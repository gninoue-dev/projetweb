<?php
session_start();
include("includes/db.php");
include("includes/header.php");
include("includes/footer.php");
include("includes/functions.php");

// Vérifier que l'utilisateur est connecté et qu'il est étudiant
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'etudiant') {
    header("Location: index.php");
    exit();
}

$id_user = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reponse'])) {
    foreach ($_POST['reponse'] as $id_question => $reponse_donnee) {
        // Insertion ou mise à jour de la réponse
        $sql = "INSERT INTO reponses (id_question, id_user, reponse_donnee) 
                VALUES ('$id_question','$id_user','$reponse_donnee')
                ON DUPLICATE KEY UPDATE reponse_donnee='$reponse_donnee'";
        mysqli_query($conn, $sql);
    }

    echo "✅ Vos réponses ont été enregistrées avec succès.";
    // Redirection vers une page de confirmation ou tableau de bord étudiant
    header("Location: confirmation.php");
    exit();
} else {
    echo "⚠️ Aucune réponse reçue.";
}
?>
