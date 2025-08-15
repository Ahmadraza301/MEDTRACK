<?php 
// Database configuration for local XAMPP
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'medtrack_db';

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

// Optional: Set timezone
mysqli_query($conn, "SET time_zone = '+05:30'");
?>