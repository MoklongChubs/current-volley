<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Delete Team</title>
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Volleyball Teams</a>
            <!-- Toggle button for mobile view -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDark" aria-controls="navbarDark" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Collapsible menu -->
            <div class="collapse navbar-collapse show" id="navbarDark">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 fs-5 ms-auto p-2 text-center">
                    <!-- Navigation links -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <!-- Manage Teams dropdown-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manage Teams
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="insert.php">Insert Teams</a></li>
                            <li><a class="dropdown-item" href="standing.php">Insert Matches</a></li>
                            <li><a class="dropdown-item" href="delete.php">Delete Teams</a></li>
                        </ul>
                    </li>
                    <!-- Update Status dropdown-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Update Status
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                            <li><a class="dropdown-item" href="update.php">Update Teams</a></li>
                            <li><a class="dropdown-item" href="match.php">Update Matches</a></li>
                        </ul>
                    </li>
                    <!-- Team Rankings dropdown-->
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

    <!-- Main content for deleting a team -->
    <div class="container mt-4">
        <h2>Delete A Team</h2>
        <form action="delete.php" method="post">
            <div class="mb-3">
                <!-- Form-->
                <label for="team_id" class="form-label">Team ID:</label>
                <input type="text" id="team_id" name="team_id" class="form-control" required value="<?php if (isset($_POST['team_id'])) echo htmlspecialchars($_POST['team_id']); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-danger">Delete</button>
        </form>

        <?php
        // Check if the request method is post (form submission)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            include_once('connect.inc'); // database connecting to file

            $errors = array(); // Initialize an array to hold error messages

            // Check if team_id is provided by the user
            if (empty($_POST['team_id'])) {
                $errors[] = 'You forgot to enter the team ID.'; // Add error message if team ID is missing
            } else {
                $team_id = mysqli_real_escape_string($conn, trim($_POST['team_id']));
            }

            // If there are no errors, proceed with the deletion
            if (empty($errors)) { 
                // Prepare the DELETE query with a placeholder for the team ID
                $q = "DELETE FROM Teams WHERE team_id = ?";
                $stmt = $conn->prepare($q); // Prepare the statement
                $stmt->bind_param('i', $team_id); // Bind the team ID to the query

                // Execute the query
                if ($stmt->execute()) {
                    // Check if any rows were affected (i.e., a team was deleted)
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
                $stmt->close(); // Close the prepared statement
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
