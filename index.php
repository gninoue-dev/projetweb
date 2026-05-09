<?php
require_once 'config.php';
if (isLoggedIn()) {
    $redirect = $_SESSION['user_role'] === 'examinateur' ? 'dashboard_examinateur.php' : 'dashboard_etudiant.php';
    header('Location: ' . BASE_URL . $redirect);
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['mot_de_passe'] ?? '';
    if ($email && $mdp) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_nom']  = $user['prenom'] . ' ' . $user['nom'];
            $_SESSION['user_role'] = $user['role'];
            $redirect = $user['role'] === 'examinateur' ? 'dashboard_examinateur.php' : 'dashboard_etudiant.php';
            header('Location: ' . BASE_URL . $redirect);
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ExamSecure – Connexion</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
<div class="login-container">
    <div class="login-logo">
        <div class="logo-icon"><i class="fa-solid fa-clipboard"></i></div>
        <h1>ExamSecure</h1>
        <p>Plateforme d'examen sécurisée</p>
    </div>
    <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" class="login-form">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" placeholder="votre@email.com" required autofocus>
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary btn-full">Se connecter</button>
    </form>
    <p class="login-hint">Examinateur : exam@test.com / password<br>Étudiant : etudiant@test.com / password</p>
</div>
</body>
</html>