<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CollectionKeeper</title>
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

<?php
session_start();
$DBConnect = new mysqli("127.0.0.1", "flbierman", "Ilikepie001!", "collection");

if ($DBConnect->connect_error) {
    die("Connection failed: " . $DBConnect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $DBConnect->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if the email already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $checkStmt = $DBConnect->prepare($checkQuery);
    if ($checkStmt === false) {
        die("Error preparing check statement: " . $DBConnect->error);
    }

    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        $_SESSION['error'] = "Error: This email is already registered.";
        header("Location: signup.php");
        exit();
    } else {
        // Prepare the SQL statement with placeholders
        $stmt = $DBConnect->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        if ($stmt === false) {
            die("Error preparing statement: " . $DBConnect->error);
        }

        // Bind the parameters to the placeholders
        $stmt->bind_param("ss", $email, $password);

        // Execute the prepared statement
        if ($stmt->execute()) {
            header("Location: signin.php");
            exit();
        } else {
            $_SESSION['error'] = "Error: " . $DBConnect->error;
            header("Location: signup.php");
            exit();
        }

        // Close the statement
        $stmt->close();
    }

    // Close the check statement and connection
    $checkStmt->close();
    $DBConnect->close();
}
?>


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

