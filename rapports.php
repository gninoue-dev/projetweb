<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'examinateur') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$stmt = $pdo->prepare("SELECT e.*, COUNT(t.id) as nb_tentatives, AVG(t.score) as moyenne FROM examens e LEFT JOIN tentatives t ON e.id = t.examen_id WHERE e.examinateur_id = ? GROUP BY e.id");
$stmt->execute([$_SESSION['user_id']]);
$examens = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rapports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="page-header">
            <h2><i class="fa-solid fa-chart-bar"></i> Rapports des examens</h2>
        </div>
        <?php if (empty($examens)): ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-solid fa-file-lines"></i></div>
            <h3>Aucun rapport disponible</h3>
        </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Tentatives</th>
                    <th>Moyenne</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($examens as $examen): ?>
                <tr>
                    <td><?= htmlspecialchars($examen['titre']) ?></td>
                    <td><?= $examen['nb_tentatives'] ?? 0 ?></td>
                    <td><?= round($examen['moyenne'] ?? 0, 2) ?>/20</td>
                    <td><span class="badge badge-blue"><?= $examen['statut'] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
