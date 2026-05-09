<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$exam_id = $_GET['id'] ?? null;
if (!$exam_id) {
    header('Location: ' . BASE_URL . 'dashboard_etudiant.php');
    exit;
}
// Vérifier que l'examen existe
$stmt = $pdo->prepare("SELECT * FROM examens WHERE id = ? AND statut = 'publié'");
$stmt->execute([$exam_id]);
if (!$stmt->fetch()) {
    die('Examen non trouvé');
}
// Créer une tentative
$stmt = $pdo->prepare("INSERT INTO tentatives (examen_id, etudiant_id, statut) VALUES (?, ?, 'en_cours')");
$stmt->execute([$exam_id, $_SESSION['user_id']]);
$tentative_id = $pdo->lastInsertId();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Examen en cours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="exam-page">
    <div class="exam-topbar">
        <div class="exam-title"><i class="fa-solid fa-clipboard-check"></i> Examen</div>
        <div class="exam-timer-wrap">
            <span id="timer">00:00</span>
        </div>
        <div class="cam-indicator" id="cam-status"><i class="fa-solid fa-camera"></i> Vérification...</div>
    </div>
    <div id="alertBanner" class="alert-banner" style="display:none;"></div>
    <div class="exam-body">
        <form id="examForm" method="POST">
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-hourglass"></i></div>
                <h3>Examen en cours...</h3>
            </div>
        </form>
    </div>
    <div class="cam-container">
        <video id="videoFeed" autoplay playsinline muted></video>
    </div>
    <script>
        const START_TIME = Date.now();
        const DUREE_SEC = 3600;
        const EXAMEN_ID = <?= json_encode($exam_id) ?>;
        const TENTATIVE_ID = <?= json_encode($tentative_id) ?>;
    </script>
    <script src="js/facial_recognition.js"></script>
    <script src="js/camera.js"></script>
    <script src="js/detection.js"></script>
    <script src="js/timer.js"></script>
</body>
</html>
