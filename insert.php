<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS for aesthetics -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Css -->
    <link rel="stylesheet" href="style.css">
    <title>Inserting a Team</title>
</head>
<body>
    <!-- Includes -->
    <?php include 'includes/nav_bar.php'; ?>
    
    <!-- Form for adding a new team -->
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

    <?php
    include_once('connect.inc'); // Includes the database connection setup

    if (isset($_POST["submit"])) { // Check if form was submitted
        // Retrieve and POST data
        $team_name = trim($_POST["team_name"]);
        $home_city = trim($_POST["home_city"]);
        $venue_name = trim($_POST["venue_name"]);
        
        // Check if venue already exists
        $checkVenueStmt = $conn->prepare("SELECT venue_id FROM Venues WHERE venue_name = ?");
        $checkVenueStmt->bind_param("s", $venue_name);
        $checkVenueStmt->execute();
        $result = $checkVenueStmt->get_result();

        if ($result->num_rows > 0) {
            // Venue exists, get its ID
            $venue_id = $result->fetch_assoc()['venue_id'];
        } else {
            // Insert new venue and get its ID
            $insertVenueStmt = $conn->prepare("INSERT INTO Venues (venue_name) VALUES (?)");
            $insertVenueStmt->bind_param("s", $venue_name);
            if ($insertVenueStmt->execute()) {
                $venue_id = $conn->insert_id;
            } else {
                echo "Error inserting venue: " . $insertVenueStmt->error;
                exit; // Exit if there's an error inserting the venue
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
        
        $conn->close(); // Close database connection
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
