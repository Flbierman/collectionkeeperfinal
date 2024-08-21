<?php
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

$response = array('success' => false);

if (isset($_GET['title']) && isset($_GET['type'])) {
    $title = $DBConnect->real_escape_string(trim(strtolower($_GET['title'])));
    $type = $DBConnect->real_escape_string($_GET['type']);

    switch ($type) {
        case 'Game':
            $deleteSql = "DELETE FROM games WHERE LOWER(TRIM(title))='$title' AND user_id=$user_id";
            break;
        case 'Movie':
            $deleteSql = "DELETE FROM movies WHERE LOWER(TRIM(title))='$title' AND user_id=$user_id";
            break;
        case 'Book':
            $deleteSql = "DELETE FROM books WHERE LOWER(TRIM(title))='$title' AND user_id=$user_id";
            break;
        default:
            $deleteSql = "";
            break;
    }

    if (!empty($deleteSql)) {
        if ($DBConnect->query($deleteSql) === TRUE) {
            $response['success'] = true;
        } else {
            $response['error'] = "Error deleting record: " . $DBConnect->error;
        }
    } else {
        $response['error'] = "Invalid media type.";
    }
} else {
    $response['error'] = "Missing required parameters.";
}

echo json_encode($response);
$DBConnect->close();

?>
