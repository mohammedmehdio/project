<?php
session_start(); // Only call this once in your project, if needed

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inscription_clubhub";
$port = 4000; // Your custom MySQL port 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); // Log the error
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}
?>
