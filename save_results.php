<?php
$host = 'localhost';
$db = 'neothe';
$user = 'Neothe';
$pass = 'F6AgCUUyCVFQsi@';
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
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Odbieranie danych z formularza
$userName = $_POST['userName'];
$timeTaken = $_POST['timeTaken'];
$errorCount = $_POST['errorCount'];
$wpmCount = $_POST['wpmCount'];

// Zapis do bazy danych
$stmt = $pdo->prepare("INSERT INTO `results`  (user_name, time_taken, errors, wpm) VALUES (?, ?, ?, ?)");
$stmt->execute([$userName, $timeTaken, $errorCount, $wpmCount]);

echo "Wynik został zapisany!";
?>
