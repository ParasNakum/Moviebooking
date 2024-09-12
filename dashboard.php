<?php 
session_start();
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];

    // Establish connection to the database
    $con = new mysqli("localhost", "root", "", "moviebooking") or die("Connection failed:" . $con->connect_error);

    // Prepare and execute SQL query to fetch the user's name based on the email
    $sql = "SELECT fullname FROM users WHERE email = '$email'";
    $result = $con->query($sql);

    // Check if the query executed successfully and fetched a row
    if ($result->num_rows > 0) {
        // Fetch the row and extract the fullname
        $row = $result->fetch_assoc();
        $fullname = $row['fullname'];
    } else {
        // If no user found with the provided email, redirect to login page
        header("location: login.php");
    }

    // Close the database connection
    $con->close();
} else {
    // If session email is not set, redirect to login page
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1">
    
    <title>Movie Review</title>

    <!-- Loading third party fonts -->
    <link href="http://fonts.googleapis.com/css?family=Roboto:300,400,700|" rel="stylesheet" type="text/css">
    <link href="fonts/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Loading main css file -->
    <link rel="stylesheet" href="style.css">
    
    <!--[if lt IE 9]>
    <script src="js/ie-support/html5.js"></script>
    <script src="js/ie-support/respond.js"></script>
    <![endif]-->

    <style>
        /* Custom styles for the poster grid */
        .poster-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(calc(33.33% - 20px), 1fr)); /* Adjusted to display 3 movies per line */
            gap: 20px;
        }

        .poster-item img {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div id="site-content">
        <header class="site-header">
            <div class="container">
                <a href="index.html" id="branding">
                    <img src="images/logo.png" alt="" class="logo">
                    <div class="logo-copy">
                        <h1 class="site-title"> MY SHOW</h1>
                        <h2>Welcome <?php echo $fullname; ?></h2>
                    </div>
                </a>

                <div class="main-navigation">
                    <button type="button" class="menu-toggle"><i class="fa fa-bars"></i></button>
                    <ul class="menu">
                        <li class="menu-item current-menu-item"><a href="dashboard.php">Home</a></li>
                        <li class="menu-item"><a href="about.html">About</a></li>
                        <!--<li class="menu-item"><a href="review.html">Movie reviews</a></li>-->
                        <li class="menu-item"><a href="joinus.html">Join us</a></li>
                        <li class="menu-item"><a href="contact.html">Contact</a></li>
                        <li class="menu-item"><a href="login.php">Logout</a></li>
                    </ul>

                    <form action="#" class="search-form" method="GET">
                        <input type="text" name="search" placeholder="Search..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="mobile-navigation"></div>
            </div>
        </header>

        <main class="main-content">
            <div class="container">
                <div class="page">
                    <div class="row">
                        <!-- Fetch poster image from database and display in the grid -->
                        <ul class="poster-grid">
                            <?php 
                            // Establish connection to the database
                            $con = new mysqli("localhost", "root", "", "moviebooking") or die("Connection failed:" . $con->connect_error);

                            // Get search query if available
                            $search = isset($_GET['search']) ? $con->real_escape_string($_GET['search']) : '';

                            // Modify the SQL query to include a search condition
                            $sql = "SELECT movieId, moviename, posterImage FROM movies";
                            if ($search) {
                                $sql .= " WHERE moviename LIKE '%$search%'";
                            }

                            $result = $con->query($sql);

                            // Display each poster image with its movie name in the grid
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    // Display each movie as a link to its details page
                                    echo '<li class="poster-item">';
                                    echo '<a href="movieDetails.php?id=' . $row["movieId"] . '">';
                                    echo '<img src="' . $row["posterImage"] . '" alt="Poster" style="height:200px;width:400px">';
                                    echo '<br><br><h3 style="margin-left:150px;">' . $row["moviename"] . '</h3>';
                                    echo '</a>';
                                    echo '</li>';
                                }
                            } else {
                                echo '<li>No movies found.</li>';
                            }
                            $con->close();
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </main>

        <footer class="site-footer">
            <!-- Footer content -->
        </footer>
    </div>

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
