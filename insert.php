<?php
// Connect to the database
include_once('connect.inc'); // Ensure this file contains the database connection setup

if (isset($_POST["submit"])) {
    // Retrieve and sanitize POST data
    $team_name = trim($_POST["team_name"]);
    $home_city = trim($_POST["home_city"]);
    $venue_name = trim($_POST["venue_name"]);
    
    // Find or insert the venue
    $checkVenueStmt = $conn->prepare("SELECT venue_id FROM Venues WHERE venue_name = ?");
    $checkVenueStmt->bind_param("s", $venue_name);
    $checkVenueStmt->execute();
    $result = $checkVenueStmt->get_result();

    if ($result->num_rows > 0) {
        // Venue exists
        $venue_id = $result->fetch_assoc()['venue_id'];
    } else {
        // Insert new venue
        $insertVenueStmt = $conn->prepare("INSERT INTO Venues (venue_name) VALUES (?)");
        $insertVenueStmt->bind_param("s", $venue_name);
        if ($insertVenueStmt->execute()) {
            $venue_id = $conn->insert_id;
        } else {
            echo "Error inserting venue: " . $insertVenueStmt->error;
            exit; // Exit if there was an error inserting the venue
        }
        $insertVenueStmt->close();
    }
    $checkVenueStmt->close();

    // Insert team data into Teams table
    $insertTeamStmt = $conn->prepare("INSERT INTO Teams (team_name, home_city, venue_id) VALUES (?, ?, ?)");
    $insertTeamStmt->bind_param("ssi", $team_name, $home_city, $venue_id);
    if ($insertTeamStmt->execute()) {
        echo "New team record created successfully!";
    } else {
        echo "Error inserting team: " . $insertTeamStmt->error;
    }
    $insertTeamStmt->close();
    
    $conn->close();
}
?>





<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Volleyball</title>
</head>
<body>
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Volleyball Teams</a>
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
    </nav>
    <div class="container mt-4">
        <h2>Add New Team</h2>
        <form action="insert.php" method="post">
            <div class="mb-3">
                <label for="team_name" class="form-label">Team Name:</label>
                <input type="text" id="team_name" name="team_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="home_city" class="form-label">Home City:</label>
                <input type="text" id="home_city" name="home_city" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="venue_name" class="form-label">Venue Name:</label>
                <input type="text" id="venue_name" name="venue_name" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
