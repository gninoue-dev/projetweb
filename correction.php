<?php
session_start();
include("includes/db.php");
include("includes/functions.php");


// Vérifier que l'utilisateur est examinateur ou admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'examinateur' && $_SESSION['role'] != 'admin')) {
    header("Location: index.php");
    exit();
}

$id_exam = $_GET['id_exam']; // examen sélectionné

// Récupérer toutes les réponses des étudiants pour cet examen
$sql_reponses = "SELECT r.*, q.reponse_correcte 
                 FROM reponses r 
                 JOIN questions q ON r.id_question = q.id_question
                 WHERE q.id_exam='$id_exam'";
$result_reponses = mysqli_query($conn, $sql_reponses);

while($rep = mysqli_fetch_assoc($result_reponses)) {
    $note = 0;

    // Correction automatique simple
    if (strtolower(trim($rep['reponse_donnee'])) == strtolower(trim($rep['reponse_correcte']))) {
        $note = 1; // bonne réponse
    }

    // Mise à jour de la note
    $sql_update = "UPDATE reponses SET note='$note' WHERE id_reponse='".$rep['id_reponse']."'";
    mysqli_query($conn, $sql_update);
}

echo "✅ Correction automatique terminée pour l'examen $id_exam.";
?>
