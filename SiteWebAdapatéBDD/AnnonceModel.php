<?php
require_once 'Database.php';

class AnnonceModel {

    public static function create(
        $utilisateurId,
        $localisation,
        $animal,
        $activite,
        $dateDebut,
        $dateFin,
        $prix,
        $telephone,
        $details
    ) {
        $conn = Database::getConnection();

        // logique métier minimale
        if ($dateFin <= $dateDebut) {
            return false;
        }

        $stmt = $conn->prepare("
            INSERT INTO annonce (
                utilisateur_id,
                localisation,
                animal,
                activite,
                date_debut,
                date_fin,
                prix,
                telephone,
                details
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssssdss",
            $utilisateurId,
            $localisation,
            $animal,
            $activite,
            $dateDebut,
            $dateFin,
            $prix,
            $telephone,
            $details
        );

        return $stmt->execute();
    }

    public static function getByUser($utilisateurId) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT * FROM annonce
            WHERE utilisateur_id = ?
            ORDER BY date_publication DESC
        ");

        $stmt->bind_param("i", $utilisateurId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function getAll() {
        $conn = Database::getConnection();

        $result = $conn->query("
            SELECT annonce.*, utilisateur.nom, utilisateur.prenom
            FROM annonce
            JOIN utilisateur ON annonce.utilisateur_id = utilisateur.id
            ORDER BY date_publication DESC
        ");

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}