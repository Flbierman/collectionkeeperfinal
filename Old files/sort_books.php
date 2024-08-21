<?php
// Start the session to track user login status
session_start();

// Check if the user is logged in. If not, return an error response.
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

// Get the user_id of the logged-in user
$user_id = $_SESSION['user_id'];

// Database connection
$DBConnect = new mysqli("127.0.0.1", "flbierman", "Ilikepie001!", "collection");

// If there is no DB connection, return an error response.
if ($DBConnect->connect_error) {
    echo json_encode(['error' => 'Connection to DB failed: ' . $DBConnect->connect_error]);
    exit();
}

// Get the sort criteria from the request
$sort_order = isset($_GET['sort']) ? $DBConnect->real_escape_string($_GET['sort']) : 'title';

// Fetch and sort data from the database
$books_result = $DBConnect->query("SELECT title, location FROM books WHERE user_id = $user_id ORDER BY $sort_order");
$books = [];
if ($books_result && $books_result->num_rows > 0) {
    while ($row = $books_result->fetch_assoc()) {
        $books[] = $row;
    }
}

// Return the sorted data as JSON
echo json_encode($books);
?>
