<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'examinateur') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$stmt = $pdo->prepare("
    SELECT a.*, u.prenom, u.nom, e.titre 
    FROM anomalies a 
    JOIN tentatives t ON a.tentative_id = t.id 
    JOIN examens e ON t.examen_id = e.id 
    JOIN utilisateurs u ON t.etudiant_id = u.id 
    WHERE e.examinateur_id = ? 
    ORDER BY a.date_detection DESC
");
$stmt->execute([$_SESSION['user_id']]);
$anomalies = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anomalies détectées</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="page-header">
            <h2><i class="fa-solid fa-triangle-exclamation"></i> Anomalies détectées</h2>
        </div>
        <?php if (empty($anomalies)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-check-circle"></i></div>
            <h3>Aucune anomalie détectée</h3>
        </div>
        <?php else: ?>
        <div style="display: grid; gap: 1rem;">
            <?php foreach ($anomalies as $anomalie): 
                $couleur = $anomalie['severite'] === 'élevé' ? 'niveau-eleve' : ($anomalie['severite'] === 'moyen' ? 'niveau-moyen' : 'niveau-faible');
            ?>
            <div class="anomalie-card <?= $couleur ?>">
                <div class="anomalie-header">
                    <strong><?= htmlspecialchars($anomalie['prenom'] . ' ' . $anomalie['nom']) ?></strong>
                    <span><?= htmlspecialchars($anomalie['titre']) ?></span>
                    <small><?= $anomalie['date_detection'] ?></small>
                </div>
                <p><?= htmlspecialchars($anomalie['description']) ?></p>
                <span class="badge badge-red"><?= $anomalie['severite'] ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
