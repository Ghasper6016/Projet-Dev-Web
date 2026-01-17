<?php
session_start();
require_once 'Database.php';
$mysqli = Database::getConnection();

$expediteurId = $_SESSION['user_id'] ?? 1;
$destinataireId = intval($_POST['destinataire_id']);
$contenu = $_POST['contenu'] ?? '';

if(!$destinataireId || !$contenu) {
    echo json_encode(['status'=>'error','message'=>'Paramètres manquants']);
    exit;
}

$stmt = $mysqli->prepare("INSERT INTO message (expediteur_id, destinataire_id, contenu, date_envoi) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $expediteurId, $destinataireId, $contenu);

if($stmt->execute()) {
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error','message'=>'Erreur SQL']);
}
