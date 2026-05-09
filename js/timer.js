// timer.js : compte à rebours côté client

function startTimer(duration, display) {
    let timer = duration, minutes, seconds;
    let countdown = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            clearInterval(countdown);
            alert("⏳ Temps écoulé ! Vos réponses vont être soumises.");
            window.location.href = "confirmation.php"; // redirection auto
        }
    }, 1000);
}

// Initialisation automatique
window.onload = function () {
    let examDuration = 30 * 60; // 30 minutes en secondes
    let display = document.querySelector('#time');
    startTimer(examDuration, display);
};
