<?php
session_start();
include("includes/db.php");
include("includes/header.php");
include("includes/footer.php");
include("includes/functions.php");



// Vérifier que l'utilisateur est examinateur ou admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'examinateur' && $_SESSION['role'] != 'admin')) {
    header("Location: index.php");
    exit();
}

$id_exam = $_GET['id_exam']; // examen sélectionné

// Récupérer les réponses des étudiants
$sql_reponses = "SELECT r.*, u.nom, u.prenom, u.matricule 
                 FROM reponses r 
                 JOIN utilisateurs u ON r.id_user = u.id_user
                 WHERE r.id_question IN (SELECT id_question FROM questions WHERE id_exam='$id_exam')";
$result_reponses = mysqli_query($conn, $sql_reponses);

// Récupérer les anomalies
$sql_anomalies = "SELECT a.*, u.nom, u.prenom, u.matricule 
                  FROM anomalies a 
                  JOIN utilisateurs u ON a.id_user = u.id_user
                  WHERE a.id_exam='$id_exam'";
$result_anomalies = mysqli_query($conn, $sql_anomalies);
?>

<h2>Rapport de l'examen</h2>

<!-- Réponses des étudiants -->
<h3>Réponses des étudiants</h3>
<table border="1">
  <tr>
    <th>Matricule</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>ID Question</th>
    <th>Réponse donnée</th>
    <th>Note</th>
  </tr>
  <?php while($rep = mysqli_fetch_assoc($result_reponses)) { ?>
    <tr>
      <td><?php echo $rep['matricule']; ?></td>
      <td><?php echo $rep['nom']; ?></td>
      <td><?php echo $rep['prenom']; ?></td>
      <td><?php echo $rep['id_question']; ?></td>
      <td><?php echo $rep['reponse_donnee']; ?></td>
      <td><?php echo $rep['note']; ?></td>
    </tr>
  <?php } ?>
</table>

<!-- Anomalies détectées -->
<h3>Anomalies détectées</h3>
<table border="1">
  <tr>
    <th>Matricule</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Type anomalie</th>
    <th>Date/Heure</th>
    <th>Validé par examinateur</th>
  </tr>
  <?php while($ano = mysqli_fetch_assoc($result_anomalies)) { ?>
    <tr>
      <td><?php echo $ano['matricule']; ?></td>
      <td><?php echo $ano['nom']; ?></td>
      <td><?php echo $ano['prenom']; ?></td>
      <td><?php echo $ano['type_anomalie']; ?></td>
      <td><?php echo $ano['horodatage']; ?></td>
      <td><?php echo $ano['valide_par_examinateur'] ? "✅ Oui" : "❌ Non"; ?></td>
    </tr>
  <?php } ?>
</table>
