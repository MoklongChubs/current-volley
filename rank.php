<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Volleyball Standings</title>
</head>
<body>
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
                        <a class="nav-link" href="rank.php">Rankings</a></li>
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
            include_once('connect.inc');

            // Establish the database connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to get all teams by number of wins
            $query = "SELECT 
                        team_id,
                        team_name,
                        SUM(CASE 
                                WHEN home_team_id = team_id AND home_team_score > away_team_score THEN 1 
                                WHEN away_team_id = team_id AND away_team_score > home_team_score THEN 1 
                                ELSE 0 
                            END) AS wins
                    FROM 
                        Teams
                    LEFT JOIN 
                        Matches ON Teams.team_id = Matches.home_team_id OR Teams.team_id = Matches.away_team_id
                    GROUP BY 
                        team_id, team_name
                    ORDER BY 
                        wins DESC";

            $result = $conn->query($query);
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

            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
