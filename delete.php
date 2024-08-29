<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS for aesthetics -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- stylesheets -->
    <link rel="stylesheet" href="style.css">
    <title>Delete Team</title>
</head>
<body>
    <!-- Includes -->
    <?php include 'includes/nav_bar.php'; ?>

    <!-- Main content for deleting a team -->
    <div class="container mt-4">
        <h2>Delete A Team</h2>
        <form action="delete.php" method="post">
            <div class="mb-3">
                <!-- Form -->
                <label for="team_id" class="form-label">Team ID:</label>
                <input type="text" id="team_id" name="team_id" class="form-control" required value="<?php if (isset($_POST['team_id'])) echo htmlspecialchars($_POST['team_id']); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-danger">Delete</button>
        </form>

        <?php
        // Check if the request method is POST (form submission)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            include_once('connect.inc'); // Database connection file

            $errors = array(); // Initialize an array to hold error messages

            // Check if team_id is provided by the user
            if (empty($_POST['team_id'])) {
                $errors[] = 'You forgot to enter the team ID.'; // Add error message if team ID is missing
            } else {
                $team_id = mysqli_real_escape_string($conn, trim($_POST['team_id']));
            }

            // If there are no errors, proceed with the deletion
            if (empty($errors)) { 
                // Prepare the DELETE query for the team ID
                $q = "DELETE FROM Teams WHERE team_id = ?";
                $stmt = $conn->prepare($q); // Prepare the statement
                $stmt->bind_param('i', $team_id); // Bind the team ID to the query

                // Execute the query
                if ($stmt->execute()) {
                    if ($stmt->affected_rows == 1) {
                        echo '<h1>Success</h1>
                        <p>The team has been deleted successfully.</p>';
                    } else {
                        echo '<h1>Error</h1>
                        <p class="error">No team found with that ID.</p>';
                    }
                } else {
                    // Display an error message if the query fails
                    echo '<h1>System Error</h1>
                    <p class="error">The team could not be deleted due to a system error.</p>';
                    echo '<p>' . mysqli_error($conn) . '<br/><br />Query: ' . $stmt->error . '</p>';
                }
                $stmt->close(); // Close the statement
            } else {
                // Report any validation errors
                foreach ($errors as $msg) {
                    echo "<p class='error'>$msg</p>";
                }
            }

            mysqli_close($conn); // Close the database connection
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
