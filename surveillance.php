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
$id_exam = $_GET['id_exam'];

// Réception des anomalies envoyées par JS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_anomalie = $_POST['type_anomalie'];

    $sql = "INSERT INTO anomalies (id_user, id_exam, type_anomalie) 
            VALUES ('$id_user','$id_exam','$type_anomalie')";
    mysqli_query($conn, $sql);

    echo "✅ Anomalie enregistrée.";
}
?>
