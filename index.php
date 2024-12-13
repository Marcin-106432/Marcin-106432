<?php
header('Content-Type: text/html; charset=utf-8');

// Database connection
require 'db.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wirtuoz Pisania</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<button onclick="window.location.href='tabela.php'">Zobacz tabelę wyników</button>

    <div class="container">
        <h1>Test szybkości pisania</h1>
        <p>Wpisz swoje imię, aby rozpocząć:</p>
        <input type="text" id="nameInput" placeholder="Wpisz swoje imię" />
        <p>Wybierz swoją drużynę:</p>
        
<select id="teamSelect">
<option value="Drużyna 1">Astronomia i Fizyka</option>
<option value="Drużyna 2">Biologia</option>
<option value="Drużyna 3">Biotechnologia</option>
<option value="Drużyna 4">Informatyka i ekonometria</option>
<option value="Drużyna 5">Inżynieria danych</option>
<option value="Drużyna 6">Matematyka</option>
<option value="Drużyna 7">Sport i Wychowanie fizyczne</option>
<option value="Drużyna 8">Turystyka i rekreacja</option>
<option value="Drużyna 9">Żywienie człowieka i dietoterapia</option>
</select>
</input>
        <button id="startBtn" onclick="startTest()">Start</button>

        <div id="typingSection" class="hidden">
            <p>Teraz przepisz poniższy tekst:</p>
            <p id="textToDisplay"></p>
            <textarea id="inputField" placeholder="Przepisz tekst tutaj..." oninput="checkTyping()" disabled></textarea>
            <div class="stats-container">
                <p id="timer">Czas: 0.00 sekund</p>
                <p id="wpm">WPM: 0</p>
                <p id="accuracy">Dokładność: 100%</p>
            </div>
            <div class="button-group">
                <button id="submitBtn" onclick="submitTest()" disabled>Zatwierdź</button>
                <button id="giveUpBtn" onclick="giveUp()">Poddaj się</button>
                <button id="resetBtn" onclick="resetTest()">Reset</button>
            </div>
        </div>

        <div id="resultsSection" class="hidden">
            <h3>Wynik</h3>
            <p>Czas: <span id="timeTaken"></span> sekund</p>
            <p>Błędy: <span id="errorCount"></span></p>
            <p>Słowa na minutę: <span id="wpmCount"></span></p>
            <p>Dokładność: <span id="accuracyResult"></span>%</p>
            <p>Imię: <span id="userName"></span></p>
        </div>

        <form id="resultForm" action="save_results.php" method="POST">
            <input type="hidden" name="userName" id="formUserName">
            <input type="hidden" name="timeTaken" id="formTimeTaken">
            <input type="hidden" name="errorCount" id="formErrorCount">
            <input type="hidden" name="wpmCount" id="formWpmCount">
            <input type="hidden" name="accuracyCount" id="formAccuracyCount">
            <input type="hidden" name="teamName" id="formTeamName">
        </form>
    </div>
    <script>
        let startTime;
        let timerInterval;
        let errorCount = 0;
        let totalCharacters = 0;
        let correctCharacters = 0;
        let textToType = '';
        let userInput = '';
        let userName = '';

        function calculateWPM() {
            const currentTime = Date.now();
            const timeElapsed = (currentTime - startTime) / 60000; // czas w minutach
            const words = userInput.trim().split(/\s+/).length;
            const wpm = Math.round(words / timeElapsed);
            return wpm || 0;
        }

        function checkTyping() {
            userInput = document.getElementById('inputField').value;
            
            // Obliczanie dokładności
            totalCharacters = textToType.length;
            correctCharacters = 0;
            for (let i = 0; i < userInput.length; i++) {
                if (userInput[i] === textToType[i]) {
                    correctCharacters++;
                }
            }
            
            const accuracy = totalCharacters > 0 
                ? Math.round((correctCharacters / totalCharacters) * 100) 
                : 100;
            
            document.getElementById('accuracy').textContent = `Dokładność: ${accuracy}%`;
            document.getElementById('wpm').textContent = `WPM: ${calculateWPM()}`;

            // Warunek zatwierdzenia
            if (userInput.trim() === textToType.trim()) {
                document.getElementById('submitBtn').disabled = false;
            } else {
                document.getElementById('submitBtn').disabled = true;
            }
        }

        function startTest() {
            userName = document.getElementById('nameInput').value.trim();
            if (userName === "") {
                alert("Proszę podać imię.");
                return;
            }

            // Losowy tekst do przepisania
            const texts = [
                "To jest przykładowy tekst, który trzeba przepisać.",
                "Szybkie pisanie wymaga praktyki i koncentracji.",
                "Każdy może poprawić swoje umiejętności pisania.",
                "Trening czyni mistrza w każdej dziedzinie."
            ];
            textToType = texts[Math.floor(Math.random() * texts.length)];

            document.getElementById('startBtn').disabled = true;
            document.getElementById('nameInput').disabled = true;
            document.getElementById('typingSection').classList.remove('hidden');

            document.getElementById('textToDisplay').textContent = textToType;
            document.getElementById('inputField').disabled = false;
            document.getElementById('inputField').focus();

            startTime = Date.now();
            timerInterval = setInterval(updateTimer, 100);
        }

        function updateTimer() {
            const currentTime = Date.now();
            const timeElapsed = ((currentTime - startTime) / 1000).toFixed(2);
            document.getElementById('timer').textContent = `Czas: ${timeElapsed} sekund`;
        }

        function submitTest() {
            clearInterval(timerInterval);
            
            const name = document.getElementById('nameInput').value;
            const time = parseFloat(document.getElementById('timer').textContent.split(' ')[1]);
            const wpm = calculateWPM();
            const accuracy = Math.round((correctCharacters / totalCharacters) * 100);
            
            // Wyłączenie przycisku po zatwierdzeniu
            document.getElementById('submitBtn').disabled = true;

            // Wypełnienie ukrytych pól formularza
            document.getElementById('timeTaken').textContent = time;
            document.getElementById('errorCount').textContent = totalCharacters - correctCharacters;
            document.getElementById('wpmCount').textContent = wpm;
            document.getElementById('accuracyResult').textContent = accuracy;
            document.getElementById('userName').textContent = name;
            document.getElementById('formTeamName').value = document.getElementById('teamSelect').value;


            document.getElementById('resultsSection').classList.remove('hidden');
            document.getElementById('typingSection').classList.add('hidden');

            fetch('submit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
         body: `name=${encodeURIComponent(name)}&time=${time}&errors=${totalCharacters - correctCharacters}&wpm=${wpm}&accuracy=${accuracy}&team=${encodeURIComponent(document.getElementById('teamSelect').value)}`
        })
        .then(response => response.text())
        .then(data => {
            console.log("Wynik został zapisany:", data);
        })
        .catch(error => {
            console.error('Błąd:', error);
        });
}

        function giveUp() {
            clearInterval(timerInterval);
            alert("Próba zakończona. Możesz spróbować ponownie.");
            resetTest();
        }

        function resetTest() {
            location.reload();
        }
        document.getElementById('textToDisplay').addEventListener('contextmenu', function (e) {
    e.preventDefault(); // Blokuje menu kontekstowe (prawy przycisk myszy)
});

document.addEventListener('keydown', function (e) {
    if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'C' || e.key === 'v' || e.key === 'V')) {
        e.preventDefault(); // Blokuje skróty Ctrl + C i Ctrl + V
    }
});


    </script>
</body>
</html>
			
