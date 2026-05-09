<?php
require_once 'config.php';
require_once 'includes/auth.php';
if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profil'])) {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $filiere = $_POST['filiere'] ?? '';
        $sexe = $_POST['sexe'] ?? '';
        
        if ($nom && $prenom && $filiere && $sexe) {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom=?, prenom=?, filiere=?, sexe=? WHERE id=?");
            if ($stmt->execute([$nom, $prenom, $filiere, $sexe, $_SESSION['user_id']])) {
                $_SESSION['user_nom'] = "$prenom $nom";
                $message = '<div class="alert alert-success"><i class="fa-solid fa-check"></i> Profil mis à jour!</div>';
                $user = $pdo->query("SELECT * FROM utilisateurs WHERE id = {$_SESSION['user_id']}")->fetch();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="page-header">
            <h2><i class="fa-solid fa-user-circle"></i> Mon Profil</h2>
        </div>
        <?= $message ?>
        <div class="form-card" style="max-width: 600px;">
            <h3>Informations personnelles</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="sexe">Sexe</label>
                        <select id="sexe" name="sexe" required>
                            <option value="">Sélectionnez...</option>
                            <option value="M" <?= $user['sexe'] === 'M' ? 'selected' : '' ?>>Masculin</option>
                            <option value="F" <?= $user['sexe'] === 'F' ? 'selected' : '' ?>>Féminin</option>
                            <option value="Autre" <?= $user['sexe'] === 'Autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filiere">Filière</label>
                        <input type="text" id="filiere" name="filiere" placeholder="Ex: Informatique" value="<?= htmlspecialchars($user['filiere'] ?? '') ?>" required>
                    </div>
                </div>
                <button type="submit" name="update_profil" class="btn btn-primary"><i class="fa-solid fa-save"></i> Enregistrer</button>
            </form>
        </div>
        <div class="form-card" style="max-width: 600px; margin-top: 2rem;">
            <h3><i class="fa-solid fa-face-smile"></i> Reconnaissance faciale</h3>
            <p style="color: #666; margin-bottom: 1rem;">Enregistrez votre visage pour une meilleure sécurité</p>
            <video id="faceVideo" style="width: 100%; max-width: 300px; border-radius: 8px; margin-bottom: 1rem; display: none;"></video>
            <canvas id="faceCanvas" style="display: none;"></canvas>
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-primary" id="startFaceBtn"><i class="fa-solid fa-camera"></i> Démarrer caméra</button>
                <button type="button" class="btn btn-success" id="captureFaceBtn" style="display: none;"><i class="fa-solid fa-check"></i> Capturer visage</button>
                <button type="button" class="btn btn-secondary" id="stopFaceBtn" style="display: none;"><i class="fa-solid fa-stop"></i> Arrêter</button>
            </div>
            <div id="faceStatus" style="margin-top: 1rem; color: #27500A;"></div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    
    <script>
    let faceStream = null;
    const faceVideo = document.getElementById('faceVideo');
    const faceCanvas = document.getElementById('faceCanvas');
    const startBtn = document.getElementById('startFaceBtn');
    const captureBtn = document.getElementById('captureFaceBtn');
    const stopBtn = document.getElementById('stopFaceBtn');
    const status = document.getElementById('faceStatus');
    
    startBtn.addEventListener('click', async () => {
        try {
            faceStream = await navigator.mediaDevices.getUserMedia({ video: true });
            faceVideo.srcObject = faceStream;
            faceVideo.style.display = 'block';
            startBtn.style.display = 'none';
            captureBtn.style.display = 'inline-flex';
            stopBtn.style.display = 'inline-flex';
            status.textContent = '<i class="fa-solid fa-circle" style="color: green;"></i> Caméra active';
        } catch (err) {
            status.textContent = '<i class="fa-solid fa-exclamation-circle"></i> Erreur: ' + err.message;
        }
    });
    
    captureBtn.addEventListener('click', () => {
        faceCanvas.width = faceVideo.videoWidth;
        faceCanvas.height = faceVideo.videoHeight;
        faceCanvas.getContext('2d').drawImage(faceVideo, 0, 0);
        const imageData = faceCanvas.toDataURL('image/jpeg');
        
        fetch('process_facial.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'image=' + encodeURIComponent(imageData)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                status.innerHTML = '<i class="fa-solid fa-check-circle" style="color: green;"></i> Visage enregistré avec succès!';
            } else {
                status.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i> Erreur: ' + data.error;
            }
        });
    });
    
    stopBtn.addEventListener('click', () => {
        faceStream.getTracks().forEach(track => track.stop());
        faceVideo.style.display = 'none';
        startBtn.style.display = 'inline-flex';
        captureBtn.style.display = 'none';
        stopBtn.style.display = 'none';
        status.textContent = '';
    });
    </script>
</body>
</html>
