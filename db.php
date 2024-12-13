<?php
// Database Configuration
$host = 'localhost';
$dbname = 'neothe';
$user = 'Neothe';
$password = 'F6AgCUUyCVFQsi@';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// PDO Connection
try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password, $options);
} catch (PDOException $e) {
    error_log("PDO Connection Error Details:");
    error_log("Error Code: " . $e->getCode());
    error_log("Error Message: " . $e->getMessage());
    die("PDO Database Connection Error: " . $e->getMessage());
}

// Mysqli Connection (alternative)
$conn = new mysqli($host, $user, $password, $dbname);

// Check mysqli connection
if ($conn->connect_error) {
    error_log("Mysqli Connection Error Details:");
    error_log("Error Code: " . $conn->connect_errno);
    error_log("Error Message: " . $conn->connect_error);
    die("Mysqli Database Connection Error: " . $conn->connect_error);
}

// Optional: Set charset for mysqli connection
$conn->set_charset("utf8mb4");
?>
