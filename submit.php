<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $team = $_POST['team'];
    $time = $_POST['time'];
    $errors = $_POST['errors'];
    $wpm = $_POST['wpm'];
    $accuracy = $_POST['accuracy'];

    $stmt = $conn->prepare("INSERT INTO results (user_name, team, time_taken, errors, acc, wpm) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdddi", $name, $team, $time, $errors, $accuracy, $wpm);

    if ($stmt->execute()) {
        echo "Wynik został zapisany!";
    } else {
        echo "Błąd podczas zapisywania wyniku: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
