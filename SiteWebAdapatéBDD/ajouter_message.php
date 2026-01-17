<?php
require_once 'Database.php';
$mysqli = Database::getConnection();

// Remplace par l'ID de l'utilisateur connecté
$monId = 1;

// Récupérer les messages de l'utilisateur connecté
$sql = "SELECT m.id, m.contenu, m.service, m.lu, m.date_envoi,
               u.prenom, u.nom
        FROM message m
        JOIN utilisateur u ON m.expediteur_id = u.id
        WHERE m.destinataire_id = ?
        ORDER BY m.date_envoi DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $monId);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);
?>
