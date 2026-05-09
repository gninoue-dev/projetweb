<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'examinateur') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surveillance Examen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="page-header">
            <h2><i class="fa-solid fa-binoculars"></i> Surveillance Examen</h2>
        </div>
        <div class="two-cols">
            <div>
                <div class="section-title">Candidats en ligne</div>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa-solid fa-users"></i></div>
                    <h3>Aucun candidat</h3>
                </div>
            </div>
            <div>
                <div class="section-title">Anomalies détectées</div>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa-solid fa-check-circle"></i></div>
                    <h3>Aucune anomalie</h3>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
