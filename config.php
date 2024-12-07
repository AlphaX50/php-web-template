<?php
// Set database connection setting
$host = 'localhost'; // MySQL server address (usually 'localhost')
$dbname = 'template_PHP_web'; // Database name
$username = 'root'; // MySQL username (default is 'root' on XAMPP)
$password = ''; // MySQL password (default empty on XAMPP)
$charset = 'utf8mb4'; // Character set to use

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>
