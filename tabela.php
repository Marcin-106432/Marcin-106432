<?php
require 'db.php';

$result = $conn->query("SELECT user_name, team, time_taken, errors, wpm, acc FROM results GRUOP BY team");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wyniki</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Tabela wyników</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Imię</th>
                <th>Drużyna</th>
                <th>Czas (sekundy)</th>
                <th>Błędy</th>
                <th>WPM</th>
                <th>Dokładność (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['team']) ?></td>
                    <td><?= htmlspecialchars($row['time']) ?></td>
                    <td><?= htmlspecialchars($row['errors']) ?></td>
                    <td><?= htmlspecialchars($row['wpm']) ?></td>
                    <td><?= htmlspecialchars($row['accuracy']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php"><button>Powrót do testu</button></a>
</body>
</html>

<?php
$conn->close();
?>
