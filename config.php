
<?php

$host = 'localhost';
// $db   = 'u676076063_maxxifydb';        
// $user = 'u676076063_maxxifyuser';        
// $pass = 'Maxxify3765@.';
$db   = 'mcqmanagement';        
$user = 'root';        
$pass = '';           
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
}
?>