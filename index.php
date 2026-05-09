<?php
session_start();
include("includes/db.php");
include("includes/header.php");
include("includes/footer.php");
include("includes/functions.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifiant = $_POST['identifiant']; // peut être email ou matricule
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier si l'identifiant correspond à un email ou un matricule
    $sql = "SELECT * FROM utilisateurs WHERE email='$identifiant' OR matricule='$identifiant'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        // Création de la session
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];

        // Redirection selon rôle
        if ($user['role'] == 'etudiant') {
            header("Location: examen.php");
        } elseif ($user['role'] == 'examinateur') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php"); // admin aussi
        }
    } else {
        echo "❌ Identifiants incorrects.";
    }
}
?>

<!-- Formulaire HTML -->
<form method="POST">
  Email ou Matricule: <input type="text" name="identifiant" required><br>
  Mot de passe: <input type="password" name="mot_de_passe" required><br>
  <button type="submit">Connexion</button>
</form>
