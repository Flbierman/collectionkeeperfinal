<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CollectionKeeper - My Movies</title>
<link rel="stylesheet" type="text/css" href="css/collectionstyle.css">
</head>
<body>
<header>
    <picture class="title-image">
        <source srcset="Images/collectionkeepersmall.jpeg" media="(max-width: 480px)">
        <img src="Images/collectionkeepermedium.jpeg" alt="CollectionKeeper" media="(min-width: 481px)">
    </picture>
</header>
<div class="top-bar">
    <div id="cchandle">
        <a href="https://x.com/CollectionKeep" target="_blank">@CollectionKeeper</a>
    </div>
    <!-- Navigation -->
    <div id="nav">
        <a href="index.html">Homepage</a>
        <a href="mycollection.php">MyCollection</a>
        <a href="signin.php">Sign in/up</a>
        <a href="about.html">About</a>
    </div>
</div>
<div id="link-container">
    <br>
    <a href="mycollection.php">My Full Collection</a>
    <a href="mygames.php">My Games</a>
    <a href="mymovies.php">My Movies</a>
    <a href="mybooks.php">My Books</a>
    <br><br>
</div>
<div id="link-container2">
    <br>
    <a href="mymoviesabc.php">By Title</a>
    <a href="mymovieslocation.php">By Location</a>
</div>

<!-- This is the connection to the database section using PHP -->
<?php
    // Start the session to track user login status
    session_start();
    // Check if the user is logged in. If not, redirect to the sign-in page.
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }

    // Get the user_id of the logged-in user
    $user_id = $_SESSION['user_id'];

    // Database connection
    $DBConnect = new mysqli("127.0.0.1", "flbierman", "Ilikepie001!", "collection");

    // If there is no DB connection, let the admin know
    if ($DBConnect->connect_error) {
        die("Connection to DB failed:" . $DBConnect->connect_error);
    }

    // Handle the submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $title = $DBConnect->real_escape_string($_POST['title']);
        $location = $DBConnect->real_escape_string($_POST['location']);

        // Prepare the SQL statement for inserting a new movie
        $sql = $DBConnect->prepare("INSERT INTO movies(title, location, user_id) VALUES(?,?,?)");
        $sql->bind_param("ssi", $title, $location, $user_id);

        // Execute the prepared statement and check if it was successful
        if ($sql && $sql->execute()) {
            echo "<p>New movie record created successfully</p>";
        } else {
            echo "<p>Error:" . $DBConnect->error . "</p>";
        }
    }

    // Fetch data from the movies table
    $movies_result = $DBConnect->query("SELECT title, location FROM movies WHERE user_id = $user_id ORDER BY title");
?>
<!-- Form for adding new movie entries into the collection -->
<div class="container">
    <form action="mymovies.php" method="post">
        <div class="container-form">
            <fieldset>
                <legend>Add to Movies Collection</legend>
                <div>
                    <label for="insert-title">Title:</label>
                    <input type="text" name="title" id="insert-title" required>
                </div>
                <div>
                    <label for="medialocation">Location:</label>
                    <input type="text" name="location" id="medialocation" required>
                </div>
            </fieldset>
            <input type="submit" name="add" value="Add Movie" class="styled-button">
        </div>
    </form>

    <!-- Display the current movies collection -->
    <div class="container-table">
        <h2>Movies List</h2>
        <table class="MediaList">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Loop through the movies results and display each movie in a table row
                    if ($movies_result && $movies_result->num_rows > 0) {
                        while ($row = $movies_result->fetch_assoc()) {
                            echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td>
                                  <td>" . htmlspecialchars($row["location"]) . "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No Records Found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>
<hr>
<div class="section-4">
    <footer>
        <div id="nav">
            <a href="index.html">Homepage</a>
            <a href="mycollection.php">MyCollection</a>
            <a href="signin.php">Sign in/up</a>
            <a href="about.html">About</a>
        </div>
    </footer>
    <footer>
        <div id="location">
            <p>© 2024 CollectionKeeper All rights reserved.</p>
        </div>
    </footer>
</div>
</body>
</html>
