<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'examinateur') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM examens WHERE examinateur_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$nb_examens = $stmt->fetch()['total'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) as total FROM anomalies a 
    JOIN tentatives t ON a.tentative_id = t.id 
    JOIN examens e ON t.examen_id = e.id 
    WHERE e.examinateur_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$nb_anomalies = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT * FROM examens WHERE examinateur_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$examens = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord Examinateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="page-header">
            <h2><i class="fa-solid fa-chalkboard"></i> Gestion des Examens</h2>
            <a href="creer_examen.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Créer examen</a>
        </div>
        <div class="stats-grid">
            <div class="stat-card stat-green">
                <div class="stat-number"><?= $nb_examens ?></div>
                <div class="stat-label">Examens créés</div>
            </div>
            <div class="stat-card stat-red">
                <div class="stat-number"><?= $nb_anomalies ?></div>
                <div class="stat-label">Anomalies détectées</div>
            </div>
        </div>
        <div class="page-header">
            <h3><i class="fa-solid fa-list"></i> Mes examens</h3>
            <a href="rapports.php" class="btn btn-secondary btn-sm"><i class="fa-solid fa-chart-bar"></i> Rapports</a>
        </div>
        <?php if (empty($examens)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-file-circle-question"></i></div>
            <h3>Aucun examen créé</h3>
            <p>Commencez par créer un nouvel examen</p>
        </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($examens as $examen): ?>
                <tr>
                    <td><?= htmlspecialchars($examen['titre']) ?></td>
                    <td><?= $examen['duree_minutes'] ?> min</td>
                    <td><span class="badge badge-blue"><?= $examen['statut'] ?></span></td>
                    <td>
                        <a href="examen.php?id=<?= $examen['id'] ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-play"></i></a>
                        <a href="surveillance.php?id=<?= $examen['id'] ?>" class="btn btn-sm btn-secondary"><i class="fa-solid fa-binoculars"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
