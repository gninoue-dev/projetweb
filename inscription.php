<?php
include("includes/db.php");
include("includes/header.php");
include("includes/footer.php");
include("includes/functions.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricule = $_POST['matricule'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $filiere = $_POST['filiere'];
    $age = $_POST['age'];
    $sexe = $_POST['sexe'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // Vérifier si l'email ou le matricule existe déjà
    $check = mysqli_query($conn, "SELECT * FROM utilisateurs WHERE email='$email' OR matricule='$matricule'");
    if (mysqli_num_rows($check) > 0) {
        echo "⚠️ Email ou matricule déjà utilisé.";
    } else {
        // Hash du mot de passe
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // ⚠️ Pour l'instant, on stocke une empreinte faciale fictive (à remplacer par ton module IA)
        $empreinte_faciale = "vecteur_demo";

        // Insertion
        $sql = "INSERT INTO utilisateurs (matricule, nom, prenom, filiere, age, sexe, email, mot_de_passe, role, empreinte_faciale) 
                VALUES ('$matricule','$nom','$prenom','$filiere','$age','$sexe','$email','$hash','$role','$empreinte_faciale')";
        if (mysqli_query($conn, $sql)) {
            echo "✅ Inscription réussie !";
            header("Location: index.php"); // redirection vers connexion
        } else {
            echo "Erreur: " . mysqli_error($conn);
        }
    }
}
?>

<!-- Formulaire HTML -->
<form method="POST">
  Matricule: <input type="text" name="matricule" required><br>
  Nom: <input type="text" name="nom" required><br>
  Prénom: <input type="text" name="prenom" required><br>
  Filière: <input type="text" name="filiere" required><br>
  Âge: <input type="number" name="age" min="16" required><br>
  Sexe: 
  <select name="sexe">
    <option value="M">Masculin</option>
    <option value="F">Féminin</option>
  </select><br>
  Email: <input type="email" name="email" required><br>
  Mot de passe: <input type="password" name="mot_de_passe" required><br>
  Rôle: 
  <select name="role">
    <option value="etudiant">Étudiant</option>
    <option value="examinateur">Examinateur</option>
    <option value="admin">Admin</option>
  </select><br>
  <button type="submit">Créer un compte</button>
</form>
