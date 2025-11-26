
// Funkcja do obliczania odległości Levenshteina
function levenshtein(a, b) {
    const matrix = Array(a.length + 1)
        .fill(null)
        .map(() => Array(b.length + 1).fill(null));

    for (let i = 0; i <= a.length; i++) matrix[i][0] = i;
    for (let j = 0; j <= b.length; j++) matrix[0][j] = j;

    for (let i = 1; i <= a.length; i++) {
        for (let j = 1; j <= b.length; j++) {
            const indicator = a[i - 1] === b[j - 1] ? 0 : 1;
            matrix[i][j] = Math.min(
                matrix[i - 1][j] + 1, // usunięcie
                matrix[i][j - 1] + 1, // wstawienie
                matrix[i - 1][j - 1] + indicator // zamiana
            );
        }
    }
    return matrix[a.length][b.length];
}

// Funkcja do tokenizacji tekstu na zdania
function splitIntoSentences(text) {
    return text.split(/(?<=[.!?])\s+/); // Rozdziel po znakach końca zdań
}

// Funkcja do tokenizacji tekstu na słowa
function splitIntoWords(text) {
    return text.split(/\s+/); // Rozdziel po białych znakach
}

// Funkcja porównująca dwa teksty i licząca punktację
function calculateScore(original, userInput, duration) {
    const originalSentences = splitIntoSentences(original);
    const userSentences = splitIntoSentences(userInput);

    let sentenceAcc = 0;
    let wordAcc = 0;
    let totalErrors = 0; // Dodana zmienna na błędy

    // Porównanie na poziomie zdań
    for (let i = 0; i < originalSentences.length; i++) {
        const originalSentence = originalSentences[i] || "";
        const userSentence = userSentences[i] || "";

        const levDist = levenshtein(originalSentence, userSentence);
        totalErrors += levDist; // Dodaj liczbę błędów z Levenshteina
        const maxLen = Math.max(originalSentence.length, userSentence.length);
        sentenceAcc += 1 - levDist / maxLen; // Dodaj procent podobieństwa
    }
    sentenceAcc /= originalSentences.length; // Średnia punktacja za zdania

    // Porównanie na poziomie słów
    const originalWords = splitIntoWords(original);
    const userWords = splitIntoWords(userInput);

    let matches = 0;
    for (let i = 0; i < originalWords.length; i++) {
        if (originalWords[i] === userWords[i]) matches++;
        else totalErrors++; // Dodanie błędu, jeśli słowo się różni
    }
    wordAcc = matches / originalWords.length; // Procent poprawnych słów

    // Oblicz końcowy wynik z wagami
    const textAcc = sentenceAcc * 0.5 + wordAcc * 0.5; // 50/50 wagi

    // Obliczenie bonusu za czas
    const timeBonus = calculateTimeBonus(duration);

    // Obliczenie ostatecznej punktacji
    const finalScore = textAcc * 70 + timeBonus;

    return {
        sentenceAcc: parseFloat((sentenceAcc * 100).toFixed(2)),
        wordAcc: parseFloat((wordAcc * 100).toFixed(2)),
        textAcc: parseFloat((textAcc * 100).toFixed(2)),
        timeBonus: parseInt(timeBonus),
        finalScore: parseInt(finalScore),
        errorCount: totalErrors 
    };
}

function calculateTimeBonus(duration) {
    const maxBonusTime = 30; // Maksymalny bonus
    const thresholdTime = 30; // Czas, do którego bonus jest maksymalny
    const maxTime = 60; // Czas, po którym bonus wynosi 0

    if (duration <= thresholdTime) {
        return maxBonusTime; // Maksymalny bonus do 20 sekund
    } else if (duration <= maxTime) {
        // Liniowy spadek od 20s do 60s
        return Math.max(0, maxBonusTime * (1 - (duration - thresholdTime) / (maxTime - thresholdTime)));
    } else {
        return 0; // Bonus wynosi 0 po 60 sekundach
    }
}


// Funkcja porównująca teksty i pokazująca różnice
function showDifferences(original, userInput) {
    // Jeśli używasz biblioteki z CDN
    const diff = Diff.diffWords(original, userInput);

    // Tworzenie podglądu różnic
    let resultHTML = '';
    diff.forEach(part => {
        const color = part.added ? 'green' :
                      part.removed ? 'red' : 'black';
        const decoration = part.removed ? 'line-through' : 'none';

        // Dodanie różnic do podglądu
        resultHTML += `<span style="color:${color}; text-decoration:${decoration};">${part.value}</span>`;
    });

    return resultHTML; // Zwraca HTML do wyświetlenia
}


