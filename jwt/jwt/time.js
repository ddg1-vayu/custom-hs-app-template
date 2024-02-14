let remainingTime = 60;
let timerInterval;

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    const formattedMinutes = String(minutes).padStart(2, '0');
    const formattedSeconds = String(remainingSeconds).padStart(2, '0');
    return `${formattedMinutes}:${formattedSeconds}`;
}

function startStopwatch() {
    timerInterval = setInterval(updateStopwatch, 1000);
}

function updateStopwatch() {
    remainingTime--;
    if (remainingTime < 0) {
        stopStopwatch();
        remainingTime = 0;
    }
    updateDisplay(remainingTime);
}



function updateDisplay(remainingTime) {
    const stopwatchElement = document.querySelector('.stopwatch');
    stopwatchElement.textContent = formatTime(remainingTime);
}