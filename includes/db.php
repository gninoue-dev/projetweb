<?php
// Paramètres de connexion
$host = "localhost";
$user = "Ozen";          // ton utilisateur MySQL
$password = "Ozen1234@"; // ton mot de passe MySQL
$database = "examen_hybride";

// Connexion
$conn = mysqli_connect($host, $user, $password, $database);

// Vérification
if (!$conn) {
    die("❌ Connexion échouée : " . mysqli_connect_error());
}
?>
