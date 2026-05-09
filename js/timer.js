(function() {
    const timerEl = document.getElementById('timer');
    if (!timerEl) return;
    function updateTimer() {
        const elapsed = Math.floor((Date.now() - START_TIME) / 1000);
        const remaining = DUREE_SEC - elapsed;
        if (remaining <= 0) {
            timerEl.textContent = '00:00';
            timerEl.style.color = '#E24B4A';
            document.getElementById('examForm').submit();
            return;
        }
        const m = Math.floor(remaining / 60).toString().padStart(2, '0');
        const s = (remaining % 60).toString().padStart(2, '0');
        timerEl.textContent = m + ':' + s;
        if (remaining < 300) timerEl.style.color = '#EF9F27';
        if (remaining < 60)  timerEl.style.color = '#E24B4A';
    }
    updateTimer();
    setInterval(updateTimer, 1000);
})();
