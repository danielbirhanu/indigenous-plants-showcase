<?php
// Database connection for XAMPP MySQL running on port 3307.
$host = "127.0.0.1";
$port = "3307";
$dbName = "indigenous_plants_db";
$username = "root";
$password = "";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Keep the message simple for a beginner project.
    die("Database connection failed. Please check that MySQL is running on port 3307 and that the database exists.");
}
