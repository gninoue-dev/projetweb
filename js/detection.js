(function() {
    let lastAlert = 0;
    const COOLDOWN = 10000;
    function signalerAnomalie(type, description) {
        const now = Date.now();
        if (now - lastAlert < COOLDOWN) return;
        lastAlert = now;
        const banner = document.getElementById('alertBanner');
        if (banner) {
            banner.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Anomalie détectée : ' + description;
            banner.style.display = 'block';
            setTimeout(() => banner.style.display = 'none', 5000);
        }
        fetch('examen.php?id=' + EXAMEN_ID, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'anomalie=1&type_anomalie=' + encodeURIComponent(type) + '&description=' + encodeURIComponent(description)
        });
    }
    window.signalerAnomalie = signalerAnomalie;
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) signalerAnomalie('changement_fenetre', "L'étudiant a quitté la fenêtre d'examen");
    });
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        signalerAnomalie('autre', 'Tentative de clic droit détectée');
    });
    document.addEventListener('copy',  function(e) { e.preventDefault(); });
    document.addEventListener('paste', function(e) { e.preventDefault(); });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' || (e.ctrlKey && ['u','U','s','S'].includes(e.key))) {
            e.preventDefault();
            signalerAnomalie('autre', 'Tentative accès outils développeur');
        }
    });
    function demanderPleinEcran() {
        if (!document.fullscreenElement)
            document.documentElement.requestFullscreen().catch(() => {});
    }
    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            signalerAnomalie('changement_fenetre', 'Sortie du mode plein écran');
            setTimeout(demanderPleinEcran, 2000);
        }
    });
    setTimeout(demanderPleinEcran, 1000);
})();

function saveReponse(questionId, texte, optionId) {
    const body = 'ajax_save=1&question_id=' + questionId +
        (texte !== null ? '&reponse=' + encodeURIComponent(texte) : '') +
        (optionId !== null ? '&option_id=' + optionId : '');
    fetch('examen.php?id=' + EXAMEN_ID, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body
    });
}