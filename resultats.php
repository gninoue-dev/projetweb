<?php
session_start();
include("includes/db.php");
include("includes/header.php");
include("includes/footer.php");
include("includes/functions.php");

// Vérifier que l'utilisateur est étudiant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'etudiant') {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['user_id'];

// Récupérer score et statut de l'étudiant
$sql = "SELECT score_global, statut FROM utilisateurs WHERE id_user='$id_user'";
$result = mysqli_query($conn, $sql);
$etu = mysqli_fetch_assoc($result);
?>

<h2>Résultats de votre examen</h2>
<p>Bonjour <?php echo $_SESSION['prenom']." ".$_SESSION['nom']; ?> (Matricule : <?php echo $_SESSION['user_id']; ?>)</p>

<?php if ($etu) { ?>
    <p>Votre score global : <b><?php echo $etu['score_global']; ?>%</b></p>
    <p>Statut : <b><?php echo ucfirst($etu['statut']); ?></b></p>
<?php } else { ?>
    <p>⚠️ Aucun résultat disponible pour le moment.</p>
<?php } ?>

<a href="index.php">Retour à l'accueil</a>
