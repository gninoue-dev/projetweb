<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php';

echo "<h1>🔍 DEBUG ExamSecure</h1>";
echo "<pre>";

try {
    $test = $pdo->query("SELECT COUNT(*) as count FROM utilisateurs");
    $result = $test->fetch();
    echo "✅ BD OK\n";
    echo "Utilisateurs trouvés: " . $result['count'] . "\n\n";
} catch (Exception $e) {
    echo "❌ ERREUR BD: " . $e->getMessage() . "\n\n";
}

echo "Fichiers OK:\n";
echo "config.php: " . (file_exists('config.php') ? '✅' : '❌') . "\n";
echo "includes/auth.php: " . (file_exists('includes/auth.php') ? '✅' : '❌') . "\n";
echo "js/camera.js: " . (file_exists('js/camera.js') ? '✅' : '❌') . "\n";

echo "\nVersion PHP: " . phpversion() . "\n";
echo "</pre>";
?>
