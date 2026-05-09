<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<h2>✅ Confirmation</h2>
<p>Merci <?php echo $_SESSION['prenom']." ".$_SESSION['nom']; ?>, vos réponses ont bien été enregistrées.</p>
<p>Vous pouvez maintenant attendre la correction de l’examen par l’examinateur.</p>

<a href="index.php">Retour à l'accueil</a>
