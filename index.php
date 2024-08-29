<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS for aesthetics -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom stylesheet -->
    <link rel="stylesheet" href="style.css">
    <title>Volleyball</title>
</head>
<body>
    <!-- Includes -->
    <?php include 'includes/nav_bar.php'; ?>
    
    <!-- Carousel for Images -->
    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
            <!-- First slide -->
            <div class="carousel-item active">
                <img src="images/first-slide.jpg" class="d-block w-100" alt="First slide">
            </div>
            <!-- Second slide -->
            <div class="carousel-item">
                <img src="images/second-slide.jpg" class="d-block w-100" alt="Second slide">
            </div>
            <!-- Third slide -->
            <div class="carousel-item">
                <img src="images/third.jpg" class="d-block w-100" alt="Third slide">
            </div>
        </div>
        <!-- Carousel controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Container for displaying teams -->
    <div class="container my-5">
        <div class="row">
            <?php
            include_once('connect.inc'); // Include database connection file

            // Initialize an empty array for storing results
            $data_result = array();

            // Get search query from user
            $search_query = isset($_GET['search']) ? $_GET['search'] : '';

            // Base sql query
            $query = "SELECT * FROM teams";

            // Modify query if there's a search query
            if (!empty($search_query)) {
                $search_query = $conn->real_escape_string($search_query); 
                $query .= " WHERE team_name LIKE '%$search_query%' OR home_city LIKE '%$search_query%'";
            }

            $result = $conn->query($query); // Execute the query

            if ($result) {
                // Fetch all results into $data_result
                while ($row = $result->fetch_assoc()) {
                    $data_result[] = $row;
                }

                // Check if there are results to display
                if (!empty($data_result)) {
                    foreach ($data_result as $row) {
                        // Display each team in a card
                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card team-container h-100">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title team-header">Team Number: ' . $row['team_id'] . '</h5>';
                        echo '<p class="card-text team-details">Team Name: ' . $row['team_name'] . '</p>';
                        echo '<p class="card-text team-details">Home: ' . $row['home_city'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    // Display a message if no teams are found
                    echo '<div class="col-12"><div class="alert alert-warning">No teams found.</div></div>';
                }
            } else {
                // Display an error message if the query fails
                echo '<div class="col-12"><div class="alert alert-danger">Query failed: ' . $conn->error . '</div></div>';
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
