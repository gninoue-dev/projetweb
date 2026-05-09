<?php if (!isLoggedIn()) return;
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<header class="main-header">
    <div class="header-inner">
        <a href="<?= $_SESSION['user_role'] === 'examinateur' ? 'dashboard_examinateur.php' : 'dashboard_etudiant.php' ?>" class="logo">
            <i class="fa-solid fa-clipboard"></i> ExamSecure
        </a>
        <nav class="header-nav">
            <a href="profil.php" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-user-circle"></i> 
                <?= htmlspecialchars($_SESSION['user_nom']) ?> 
                (<?= htmlspecialchars($user['filiere'] ?? 'N/A') ?>)
            </a>
            <span class="user-role" title="<?= $user['sexe'] === 'M' ? 'Masculin' : ($user['sexe'] === 'F' ? 'Féminin' : 'Autre') ?>">
                <?= $user['sexe'] === 'M' ? '<i class="fa-solid fa-mars"></i>' : ($user['sexe'] === 'F' ? '<i class="fa-solid fa-venus"></i>' : '<i class="fa-solid fa-person"></i>') ?>
            </span>
            <a href="logout.php" class="btn btn-sm btn-outline">Déconnexion</a>
        </nav>
    </div>
</header>
