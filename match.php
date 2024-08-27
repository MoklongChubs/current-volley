<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Include Bootstrap CSS for aesthetics -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update Match</title>
</head>
<body>
    <!-- Navigation bar with links and dropdowns -->
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
                    <!-- Dropdown for managing teams -->
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
                    <!-- Dropdown for updating status -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Update Status
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="update.php">Update Teams</a></li>
                            <li><a class="dropdown-item" href="match.php">Update Matches</a></li>
                        </ul>
                    </li>
                    <!-- Dropdown for viewing rankings -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Team Rankings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="rank.php">Team Rankings</a></li>
                            <li><a class="dropdown-item" href="quarter.php">Quarter Final Games</a></li>
                            <li><a class="dropdown-item" href="final.php">Finals Games</a></li>
                        </ul>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Update Match</h2>

        <!-- Form to select match type (selecting if it will get the information inside tables matches, quarterfinals, or finals)  -->
        <?php
        include_once('connect.inc');

        if (!isset($_POST['match_type'])) {
            ?>
            <form action="match.php" method="post">
                <div class="mb-3">
                    <label for="match_type" class="form-label">Select Match Type:</label>
                    <select id="match_type" name="match_type" class="form-control" required>
                        <option value="">Select...</option>
                        <option value="regular">Regular Match</option>
                        <option value="quarterfinal">Quarter-Final</option>
                        <option value="final">Final</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Load Matches</button>
            </form>
            <?php
        } else {
            $match_type = $_POST['match_type'];
            $table = '';

            // Set table based on match type
            if ($match_type == 'regular') {
                $table = 'Matches';
            } elseif ($match_type == 'quarterfinal') {
                $table = 'QuarterFinals';
            } elseif ($match_type == 'final') {
                $table = 'Finals';
            }

            //  Form to select a specific match
            if (!isset($_POST['match_id'])) {
                ?>
                <form action="match.php" method="post">
                    <input type="hidden" name="match_type" value="<?php echo $match_type; ?>">
                    <div class="mb-3">
                        <label for="match_id" class="form-label">Select Match:</label>
                        <select id="match_id" name="match_id" class="form-control" required>
                            <?php
                            // Query for match options
                            $matches = $conn->query("SELECT match_id, match_date, (SELECT team_name FROM Teams WHERE team_id = $table.home_team_id) AS home_team_name, (SELECT team_name FROM Teams WHERE team_id = $table.away_team_id) AS away_team_name FROM $table");
                            while ($match = $matches->fetch_assoc()) {
                                echo '<option value="' . $match['match_id'] . '">Match on ' . $match['match_date'] . ' - ' . $match['home_team_name'] . ' vs ' . $match['away_team_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Load Match</button>
                </form>
                <?php
            } else {
                $match_id = $_POST['match_id'];
                $query = $conn->prepare("SELECT * FROM $table WHERE match_id = ?");
                $query->bind_param("i", $match_id);
                $query->execute();
                $result = $query->get_result();
                $match = $result->fetch_assoc();
                ?>

                <!-- Form to update match details -->
                <form action="match.php" method="post">
                    <input type="hidden" name="match_id" value="<?php echo $match_id; ?>">
                    <input type="hidden" name="match_type" value="<?php echo $match_type; ?>">
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
                            $teams->data_seek(0); 
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
                    <button type="submit" name="update_match" class="btn btn-primary">Update Match</button>
                </form>
                <?php
            }
        }
        ?>

        <!-- Process form submission for updating match -->
        <?php
        if (isset($_POST['update_match'])) {
            $match_date = $_POST['match_date'];
            $home_team_id = $_POST['home_team_id'];
            $away_team_id = $_POST['away_team_id'];
            $home_team_score = $_POST['home_team_score'];
            $away_team_score = $_POST['away_team_score'];
            $set_1_home_score = $_POST['set_1_home_score'];
            $set_1_away_score = $_POST['set_1_away_score'];

            $stmt = $conn->prepare("
                UPDATE $table
                SET match_date = ?, home_team_id = ?, away_team_id = ?, home_team_score = ?, away_team_score = ?, set_1_home_score = ?, set_1_away_score = ?, set_2_home_score = ?, set_2_away_score = ?, set_3_home_score = ?, set_3_away_score = ?
                WHERE match_id = ?
            ");
            $stmt->bind_param("siiidddddddi", $match_date, $home_team_id, $away_team_id, $home_team_score, $away_team_score, $set_1_home_score, $set_1_away_score, $set_2_home_score, $set_2_away_score, $set_3_home_score, $set_3_away_score, $_POST['match_id']);
            
            if ($stmt->execute()) {
                echo '<div class="alert alert-success" role="alert">Match updated successfully!</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error updating match: ' . $conn->error . '</div>';
            }
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
