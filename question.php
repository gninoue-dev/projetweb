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

// Ajout d'une nouvelle question
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $contenu = $_POST['contenu'];
    $reponse_correcte = $_POST['reponse_correcte'];

    $sql = "INSERT INTO questions (id_exam, type, contenu, reponse_correcte) 
            VALUES ('$id_exam','$type','$contenu','$reponse_correcte')";
    mysqli_query($conn, $sql);
    echo "✅ Question ajoutée avec succès.";
}

// Récupérer les questions existantes
$sql_questions = "SELECT * FROM questions WHERE id_exam='$id_exam'";
$result_questions = mysqli_query($conn, $sql_questions);
?>

<h2>Gestion des questions pour l'examen <?php echo $id_exam; ?></h2>

<!-- Liste des questions -->
<h3>Questions existantes</h3>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Type</th>
    <th>Contenu</th>
    <th>Réponse correcte</th>
  </tr>
  <?php while($q = mysqli_fetch_assoc($result_questions)) { ?>
    <tr>
      <td><?php echo $q['id_question']; ?></td>
      <td><?php echo $q['type']; ?></td>
      <td><?php echo $q['contenu']; ?></td>
      <td><?php echo $q['reponse_correcte']; ?></td>
    </tr>
  <?php } ?>
</table>

<!-- Formulaire pour ajouter une question -->
<h3>Ajouter une nouvelle question</h3>
<form method="POST">
  Type :
  <select name="type">
    <option value="QCM">QCM</option>
    <option value="Texte">Texte</option>
    <option value="Vrai/Faux">Vrai/Faux</option>
  </select><br>
  Contenu : <textarea name="contenu" required></textarea><br>
  Réponse correcte : <input type="text" name="reponse_correcte"><br>
  <button type="submit">Ajouter</button>
</form>
