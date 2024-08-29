<!-- Navigation bar that can be a smaller menu for smaller screens -->
<nav class="navbar navbar-expand-xl navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">Volleyball</a>
        <!-- Toggle button for mobile view -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDark" aria-controls="navbarDark" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Collapsible menu -->
        <div class="collapse navbar-collapse show" id="navbarDark">
            <ul class="navbar-nav me-auto mb-2 mb-xl-0 fs-5 ms-auto p-2 text-center">
                <!-- Home link -->
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <!-- Manage Teams dropdown -->
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
                <!-- Update Status dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Update Status
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li><a class="dropdown-item" href="update.php">Update Teams</a></li>
                        <li><a class="dropdown-item" href="match.php">Update Matches</a></li>
                    </ul>
                </li>
                <!-- Team Rankings dropdown -->
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
            <!-- Search form -->
            <form class="d-flex flex-grow-1 ms-2 me-2" method="GET" action="index.php">
                <input class="form-control me-2 flex-grow-1" type="search" name="search" placeholder="Search Team Name" aria-label="Search">
                <button class="btn btn-outline-light" type="submit" name="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
