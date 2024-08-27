<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--  CSS -->
    <link rel="stylesheet" href="style.css">
    <title>Volleyball</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Volleyball Teams</a>
            <!-- Navbar toggle button for mobile users -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDark" aria-controls="navbarDark" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="navbarDark">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 fs-5 ms-auto p-2 text-center">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="manageTeamsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manage Teams
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="manageTeamsDropdown">
                            <li><a class="dropdown-item" href="insert.php">Insert Teams</a></li>
                            <li><a class="dropdown-item" href="standing.php">Insert Matches</a></li>
                            <li><a class="dropdown-item" href="Delete.php">Delete Teams</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="updateStatusDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Update Status
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="updateStatusDropdown">
                            <li><a class="dropdown-item" href="update.php">Update Teams</a></li>
                            <li><a class="dropdown-item" href="match.php">Update Matches</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="teamRankingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Team Rankings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="teamRankingsDropdown">
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
        <h2>Update A Team</h2>
        <form action="update.php" method="post">
            <!-- Form fields -->
            <div class="mb-3">
                <label for="team_id" class="form-label">Team ID:</label>
                <input type="text" id="team_id" name="team_id" class="form-control" required value="<?php if (isset($_POST['team_id'])) echo htmlspecialchars($_POST['team_id']); ?>">
            </div>
            <div class="mb-3">
                <label for="team_name" class="form-label">Current Team Name:</label>
                <input type="text" id="team_name" name="team_name" class="form-control" required value="<?php if (isset($_POST['team_name'])) echo htmlspecialchars($_POST['team_name']); ?>">
            </div>
            <div class="mb-3">
                <label for="new_team_name" class="form-label">New Team Name:</label>
                <input type="text" id="new_team_name" name="new_team_name" class="form-control" required value="<?php if (isset($_POST['new_team_name'])) echo htmlspecialchars($_POST['new_team_name']); ?>">
            </div>
            <div class="mb-3">
                <label for="new_home_city" class="form-label">New Home City:</label>
                <input type="text" id="new_home_city" name="new_home_city" class="form-control" required value="<?php if (isset($_POST['new_home_city'])) echo htmlspecialchars($_POST['new_home_city']); ?>">
            </div>
            <div class="mb-3">
                <label for="new_venue_name" class="form-label">New Venue Name:</label>
                <input type="text" id="new_venue_name" name="new_venue_name" class="form-control" required value="<?php if (isset($_POST['new_venue_name'])) echo htmlspecialchars($_POST['new_venue_name']); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('connect.inc'); 

        $errors = array(); 

        // Validate team ID
        if (empty($_POST['team_id'])) {
            $errors[] = 'You forgot to enter your team ID.';
        } else {
            $team_id = mysqli_real_escape_string($conn, trim($_POST['team_id']));
        }

        // Validate current team name
        if (empty($_POST['team_name'])) {
            $errors[] = 'You forgot to enter the current team name.';
        } else {
            $team_name = mysqli_real_escape_string($conn, trim($_POST['team_name']));
        }

        // Validate new team name
        if (empty($_POST['new_team_name'])) {
            $errors[] = 'You forgot to enter the new team name.';
        } else {
            $new_team_name = mysqli_real_escape_string($conn, trim($_POST['new_team_name']));
        }

        // Validate new home city
        if (empty($_POST['new_home_city'])) {
            $errors[] = 'You forgot to enter the new home city.';
        } else {
            $new_home_city = mysqli_real_escape_string($conn, trim($_POST['new_home_city']));
        }

        // Validate new venue name
        if (empty($_POST['new_venue_name'])) {
            $errors[] = 'You forgot to enter the new venue name.';
        } else {
            $new_venue_name = mysqli_real_escape_string($conn, trim($_POST['new_venue_name']));
        }

        if (empty($errors)) {
            // Check if the provided team ID and team name match
            $q = "SELECT team_id, venue_id FROM Teams WHERE team_id = ? AND team_name = ?";
            $stmt = $conn->prepare($q);
            $stmt->bind_param('is', $team_id, $team_name);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) { 
                $stmt->bind_result($team_id, $current_venue_id);
                $stmt->fetch();

                // Check if the new venue exists
                $checkVenueStmt = $conn->prepare("SELECT venue_id FROM Venues WHERE venue_name = ?");
                $checkVenueStmt->bind_param("s", $new_venue_name);
                $checkVenueStmt->execute();
                $result = $checkVenueStmt->get_result();

                if ($result->num_rows > 0) {
                    // Venue exists
                    $new_venue_id = $result->fetch_assoc()['venue_id'];
                } else {
                    // Insert new venue
                    $insertVenueStmt = $conn->prepare("INSERT INTO Venues (venue_name) VALUES (?)");
                    $insertVenueStmt->bind_param("s", $new_venue_name);
                    if ($insertVenueStmt->execute()) {
                        $new_venue_id = $conn->insert_id;
                    } else {
                        echo '<h1>System Error</h1>
                        <p class="error">The venue could not be added due to a system error.</p>';
                        echo '<p>' . mysqli_error($conn) . '<br/><br />Query: ' . $insertVenueStmt->error . '</p>';
                        exit; // Exit if there was an error inserting the venue
                    }
                    $insertVenueStmt->close();
                }
                $checkVenueStmt->close();

                // Update team details
                $q = "UPDATE Teams SET team_name = ?, home_city = ?, venue_id = ? WHERE team_id = ?";
                $updateStmt = $conn->prepare($q);
                $updateStmt->bind_param("ssii", $new_team_name, $new_home_city, $new_venue_id, $team_id);

                if ($updateStmt->execute()) { 
                    echo '<h1>Success</h1>
                    <p>Your team details have been updated successfully.</p>';
                } else {
                    echo '<h1>System Error</h1>
                    <p class="error">Your team details could not be changed due to a system error.</p>';
                    echo '<p>' . mysqli_error($conn) . '<br/><br />Query: ' . $updateStmt->error . '</p>';
                }
                $updateStmt->close();
            } else {
                echo '<h1>Error!</h1>
                <p class="error">The team ID and team name combination is incorrect.</p>';
            }
            $stmt->close();
        } else {
            foreach ($errors as $msg) {
                echo "<p class='error'>$msg</p>";
            }
        }

        mysqli_close($conn); 
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
