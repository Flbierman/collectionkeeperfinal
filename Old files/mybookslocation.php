<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CollectionKeeper - My Books</title>
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
<a href ="mybooksabc.php">By Title</a>
<a href ="mybookslocation.php">By Location</a>
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
        $location = $DBConnect->real_escape_string($_POST['location']);
       
        //prepare the sql statement for inserting a new book
        $sql = $DBConnect->prepare("INSERT INTO books(title, location, user_id) VALUES(?,?,?)");
        $sql->bind_param("ssi", $title, $location, $user_id);
        
        //execute the prepared statement and check if it was successful
        if($sql && $sql->execute()) {
            echo "<p>New book record created successfully</p>";
        } else {
            echo "<p>Error:" .$DBConnect->error."</p>";
        }
    }
    
    //fetch data from the books table
    $books_result = $DBConnect->query("SELECT title, location FROM books WHERE user_id = $user_id ORDER BY location");
?>
<!-- form for adding new book entries into the collection -->
<div class="container">
    <form action="mybooks.php" method="post">
        <div class="container-form">
            <fieldset>
                <legend>Add to Books Collection</legend>
                <div>
				<label for="insert-title">Title:</label>
                <input type="text" name="title" id="insert-title" required>
                </div>
				<label for="medialocation">Location:</label>
                <input type="text" name="location" id="medialocation" required>
				</fieldset>
				<input type="submit" name="add" value="Add Book" class="styled-button">
		</div>
    </form>


<!-- display the current books collection -->
<div class="container-table">
    <h2>Books List</h2>
    <table class="MediaList">
        <thead>
            <tr>
                <th>Title</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php
                //Loop through the books results and display each book in a table row
                if ($books_result && $books_result->num_rows > 0) {
                    while($row = $books_result->fetch_assoc()) {
                        echo "<tr><td>" . htmlspecialchars($row["title"]) . "</td>
                        <td>" . htmlspecialchars($row["location"]) . "</td>
                        </tr>";
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
            <p> © 2024 CollectionKeeper  All rights reserved.</p>
        </div>
    </footer>
</div>
</body>
</html>
