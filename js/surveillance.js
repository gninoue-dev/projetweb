// Simulation de détection anomalies via webcam
// (à remplacer par module IA/OpenCV pour vraie reconnaissance)

function detectAnomalie(type) {
    fetch("surveillance.php?id_exam=1", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "type_anomalie=" + type
    })
    .then(response => response.text())
    .then(data => console.log(data));
}

// Exemple : déclencher anomalies
setInterval(() => {
    // Ici tu pourrais mettre une vraie détection IA
    // Pour test, on simule une absence de visage toutes les 60s
    detectAnomalie("absence_visage");
}, 60000);
