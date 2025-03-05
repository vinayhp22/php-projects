<?php
// config/db.php

$host     = 'localhost';
$db       = 'quizify';
$user     = 'root';
$password = '';  // your DB password
$port = 3307;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    // Enable PDO exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
