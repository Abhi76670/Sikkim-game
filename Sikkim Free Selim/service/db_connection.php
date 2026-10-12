<?php
$host = 'localhost';
$dbname = 'shubhamc_selim';
$username = 'shubhamc_selim';
$password = 'shubhamc_selim';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
