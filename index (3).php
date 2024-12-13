<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wirtuoz Pisania</title>
    <link rel="stylesheet" href="styles.css"> <!-- Dodany zewnętrzny CSS -->
</head>
<body>
    <div class="container">
        <h1>Test szybkości pisania</h1>
        <p>Wpisz swoje imię, aby rozpocząć:</p>
        <input type="text" id="nameInput" placeholder="Wpisz swoje imię" />
        <button id="startBtn" onclick="startTest()">Start</button>

        <div id="typingSection" class="hidden">
            <p>Teraz przepisz poniższy tekst:</p>
            <p id="textToDisplay"></p>
            <textarea id="inputField" placeholder="Przepisz tekst tutaj..." oninput="checkTyping()" disabled></textarea>
            <p id="timer">Czas: 0 sekund</p>
            <p id="wpm">WPM: 0</p>
            <button id="submitBtn" onclick="submitTest()" disabled>Zatwierdź</button>
            <button id="giveUpBtn" onclick="giveUp()">Poddaj się</button>
            <button id="resetBtn" onclick="resetTest()">Reset</button>
        </div>

        <div id="resultsSection" class="hidden">
            <h3>Wynik</h3>
            <p>Czas: <span id="timeTaken"></span> sekund</p>
            <p>Błędy: <span id="errorCount"></span></p>
            <p>Słowa na minutę: <span id="wpmCount"></span></p>
            <p>Imię: <span id="userName"></span></p>
        </div>

        <!-- Formularz do wysyłania danych do bazy -->
        <form id="resultForm" action="save_results.php" method="POST">
            <input type="hidden" name="userName" id="formUserName">
            <input type="hidden" name="timeTaken" id="formTimeTaken">
            <input type="hidden" name="errorCount" id="formErrorCount">
            <input type="hidden" name="wpmCount" id="formWpmCount">
        </form>
    </div>

    <script>
        let startTime;
        let timerInterval;
        let errorCount = 0;
        let textToType = '';
        let userInput = '';
        let userName = '';

        function checkTyping() {
    userInput = document.getElementById('inputField').value;
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

            document.getElementById('startBtn').disabled = true;
            document.getElementById('nameInput').disabled = true;
            document.getElementById('typingSection').classList.remove('hidden');

            textToType = "To jest przykładowy tekst, który trzeba przepisać.";
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
    const name = document.getElementById('nameInput').value;
    const time = parseFloat(document.getElementById('timer').textContent.split(' ')[1]);
    const errors = errorCount;
    const wpm = parseInt(document.getElementById('wpm').textContent.split(' ')[1]);

    fetch('submit.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `name=${encodeURIComponent(name)}&time=${time}&errors=${errors}&wpm=${wpm}`
    })
    .then(response => response.text())
    .then(data => {
        alert("Wynik został zapisany!");
        console.log(data);
        // Opcjonalnie: przekierowanie po zapisaniu
        window.location.reload();
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
    </script>
</body>
</html>
		