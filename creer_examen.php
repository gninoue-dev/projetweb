<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn() || $_SESSION['user_role'] !== 'examinateur') {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $description = $_POST['description'] ?? '';
    $duree = $_POST['duree_minutes'] ?? 60;
    if ($titre && $description) {
        $stmt = $pdo->prepare("INSERT INTO examens (titre, description, examinateur_id, duree_minutes, statut) VALUES (?, ?, ?, ?, 'brouillon')");
        if ($stmt->execute([$titre, $description, $_SESSION['user_id'], $duree])) {
            $exam_id = $pdo->lastInsertId();
            header('Location: ' . BASE_URL . 'dashboard_examinateur.php?success=1');
            exit;
        }
    } else {
        $message = 'Remplissez tous les champs';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer un examen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="page-header">
            <h2><i class="fa-solid fa-pen-to-square"></i> Créer un nouvel examen</h2>
        </div>
        <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <div class="form-card" style="max-width: 600px;">
            <form method="POST">
                <div class="form-group">
                    <label for="titre">Titre de l'examen</label>
                    <input type="text" id="titre" name="titre" placeholder="Ex: Mathématiques - Test 1" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Décrivez l'examen..." required></textarea>
                </div>
                <div class="form-group">
                    <label for="duree_minutes">Durée (minutes)</label>
                    <input type="number" id="duree_minutes" name="duree_minutes" value="60" min="15" max="480">
                </div>
                <div class="form-row">
                    <a href="dashboard_examinateur.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Annuler</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Créer</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
