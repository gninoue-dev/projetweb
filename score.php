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

// Récupérer tous les étudiants ayant répondu à cet examen
$sql_etudiants = "SELECT DISTINCT u.id_user, u.nom, u.prenom, u.matricule
                  FROM reponses r
                  JOIN questions q ON r.id_question = q.id_question
                  JOIN utilisateurs u ON r.id_user = u.id_user
                  WHERE q.id_exam='$id_exam'";
$result_etudiants = mysqli_query($conn, $sql_etudiants);

while($etu = mysqli_fetch_assoc($result_etudiants)) {
    $id_user = $etu['id_user'];

    // Calcul du score global
    $sql_score = "SELECT SUM(note) as total, COUNT(*) as nb_questions
                  FROM reponses r
                  JOIN questions q ON r.id_question = q.id_question
                  WHERE q.id_exam='$id_exam' AND r.id_user='$id_user'";
    $res_score = mysqli_query($conn, $sql_score);
    $score = mysqli_fetch_assoc($res_score);

    $total = $score['total'];
    $nb_questions = $score['nb_questions'];
    $pourcentage = ($nb_questions > 0) ? ($total / $nb_questions) * 100 : 0;

    // Définir statut (admis si >= 50%)
    $statut = ($pourcentage >= 50) ? "admis" : "ajourné";

    // Mise à jour dans la table utilisateurs
    $sql_update = "UPDATE utilisateurs 
                   SET score_global='$pourcentage', statut='$statut' 
                   WHERE id_user='$id_user'";
    mysqli_query($conn, $sql_update);

    echo "✅ Score calculé pour ".$etu['matricule']." : ".$pourcentage."% (".$statut.")<br>";
}
?>
