<?php
include_once('connect.inc');

// makes the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get all teams and their total number of wins across different stages using joins
$query = "
    SELECT 
        t.team_id,
        t.team_name,
        COALESCE(
            (SELECT COUNT(*) FROM Matches m
                WHERE (m.home_team_id = t.team_id AND m.home_team_score > m.away_team_score)
                   OR (m.away_team_id = t.team_id AND m.away_team_score > m.home_team_score)
            ) +
            (SELECT COUNT(*) FROM QuarterFinals qf
                WHERE (qf.home_team_id = t.team_id AND qf.home_team_score > qf.away_team_score)
                   OR (qf.away_team_id = t.team_id AND qf.away_team_score > qf.home_team_score)
            ) +
            (SELECT COUNT(*) FROM Finals f
                WHERE (f.home_team_id = t.team_id AND f.home_team_score > f.away_team_score)
                   OR (f.away_team_id = t.team_id AND f.away_team_score > f.home_team_score)
            ), 0) AS wins
    FROM Teams t
    ORDER BY wins DESC
";

// Execute the query and store the result
$result = $conn->query($query);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Link to CSS and Bootstrap CSS -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Team Rankings</title>
</head>
<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-xl navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand">Team Rankings</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDark" aria-controls="navbarDark" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse show" id="navbarDark">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 fs-5 ms-auto p-2 text-center">
                    <!-- Navigation links -->
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
        <h2>Team Rankings</h2>
        <table class="table rankings">
            <thead>
                <tr>
                    <th scope="col">Rank</th>
                    <th scope="col">Team Name</th>
                    <th scope="col">Wins</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Display the result of the query in a table
            if ($result) {
                $rank = 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<th scope="row">' . $rank++ . '</th>';
                    echo '<td>' . htmlspecialchars($row['team_name']) . '</td>';
                    echo '<td>' . $row['wins'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3" class="text-center">No data available</td></tr>';
            }

            // Close the connection
            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
