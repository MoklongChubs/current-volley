<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Link to CSS and Bootstrap CSS -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Quarter Final Games</title>
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Quarter Final Games</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDark" aria-controls="navbarDark" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse show" id="navbarDark">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 fs-5 ms-auto p-2 text-center">
                    <!-- Navigation links -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <!-- Manage Teams dropdown -->
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
                    <!-- Update Status dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Update Status
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="update.php">Update Teams</a></li>
                            <li><a class="dropdown-item" href="match.php">Update Matches</a></li>
                        </ul>
                    </li>
                    <!-- Team Rankings dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Team Rankings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="rank.php">Team Rankings</a></li>
                            <li><a class="dropdown-item" href="quarter.php">Quarter Final Games</a></li>
                            <li><a class="dropdown-item" href="final.php">Finals Games</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Add a Quarter Final Game</h2>
        <!-- Form for adding a quarter final game -->
        <form action="quarter.php" method="post">
            <!-- Match Date Input -->
            <div class="mb-3">
                <label for="match_date" class="form-label">Match Date:</label>
                <input type="date" id="match_date" name="match_date" class="form-control" required>
            </div>
            <!-- Home Team Selector -->
            <div class="mb-3">
                <label for="home_team_id" class="form-label">Home Team:</label>
                <select id="home_team_id" name="home_team_id" class="form-control" required>
                    <?php
                    include_once('connect.inc');
                    $teams = $conn->query("SELECT team_id, team_name FROM Teams");
                    while ($team = $teams->fetch_assoc()) {
                        echo '<option value="' . $team['team_id'] . '">' . $team['team_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <!-- Away Team Selector -->
            <div class="mb-3">
                <label for="away_team_id" class="form-label">Away Team:</label>
                <select id="away_team_id" name="away_team_id" class="form-control" required>
                    <?php
                    $teams->data_seek(0); 
                    while ($team = $teams->fetch_assoc()) {
                        echo '<option value="' . $team['team_id'] . '">' . $team['team_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <!-- Home and Away Team Scores -->
            <div class="mb-3">
                <label for="home_team_score" class="form-label">Home Sets Won:</label>
                <input type="number" id="home_team_score" name="home_team_score" class="form-control">
            </div>
            <div class="mb-3">
                <label for="away_team_score" class="form-label">Away Sets Won:</label>
                <input type="number" id="away_team_score" name="away_team_score" class="form-control">
            </div>
            <!-- Set Scores Inputs -->
            <h3>Set Scores</h3>
            <div class="mb-3">
                <label for="set_1_home_score" class="form-label">Set 1 Home Score:</label>
                <input type="number" id="set_1_home_score" name="set_1_home_score" class="form-control">
            </div>
            <div class="mb-3">
                <label for="set_1_away_score" class="form-label">Set 1 Away Score:</label>
                <input type="number" id="set_1_away_score" name="set_1_away_score" class="form-control">
            </div>
            <div class="mb-3">
                <label for="set_2_home_score" class="form-label">Set 2 Home Score:</label>
                <input type="number" id="set_2_home_score" name="set_2_home_score" class="form-control">
            </div>
            <div class="mb-3">
                <label for="set_2_away_score" class="form-label">Set 2 Away Score:</label>
                <input type="number" id="set_2_away_score" name="set_2_away_score" class="form-control">
            </div>
            <div class="mb-3">
                <label for="set_3_home_score" class="form-label">Set 3 Home Score:</label>
                <input type="number" id="set_3_home_score" name="set_3_home_score" class="form-control">
            </div>
            <div class="mb-3">
                <label for="set_3_away_score" class="form-label">Set 3 Away Score:</label>
                <input type="number" id="set_3_away_score" name="set_3_away_score" class="form-control">
            </div>
            <!-- Submit Button -->
            <button type="submit" name="submit" class="btn btn-primary">Add Match</button>
        </form>

        <?php
        // Process form submission
        if (isset($_POST["submit"])) {
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

            // Insert data into the database
            $stmt = $conn->prepare("INSERT INTO QuarterFinals (match_date, home_team_id, away_team_id, home_team_score, away_team_score, set_1_home_score, set_1_away_score, set_2_home_score, set_2_away_score, set_3_home_score, set_3_away_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('siiiiiiiiii', $match_date, $home_team_id, $away_team_id, $home_team_score, $away_team_score, $set_1_home_score, $set_1_away_score, $set_2_home_score, $set_2_away_score, $set_3_home_score, $set_3_away_score);

            if ($stmt->execute()) {
                echo "<p class='text-success'>Quarter-Final match added successfully!</p>";
            } else {
                echo "<p class='text-danger'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }

        $conn->close();
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
