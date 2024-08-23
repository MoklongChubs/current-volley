<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once('connect.inc'); 

    $errors = array(); // 

    
    if (empty($_POST['team_id'])) {
        $errors[] = 'You forgot to enter the team ID.';
    } else {
        $team_id = mysqli_real_escape_string($conn, trim($_POST['team_id']));
    }

    if (empty($errors)) { 
        // DELETE query
        $q = "DELETE FROM Teams WHERE team_id = ?";
        $stmt = $conn->prepare($q);
        $stmt->bind_param('i', $team_id);

        if ($stmt->execute()) { // If it ran OK
            if ($stmt->affected_rows == 1) {
                echo '<h1>Success</h1>
                <p>The team has been deleted successfully.</p>';
            } else {
                echo '<h1>Error</h1>
                <p class="error">No team found with that ID.</p>';
            }
        } else {
            echo '<h1>System Error</h1>
            <p class="error">The team could not be deleted due to a system error.</p>';
            echo '<p>' . mysqli_error($conn) . '<br/><br />Query: ' . $stmt->error . '</p>';
        }
        $stmt->close();
    } else {
        // report the errors
        foreach ($errors as $msg) {
            echo "<p class='error'>$msg</p>";
        }
    }

    mysqli_close($conn); // close the database connection
}
?>

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
                            <li><a class="dropdown-item" href="Delete.php">Delete Teams</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Update Status
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
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
        <h2>Delete A Team</h2>
        <form action="delete.php" method="post">
            <div class="mb-3">
                <label for="team_id" class="form-label">Team ID:</label>
                <input type="text" id="team_id" name="team_id" class="form-control" required value="<?php if (isset($_POST['team_id'])) echo htmlspecialchars($_POST['team_id']); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
