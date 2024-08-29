<?php
include_once('connect.inc');

// makes the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get all teams and their total number of wins across different stages using joins
$teamsQuery = "
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
$teamsResult = $conn->query($teamsQuery);

// Query to get upcoming and ongoing games
$gamesQuery = "
    SELECT 'Match' AS stage, match_date, home_team_id, away_team_id, home_team_score, away_team_score
    FROM Matches
    WHERE match_date >= CURDATE()
    UNION
    SELECT 'QuarterFinal' AS stage, match_date, home_team_id, away_team_id, home_team_score, away_team_score
    FROM QuarterFinals
    WHERE match_date >= CURDATE()
    UNION
    SELECT 'Final' AS stage, match_date, home_team_id, away_team_id, home_team_score, away_team_score
    FROM Finals
    WHERE match_date >= CURDATE()
    ORDER BY match_date
";

// Execute the query and store the result
$gamesResult = $conn->query($gamesQuery);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS for aesthetics -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- stylesheets -->
    <link rel="stylesheet" href="style.css">
    <title>Team Rankings</title>
</head>
<body>
    <!-- Includes -->
    <?php include 'includes/nav_search.php'; ?>

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
            if ($teamsResult) {
                $rank = 1;
                while ($row = $teamsResult->fetch_assoc()) {
                    echo '<tr>';
                    echo '<th scope="row">' . $rank++ . '</th>';
                    echo '<td>' . htmlspecialchars($row['team_name']) . '</td>';
                    echo '<td>' . $row['wins'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3" class="text-center">No data available</td></tr>';
            }
            ?>
            </tbody>
        </table>

        <h2 class="mt-5">Upcoming and Ongoing Games</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Stage</th>
                    <th scope="col">Date</th>
                    <th scope="col">Home Team</th>
                    <th scope="col">Away Team</th>
                    <th scope="col">Home Score</th>
                    <th scope="col">Away Score</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Display the result of the query in a table
            if ($gamesResult) {
                while ($row = $gamesResult->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['stage']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['match_date']) . '</td>';
                    
                    // Fetch team names
                    $homeTeamResult = $conn->query("SELECT team_name FROM Teams WHERE team_id = " . $row['home_team_id']);
                    $homeTeam = $homeTeamResult->fetch_assoc()['team_name'];
                    
                    $awayTeamResult = $conn->query("SELECT team_name FROM Teams WHERE team_id = " . $row['away_team_id']);
                    $awayTeam = $awayTeamResult->fetch_assoc()['team_name'];
                    
                    echo '<td>' . htmlspecialchars($homeTeam) . '</td>';
                    echo '<td>' . htmlspecialchars($awayTeam) . '</td>';
                    echo '<td>' . htmlspecialchars($row['home_team_score']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['away_team_score']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6" class="text-center">No upcoming or ongoing games</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
