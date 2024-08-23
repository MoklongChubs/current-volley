<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Match</title>
</head>
<body>
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Volleyball Standings</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDark" aria-controls="navbarDark" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse show" id="navbarDark">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 fs-5 ms-auto p-2 text-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manage Teams
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="insert.php">Insert Teams</a></li>
                            <li><a class="dropdown-item" href="standing.php">Insert Matches</a></li>
                            <li><a class="dropdown-item" href="Delete.php">Delete Teams</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Update Status
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="update.php">Update Teams</a></li>
                            <li><a class="dropdown-item" href="match.php">Update Matches</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rank.php">Rankings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Update Match</h2>

        <!-- Form to Select a Match to Update -->
        <form action="match.php" method="post">
            <div class="mb-3">
                <label for="match_id" class="form-label">Select Match:</label>
                <select id="match_id" name="match_id" class="form-control" required>
                    <?php
                    include_once('connect.inc');
                    $matches = $conn->query("SELECT match_id, match_date, (SELECT team_name FROM Teams WHERE team_id = Matches.home_team_id) AS home_team_name, (SELECT team_name FROM Teams WHERE team_id = Matches.away_team_id) AS away_team_name FROM Matches");
                    while ($match = $matches->fetch_assoc()) {
                        echo '<option value="' . $match['match_id'] . '">Match on ' . $match['match_date'] . ' - ' . $match['home_team_name'] . ' vs ' . $match['away_team_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="load_match" class="btn btn-primary">Load Match</button>
        </form>

        <?php
        if (isset($_POST['load_match'])) {
            $match_id = $_POST['match_id'];
            $query = $conn->prepare("SELECT * FROM Matches WHERE match_id = ?");
            $query->bind_param("i", $match_id);
            $query->execute();
            $result = $query->get_result();
            $match = $result->fetch_assoc();
        ?>
        
        <!-- Form to Update Match Details -->
        <form action="match.php" method="post">
            <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
            <div class="mb-3">
                <label for="match_date" class="form-label">Match Date:</label>
                <input type="date" id="match_date" name="match_date" class="form-control" required value="<?php echo $match['match_date']; ?>">
            </div>
            <div class="mb-3">
                <label for="home_team_id" class="form-label">Home Team:</label>
                <select id="home_team_id" name="home_team_id" class="form-control" required>
                    <?php
                    $teams = $conn->query("SELECT team_id, team_name FROM Teams");
                    while ($team = $teams->fetch_assoc()) {
                        echo '<option value="' . $team['team_id'] . '"' . ($team['team_id'] == $match['home_team_id'] ? ' selected' : '') . '>' . $team['team_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="away_team_id" class="form-label">Away Team:</label>
                <select id="away_team_id" name="away_team_id" class="form-control" required>
                    <?php
                    $teams->data_seek(0); // Reset the pointer to the start
                    while ($team = $teams->fetch_assoc()) {
                        echo '<option value="' . $team['team_id'] . '"' . ($team['team_id'] == $match['away_team_id'] ? ' selected' : '') . '>' . $team['team_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="home_team_score" class="form-label">Home Team Score:</label>
                <input type="number" id="home_team_score" name="home_team_score" class="form-control" required value="<?php echo $match['home_team_score']; ?>">
            </div>
            <div class="mb-3">
                <label for="away_team_score" class="form-label">Away Team Score:</label>
                <input type="number" id="away_team_score" name="away_team_score" class="form-control" required value="<?php echo $match['away_team_score']; ?>">
            </div>
            <h3>Set Scores</h3>
            <div class="mb-3">
                <label for="set_1_home_score" class="form-label">Set 1 Home Score:</label>
                <input type="number" id="set_1_home_score" name="set_1_home_score" class="form-control" required value="<?php echo $match['set_1_home_score']; ?>">
            </div>
            <div class="mb-3">
                <label for="set_1_away_score" class="form-label">Set 1 Away Score:</label>
                <input type="number" id="set_1_away_score" name="set_1_away_score" class="form-control" required value="<?php echo $match['set_1_away_score']; ?>">
            </div>
            <div class="mb-3">
                <label for="set_2_home_score" class="form-label">Set 2 Home Score:</label>
                <input type="number" id="set_2_home_score" name="set_2_home_score" class="form-control" required value="<?php echo $match['set_2_home_score']; ?>">
            </div>
            <div class="mb-3">
                <label for="set_2_away_score" class="form-label">Set 2 Away Score:</label>
                <input type="number" id="set_2_away_score" name="set_2_away_score" class="form-control" required value="<?php echo $match['set_2_away_score']; ?>">
            </div>
            <div class="mb-3">
                <label for="set_3_home_score" class="form-label">Set 3 Home Score:</label>
                <input type="number" id="set_3_home_score" name="set_3_home_score" class="form-control" value="<?php echo $match['set_3_home_score']; ?>">
            </div>
            <div class="mb-3">
                <label for="set_3_away_score" class="form-label">Set 3 Away Score:</label>
                <input type="number" id="set_3_away_score" name="set_3_away_score" class="form-control" value="<?php echo $match['set_3_away_score']; ?>">
            </div>
            <button type="submit" name="update_match" class="btn btn-primary">Update Match</button>
        </form>
        <?php
        }
        ?>

        <?php
        if (isset($_POST["update_match"])) {
            // Retrieve and sanitize POST data
            $match_id = $_POST["match_id"];
            $match_date = $_POST["match_date"];
            $home_team_id = $_POST["home_team_id"];
            $away_team_id = $_POST["away_team_id"];
            $home_team_score = $_POST["home_team_score"];
            $away_team_score = $_POST["away_team_score"];
            $set_1_home_score = $_POST["set_1_home_score"];
            $set_1_away_score = $_POST["set_1_away_score"];
            $set_2_home_score = $_POST["set_2_home_score"];
            $set_2_away_score = $_POST["set_2_away_score"];
            $set_3_home_score = $_POST["set_3_home_score"];
            $set_3_away_score = $_POST["set_3_away_score"];

            // Prepare the UPDATE statement for match data
            $updateMatchStmt = $conn->prepare("
                UPDATE Matches SET 
                    match_date = ?, home_team_id = ?, away_team_id = ?, 
                    home_team_score = ?, away_team_score = ?, 
                    set_1_home_score = ?, set_1_away_score = ?, 
                    set_2_home_score = ?, set_2_away_score = ?, 
                    set_3_home_score = ?, set_3_away_score = ? 
                WHERE match_id = ?");
            
            $updateMatchStmt->bind_param("siiiiiiiiiii", 
                $match_date, $home_team_id, $away_team_id, 
                $home_team_score, $away_team_score, 
                $set_1_home_score, $set_1_away_score, 
                $set_2_home_score, $set_2_away_score, 
                $set_3_home_score, $set_3_away_score, $match_id);

            // Execute the statement and check for success
            if ($updateMatchStmt->execute()) {
                echo "<div class='alert alert-success mt-3'>Match record updated successfully!</div>";
            } else {
                echo "<div class='alert alert-danger mt-3'>Error updating match: " . $updateMatchStmt->error . "</div>";
            }

            // Close the statement
            $updateMatchStmt->close();
        }

        // Close the database connection
        $conn->close();
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>