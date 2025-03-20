<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'vegetable_quiz';

$conn = mysqli_connect($db_host, $db_user, $db_password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if (!mysqli_query($conn, $sql)) {
    die("Error creating database: " . mysqli_error($conn));
}

mysqli_select_db($conn, $db_name);

// Create players table
$sql = "CREATE TABLE IF NOT EXISTS players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_name VARCHAR(100) NOT NULL,
    score INT NOT NULL,
    date_played DATETIME NOT NULL,
    time_started DATETIME NOT NULL,
    time_ended DATETIME NOT NULL,
    duration_seconds INT NOT NULL
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating players table: " . mysqli_error($conn));
}

// Create fruits_vegetables table
$sql = "CREATE TABLE IF NOT EXISTS fruits_vegetables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    type ENUM('fruit', 'vegetable') NOT NULL
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating fruits_vegetables table: " . mysqli_error($conn));
}
?>
