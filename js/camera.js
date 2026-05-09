(function() {
    const video = document.getElementById('videoFeed');
    const statusEl = document.getElementById('cam-status');
    if (!video) return;
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(stream => {
            video.srcObject = stream;
            statusEl.innerHTML = '<i class="fa-solid fa-camera"></i> Caméra active';
            statusEl.style.color = '#27500A';
        })
        .catch(err => {
            statusEl.innerHTML = '<i class="fa-solid fa-camera"></i> Caméra refusée';
            statusEl.style.color = '#E24B4A';
            if (typeof signalerAnomalie === 'function')
                signalerAnomalie('autre', 'Accès caméra refusé: ' + err.message);
        });
})();