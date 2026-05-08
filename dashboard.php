<?php
session_start();
include("includes/db.php");

// Vérifier que l'utilisateur est connecté et qu'il est examinateur ou admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'examinateur' && $_SESSION['role'] != 'admin')) {
    header("Location: index.php");
    exit();
}

// Récupérer les examens créés par l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM examens WHERE createur_id='$user_id'";
$result = mysqli_query($conn, $sql);
?>

<h2>Bienvenue <?php echo $_SESSION['prenom']." ".$_SESSION['nom']; ?> 👋</h2>
<h3>Tableau de bord examinateur</h3>

<!-- Liste des examens -->
<h4>Mes examens</h4>
<table border="1">
  <tr>
    <th>Titre</th>
    <th>Date</th>
    <th>Durée</th>
    <th>Actions</th>
  </tr>
  <?php while($exam = mysqli_fetch_assoc($result)) { ?>
    <tr>
      <td><?php echo $exam['titre']; ?></td>
      <td><?php echo $exam['date_exam']; ?></td>
      <td><?php echo $exam['duree']; ?> min</td>
      <td>
        <a href="questions.php?id_exam=<?php echo $exam['id_exam']; ?>">📄 Questions</a> |
        <a href="rapport.php?id_exam=<?php echo $exam['id_exam']; ?>">📊 Rapport</a>
      </td>
    </tr>
  <?php } ?>
</table>

<!-- Formulaire pour créer un nouvel examen -->
<h4>Créer un nouvel examen</h4>
<form method="POST" action="creer_exam.php">
  Titre: <input type="text" name="titre" required><br>
  Description: <textarea name="description"></textarea><br>
  Date: <input type="datetime-local" name="date_exam" required><br>
  Durée (minutes): <input type="number" name="duree" required><br>
  <button type="submit">Créer</button>
</form>
