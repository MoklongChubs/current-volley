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
                <form class="d-flex flex-grow-1 ms-2 me-2" method="GET" action="index.php">
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
        <div class="row">
            <?php
            include_once('connect.inc');

            // Initialize $data_result
            $data_result = array();

            // Check if a search query is set
            $search_query = isset($_GET['search']) ? $_GET['search'] : '';

            // Base query
            $query = "SELECT * FROM teams";

            // Add a search filter if there's a search query
            if (!empty($search_query)) {
                $search_query = $conn->real_escape_string($search_query); // Escape special characters for security
                $query .= " WHERE team_name LIKE '%$search_query%' OR home_city LIKE '%$search_query%'";
            }

            $result = $conn->query($query);

            if ($result) {
                // Fetch all results into $data_result
                while ($row = $result->fetch_assoc()) {
                    $data_result[] = $row;
                }

                // Check if there are results to display
                if (!empty($data_result)) {
                    foreach ($data_result as $row) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card team-container h-100">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title team-header">Team Number: ' . $row['team_id'] . '</h5>';
                        echo '<p class="card-text team-details">Team Name: ' . $row['team_name'] . '</p>';
                        echo '<p class="card-text team-details">Home: ' . $row['home_city'] . '</p>';
                        // echo '<p class="card-text team-details">Venue: ' . $row['venue_name'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-warning">No teams found.</div></div>';
                }
            } else {
                echo '<div class="col-12"><div class="alert alert-danger">Query failed: ' . $conn->error . '</div></div>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
