<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CollectionKeeper - My Games</title>
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
    <!--Navigation-->
    <div id="nav">
        <a href="index.html">Homepage</a>
        <a href="mycollection.php">MyCollection</a>
        <a href="signin.php">Sign in/up</a>
        <a href="about.html">About</a>
    </div>
</div>
<div id ="link-container">
<br> 
<a href ="mycollection.php">My Full Collection</a>
<a href ="mygames.php">My Games</a>
<a href ="mymovies.php">My Movies</a>
<a href = "mybooks.php">My Books</a><br><br>
</div>
<div id ="link-container2">
<br>
<a href ="mygamesabc.php">By Title</a>
<a href ="mygamessystem.php">By System</a>
<a href ="mygameslocation.php">By Location</a>
</div>
<!-- This is the connection to the database section using php-->
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
	
    //database connection
    $DBConnect = new mysqli("127.0.0.1", "flbierman", "Ilikepie001!", "collection");
    
    //if there is no DB connection, let the admin know 
    if ($DBConnect->connect_error){
        die("Connection to DB failed:".$DBConnect->connect_error);
    }
    
    //handle the submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
        $title = $DBConnect->real_escape_string($_POST['title']);
        $gamesystem = $DBConnect->real_escape_string($_POST['system']);
        $location = $DBConnect->real_escape_string($_POST['location']);
       
        //prepare the sql statement for inserting a new game
        $sql = $DBConnect->prepare("INSERT INTO games(title, game_system, location, user_id) VALUES(?,?,?,?)");
        $sql->bind_param("sssi", $title, $gamesystem, $location, $user_id);
        
        //execute the prepared statement and check if it was successful
        if($sql && $sql->execute()) {
            echo "<p>New game record created successfully</p>";
        } else {
            echo "<p>Error:" .$DBConnect->error."</p>";
        }
    }
    
    //fetch data from the games table
    $games_result = $DBConnect->query("SELECT title, game_system, location FROM games WHERE user_id = $user_id ORDER BY location");
?>
<!-- form for adding new game entries into the collection -->
<div class="container">
    <form action="mygames.php" method="post">
        <div class="container-form">
            <fieldset>
                <legend>Add to Games Collection</legend>
                <div>
				<label for="insert-title">Title:</label>
                <input type="text" name="title" id="insert-title" required>
				</div>
                <div>
				<label for="gamesystem">System:</label>
                <input list="system" name="system" id="gamesystem" required>
                </div>
				<div>
				<datalist id="system">
                    <option value="XBOX">
                    <option value="PS4">
                    <option value="PS5">
                    <option value="Nintendo Switch">
                    <option value="PC">
                </datalist>
				</div>
				<label for="medialocation">Location:</label>
                <input type="text" name="location" id="medialocation" required>
                </fieldset>
				<input type="submit" name="add" value="Add Game" class="styled-button">
        </div>
    </form>

<!-- display the current games collection -->
<div class="container-table">
    <h2>Games List</h2>
    <table class="MediaList">
        <thead>
            <tr>
                <th>Title</th>
                <th>System</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php
                //Loop through the games results and display each game in a table row
                if ($games_result && $games_result->num_rows > 0) {
                    while($row = $games_result->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td>
                        <td>". htmlspecialchars($row["game_system"]) . "</td>
                        <td>" . htmlspecialchars($row["location"]) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No Records Found</td></tr>";
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
            <p> © 2024 CollectionKeeper  All rights reserved.</p>
        </div>
    </footer>
</div>
</body>
</html>
