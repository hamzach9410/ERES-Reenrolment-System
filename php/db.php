<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "university_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Uncomment the line below to test the connection directly
// echo "Connected successfully to the database!";
