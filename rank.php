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
                    <li class="nav-item">
                        <a class="nav-link" href="rank.php">Rankings</a>
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
            
            // Get and display matches 
            $result->free(); 
            
            // Query to get match details
            $query = "
                SELECT 
                    Matches.match_date, 
                    ht.team_name AS home_team_name, 
                    at.team_name AS away_team_name, 
                    Matches.home_team_score, 
                    Matches.away_team_score,
                    Matches.set_1_home_score, Matches.set_1_away_score,
                    Matches.set_2_home_score, Matches.set_2_away_score,
                    Matches.set_3_home_score, Matches.set_3_away_score
                FROM Matches
                JOIN Teams ht ON Matches.home_team_id = ht.team_id
                JOIN Teams at ON Matches.away_team_id = at.team_id
                ORDER BY Matches.match_date DESC";

            $result = $conn->query($query);
            ?>
            </tbody>
        </table>
    </div>

    <div class="container my-5">
        <h2>Matches</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Match Date</th>
                        <th>Home Team</th>
                        <th>Away Team</th>
                        <th>Home Score</th>
                        <th>Away Score</th>
                        <th>Set 1 (Home-Away)</th>
                        <th>Set 2 (Home-Away)</th>
                        <th>Set 3 (Home-Away)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['match_date']}</td>
                                    <td>{$row['home_team_name']}</td>
                                    <td>{$row['away_team_name']}</td>
                                    <td>{$row['home_team_score']}</td>
                                    <td>{$row['away_team_score']}</td>
                                    <td>{$row['set_1_home_score']} - {$row['set_1_away_score']}</td>
                                    <td>{$row['set_2_home_score']} - {$row['set_2_away_score']}</td>
                                    <td>{$row['set_3_home_score']} - {$row['set_3_away_score']}</td>
                                </tr>";
                            }
                        } else {
                            echo '<tr><td colspan="8" class="text-center">No matches found.</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="8" class="text-center">Query failed: ' . htmlspecialchars($conn->error) . '</td></tr>';
                    }

                    $result->free(); // Free the result
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Teams Table -->
    <div class="container my-5">
        <h2>Finals</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Match</th>
                    <th scope="col">Team 1</th>
                    <th scope="col">Team 2</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to get top teams (assuming we want the top 2)
                $query = "SELECT 
                            team_name
                        FROM (
                            SELECT 
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
                                wins DESC
                            LIMIT 2
                        ) AS top_teams";

                $result = $conn->query($query);
                if ($result) {
                    if ($result->num_rows == 2) {
                        $teams = [];
                        while ($row = $result->fetch_assoc()) {
                            $teams[] = $row['team_name'];
                        }
                        echo "<tr>
                            <td>Final</td>
                            <td>{$teams[0]}</td>
                            <td>{$teams[1]}</td>
                        </tr>";
                    } else {
                        echo '<tr><td colspan="3" class="text-center">Not enough teams to determine finalists.</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="3" class="text-center">Query failed: ' . htmlspecialchars($conn->error) . '</td></tr>';
                }

                $conn->close(); // Close the connection
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
