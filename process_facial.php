<?php
require_once 'config.php';
require_once 'includes/auth.php';

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Requête invalide']);
    exit;
}

$image = $_POST['image'] ?? '';
if (!$image) {
    echo json_encode(['success' => false, 'error' => 'Aucune image reçue']);
    exit;
}

// Décoder l'image base64
$image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

if ($image_data === false) {
    echo json_encode(['success' => false, 'error' => 'Image invalide']);
    exit;
}

// Enregistrer dans la BD
try {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET reconnaissance_faciale = ?, date_reconnaissance = NOW() WHERE id = ?");
    $stmt->execute([$image_data, $_SESSION['user_id']]);
    echo json_encode(['success' => true, 'message' => 'Visage enregistré']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Erreur BD: ' . $e->getMessage()]);
}
?>
