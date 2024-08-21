<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up</title>
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
<div class = "login-container">
	<fieldset>
	<legend>Sign Up</legend>
	<p> Sign up by entering your email address and password. </p>
	<?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
	<form action = "signup_process.php" method = "POST">
		<div>
		<label for="signup-email">Email:</label>
		<input type="email" id="signup-email" name="email" required>
		</div>
		<div>
		<label for="signup-password">Password:</label>
		<input type="password" id="signup-password" name="password" required>
		</div>
		<div>
		<button type="submit" class = "login-button">Sign Up</button>
		</div>
	</form>
		
	</fieldset>
</div>
<hr>
<div class = "section-4">
	<footer>
        <div id="bottomnav">
			<a href="index.html">Homepage</a>
			<a href="mycollection.php">MyCollection</a>
			<a href="signin.php">Sign in/up</a>
			<a href="about.html">About</a>
    </div>
    </footer>
	<footer>
		<div id="location"
			<p> © 2024 CollectionKeeper  All rights reserved.</p>
		</div>
	</footer>
</body>

</html>

