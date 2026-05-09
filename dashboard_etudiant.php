<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'etudiant') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$stmt = $pdo->prepare("SELECT e.* FROM examens e WHERE e.statut = 'publié' ORDER BY e.created_at DESC");
$stmt->execute();
$examens_disponibles = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT t.*, e.titre FROM tentatives t JOIN examens e ON t.examen_id = e.id WHERE t.etudiant_id = ? ORDER BY t.date_debut DESC");
$stmt->execute([$_SESSION['user_id']]);
$mes_tentatives = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord Étudiant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="page-header">
            <h2><i class="fa-solid fa-graduation-cap"></i> Mes Examens</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-card stat-green">
                <div class="stat-number"><?= count(array_filter($mes_tentatives, fn($t) => $t['statut'] === 'en_cours')) ?></div>
                <div class="stat-label">En attente</div>
            </div>
            <div class="stat-card stat-red">
                <div class="stat-number"><?= count(array_filter($mes_tentatives, fn($t) => $t['statut'] === 'soumis')) ?></div>
                <div class="stat-label">Complétés</div>
            </div>
        </div>
        <h3 style="margin-top: 2rem; margin-bottom: 1rem;"><i class="fa-solid fa-clipboard-list"></i> Examens disponibles</h3>
        <?php if (empty($examens_disponibles)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
            <h3>Aucun examen disponible</h3>
        </div>
        <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($examens_disponibles as $examen): ?>
            <div class="card">
                <div class="card-header">
                    <h3><?= htmlspecialchars($examen['titre']) ?></h3>
                </div>
                <div class="card-desc"><?= htmlspecialchars(substr($examen['description'], 0, 100)) ?>...</div>
                <div class="card-meta">
                    <span><i class="fa-solid fa-clock"></i> <?= $examen['duree_minutes'] ?> minutes</span>
                </div>
                <a href="examen.php?id=<?= $examen['id'] ?>" class="btn btn-primary btn-full"><i class="fa-solid fa-play"></i> Commencer</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
