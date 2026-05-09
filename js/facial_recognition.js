(function() {
    const video = document.getElementById('videoFeed');
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    let faceDetected = false;
    let captureInterval = null;

    // Initialiser la caméra
    navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } })
        .then(stream => {
            video.srcObject = stream;
            // Commencer la détection faciale après 1 seconde
            setTimeout(startFaceDetection, 1000);
        })
        .catch(err => {
            if (typeof signalerAnomalie === 'function')
                signalerAnomalie('autre', 'Impossible d\'accéder à la caméra: ' + err.message);
        });

    function startFaceDetection() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Capturer le visage toutes les 5 secondes
        captureInterval = setInterval(() => {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = canvas.toDataURL('image/jpeg', 0.8);
                
                // Envoyer l'image au serveur pour vérification
                fetch('examen.php?id=' + EXAMEN_ID, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'facial_capture=1&tentative_id=' + TENTATIVE_ID + '&image=' + encodeURIComponent(imageData)
                });
            }
        }, 5000);
    }

    // Vérifier que le visage reste visible
    setInterval(() => {
        if (!faceDetected && video.readyState === video.HAVE_ENOUGH_DATA) {
            // Vérification simple: si la vidéo n'est pas noire
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
            let brightness = 0;
            for (let i = 0; i < imageData.length; i += 4) {
                brightness += (imageData[i] + imageData[i+1] + imageData[i+2]) / 3;
            }
            brightness = brightness / (imageData.length / 4);
            
            if (brightness < 50) {
                if (typeof signalerAnomalie === 'function')
                    signalerAnomalie('autre', 'Écran trop sombre - Vérifiez votre position');
            }
        }
    }, 3000);

    window.stopFaceDetection = function() {
        if (captureInterval) clearInterval(captureInterval);
    };
})();
