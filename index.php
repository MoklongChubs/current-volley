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
            <a class="navbar-brand">Volleyball</a>
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
                <form class="d-flex flex-grow-1 ms-2 me-2" method="POST" action="index.php">
                    <input class="form-control me-2 flex-grow-1" type="search" name="search" placeholder="Search Team Name" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit" name="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="first-slide.jpg" class="d-block w-100" alt="First slide">
                <p>FRANCE TOOK 2024 VNL LEAGUE WORLD CHAMPS!!</p>
            </div>
            <div class="carousel-item">
                <img src="onee.webp" class="d-block w-100" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img src="two.jpg" class="d-block w-100" alt="Third slide">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
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
                    include_once('connect.inc');

                    // Fetch matches
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
                    
                    if ($result = $conn->query($query)) {
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

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
