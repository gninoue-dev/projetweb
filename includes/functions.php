<?php
// Connexion à la base (déjà dans db.php, mais tu peux mettre des helpers ici)

// Fonction pour sécuriser les entrées utilisateur
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Fonction pour vérifier si un utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier le rôle
function hasRole($roles = []) {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], $roles);
}

// Fonction pour afficher un message de succès
function successMessage($msg) {
    return "<p class='success'>✅ $msg</p>";
}

// Fonction pour afficher un message d'erreur
function errorMessage($msg) {
    return "<p class='error'>⚠️ $msg</p>";
}
?>
