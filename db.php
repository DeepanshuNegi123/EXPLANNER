<?php
$host = "localhost";
$user = "root"; // Default XAMPP MySQL user
$password = ""; // Default XAMPP MySQL password (leave empty)
$database = "experiment_db"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
                    