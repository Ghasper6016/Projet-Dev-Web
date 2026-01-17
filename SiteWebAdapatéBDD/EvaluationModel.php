<?php
require_once 'Database.php';

class EvaluationModel {

    /**
     * Crée une nouvelle évaluation
     *
     * @param int $annonceId       L'id de l'annonce concernée
     * @param int $auteurId        L'id de l'utilisateur qui note
     * @param int $destinataireId  L'id de l'utilisateur noté
     * @param int $note            Note de 1 à 5
     * @param string|null $commentaire Commentaire facultatif
     * @return bool
     */
    public static function create($annonceId, $auteurId, $destinataireId, $note, $commentaire = null) {
        $conn = Database::getConnection();

        // Vérification simple de la note
        if ($note < 1 || $note > 5) {
            return false;
        }

        $stmt = $conn->prepare("
            INSERT INTO evaluation (
                annonce_id,
                auteur_id,
                destinataire_id,
                note,
                commentaire
            ) VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiiis",
            $annonceId,
            $auteurId,
            $destinataireId,
            $note,
            $commentaire
        );

        return $stmt->execute();
    }

    /**
     * Récupère toutes les évaluations reçues par un utilisateur
     *
     * @param int $utilisateurId
     * @return array
     */
    public static function getByUtilisateur($utilisateurId) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT e.*, a.titre AS annonce_titre, u.nom AS auteur_nom, u.prenom AS auteur_prenom
            FROM evaluation e
            JOIN utilisateur u ON e.auteur_id = u.id
            JOIN annonce a ON e.annonce_id = a.id
            WHERE e.destinataire_id = ?
            ORDER BY e.date_evaluation DESC
        ");

        $stmt->bind_param("i", $utilisateurId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Calcule la note moyenne d'un utilisateur
     *
     * @param int $utilisateurId
     * @return float|null  Moyenne ou null si aucune évaluation
     */
    public static function getMoyenne($utilisateurId) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("
            SELECT AVG(note) AS moyenne
            FROM evaluation
            WHERE destinataire_id = ?
        ");

        $stmt->bind_param("i", $utilisateurId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['moyenne'] !== null ? round($result['moyenne'], 2) : null;
    }
}
