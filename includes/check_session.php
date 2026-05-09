<?php
session_start();
include("includes/functions.php");


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Vérifier le rôle si nécessaire
function checkRole($roles = []) {
    if (!in_array($_SESSION['role'], $roles)) {
        header("Location: index.php");
        exit();
    }
}
?>
