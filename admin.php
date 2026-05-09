<?php
session_start();
include("includes/db.php");
include("includes/header.php");
include("includes/footer.php");
include("includes/functions.php");


// Vérifier que l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Suppression d'un utilisateur
if (isset($_GET['delete'])) {
    $id_user = $_GET['delete'];
    $sql = "DELETE FROM utilisateurs WHERE id_user='$id_user'";
    mysqli_query($conn, $sql);
    echo "❌ Utilisateur supprimé.";
}

// Ajout d'un utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "INSERT INTO utilisateurs (matricule, nom, prenom, email, mot_de_passe, role) 
            VALUES ('$matricule','$nom','$prenom','$email','$mot_de_passe','$role')";
    mysqli_query($conn, $sql);
    echo "✅ Utilisateur ajouté.";
}

// Récupérer tous les utilisateurs
$sql_users = "SELECT * FROM utilisateurs";
$result_users = mysqli_query($conn, $sql_users);
?>

<h2>Gestion des utilisateurs</h2>

<!-- Liste des utilisateurs -->
<table border="1">
  <tr>
    <th>ID</th>
    <th>Matricule</th>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Email</th>
    <th>Rôle</th>
    <th>Score</th>
    <th>Statut</th>
    <th>Actions</th>
  </tr>
  <?php while($u = mysqli_fetch_assoc($result_users)) { ?>
    <tr>
      <td><?php echo $u['id_user']; ?></td>
      <td><?php echo $u['matricule']; ?></td>
      <td><?php echo $u['nom']; ?></td>
      <td><?php echo $u['prenom']; ?></td>
      <td><?php echo $u['email']; ?></td>
      <td><?php echo $u['role']; ?></td>
      <td><?php echo $u['score_global']; ?>%</td>
      <td><?php echo $u['statut']; ?></td>
      <td><a href="admin.php?delete=<?php echo $u['id_user']; ?>">Supprimer</a></td>
    </tr>
  <?php } ?>
</table>

<!-- Formulaire pour ajouter un utilisateur -->
<h3>Ajouter un nouvel utilisateur</h3>
<form method="POST">
  Matricule: <input type="text" name="matricule" required><br>
  Nom: <input type="text" name="nom" required><br>
  Prénom: <input type="text" name="prenom" required><br>
  Email: <input type="email" name="email" required><br>
  Mot de passe: <input type="password" name="mot_de_passe" required><br>
  Rôle:
  <select name="role">
    <option value="etudiant">Étudiant</option>
    <option value="examinateur">Examinateur</option>
    <option value="admin">Admin</option>
  </select><br>
  <button type="submit">Ajouter</button>
</form>
