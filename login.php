<?php
session_start(); // Start the session to track user login status

$DBConnect = new mysqli("127.0.0.1", "flbierman", "Ilikepie001!", "collection");

if ($DBConnect->connect_error) {
    die("Connection failed: " . $DBConnect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $DBConnect->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    // Prepare the SQL statement with placeholders
    $stmt = $DBConnect->prepare("SELECT user_id, password FROM users WHERE email = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $DBConnect->error);
    }
    
    // Bind the parameter to the placeholder
    $stmt->bind_param("s", $email);
    
    // Execute the prepared statement
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();
    
    // Verify the password and set session variables
    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $email;
        header("Location: mycollection.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: signin.php");
        exit();
    }
    
    // Close the statement and connection
    $stmt->close();
    $DBConnect->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In</title>
<link rel="stylesheet" type="text/css" href="css/myStyle.css">
</head>

<body>
<header>
    <picture class="title-image">
        <source srcset="Images/collectionkeepersmall.jpeg" media="(max-width: 480px)">
        <img src="Images/collectionkeepermedium.jpeg" alt="CollectionKeeper" media="(min-width: 481px)">
        <!--<img src="Images/collectionkeeper.jpeg" alt="CollectionKeeper" media = "(min-width: 1081px)">-->
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
<div class="login-container">
    <fieldset>
        <legend>Sign in</legend>
        <p> Log in by entering your email address and password. </p>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="login.php" method="POST">
            <div>
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required>
            </div>
            <div>
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>
            </div>
            <div>
				<button type="submit" class = "login-button">Sign In</button>
			</div>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here!</a></p>
    </fieldset>
</div>
<hr>
<div class="section-4">
    <footer>
        <div id="bottomnav">
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
