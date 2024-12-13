<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $time = $_POST['time'];
    $errors = $_POST['errors'];
    $wpm = $_POST['wpm'];

    $stmt = $conn->prepare("INSERT INTO results (name, time, errors, wpm) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdii", $name, $time, $errors, $wpm);

    if ($stmt->execute()) {
        echo "Wynik został zapisany!";
    } else {
        echo "Błąd: " . $stmt->error;
    }
}
?>
