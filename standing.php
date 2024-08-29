<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS for aesthetics -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- stylesheets -->
    <link rel="stylesheet" href="style.css">
    <title>Insert a Match</title>
</head>
<body>
    <!-- Includes -->
    <?php include 'includes/nav_bar.php'; ?>

    <div class="container mt-4">
        <h2>Add a Match</h2>
        <!-- Form for adding a match -->
        <form action="standing.php" method="post">
            <div class="mb-3">
                <label for="match_date" class="form-label">Match Date:</label>
                <input type="date" id="match_date" name="match_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="home_team_id" class="form-label">Home Team:</label>
                <select id="home_team_id" name="home_team_id" class="form-control" required>
                    <?php
                    // Get and display team options for the home team
                    include_once('connect.inc');
                    $teams = $conn->query("SELECT team_id, team_name FROM Teams");
                    while ($team = $teams->fetch_assoc()) {
                        echo '<option value="' . $team['team_id'] . '">' . $team['team_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="away_team_id" class="form-label">Away Team:</label>
                <select id="away_team_id" name="away_team_id" class="form-control" required>
                    <?php
                    // Reuse the same team data for the away team dropdown
                    $teams->data_seek(0); 
                    while ($team = $teams->fetch_assoc()) {
                        echo '<option value="' . $team['team_id'] . '">' . $team['team_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="home_team_score" class="form-label">Home Sets Won:</label>
                <input type="number" id="home_team_score" name="home_team_score" class="form-control">
            </div>
            <div class="mb-3">
                <label for="away_team_score" class="form-label">Away Sets Won:</label>
                <input type="number" id="away_team_score" name="away_team_score" class="form-control">
            </div>
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
            <button type="submit" name="submit" class="btn btn-primary">Add Match</button>
        </form>
    </div>

    <?php
    if (isset($_POST["submit"])) {
        // Retrieve and POST data
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

        // Prepare SQL statement for inserting match data
        $insertMatchStmt = $conn->prepare("
            INSERT INTO Matches (
                match_date, home_team_id, away_team_id, 
                home_team_score, away_team_score, 
                set_1_home_score, set_1_away_score, 
                set_2_home_score, set_2_away_score, 
                set_3_home_score, set_3_away_score
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $insertMatchStmt->bind_param("siiiiiiiiii", 
            $match_date, $home_team_id, $away_team_id, 
            $home_team_score, $away_team_score, 
            $set_1_home_score, $set_1_away_score, 
            $set_2_home_score, $set_2_away_score, 
            $set_3_home_score, $set_3_away_score);

        // Check execution result
        if ($insertMatchStmt->execute()) {
            echo "<div class='alert alert-success mt-3'>New match record created successfully!</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error inserting match: " . $insertMatchStmt->error . "</div>";
        }

        // Close the statement
        $insertMatchStmt->close();
    }

    // Close the database connection
    $conn->close();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
