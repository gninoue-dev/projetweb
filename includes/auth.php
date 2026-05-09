<?php
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}
function requireRole($role) {
    requireLogin();
    if ($_SESSION['user_role'] !== $role) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>
