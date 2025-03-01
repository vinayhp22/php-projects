<?php
// config/config.php
$host = 'localhost';
$db   = 'bookhaven';
$user = 'root';
$pass = '';  // update with your MySQL password
$charset = 'utf8mb4';
$port = 3307;  // new port

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
   PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
   PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
   $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
   throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
