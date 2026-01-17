<?php
require_once 'Database.php';
$mysqli = Database::getConnection();

// Vérifier que l'ID du message est fourni
if (!isset($_POST['message_id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Message ID manquant']);
    exit;
}

$messageId = intval($_POST['message_id']);

// Optionnel : vérifier que l'utilisateur connecté est bien le destinataire
$monId = 1; // à remplacer par l'utilisateur connecté

$stmt = $mysqli->prepare("UPDATE message SET lu = 1 WHERE id = ? AND destinataire_id = ?");
$stmt->bind_param("ii", $messageId, $monId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Impossible de marquer comme lu']);
}
?>
