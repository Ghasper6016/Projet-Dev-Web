<?php
require_once 'Database.php';

class UserModel {

    public static function create($nom, $prenom, $telephone, $email, $password) {
        $conn = Database::getConnection();

        // logique métier
        if (self::emailExists($email)) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO utilisateur (nom, prenom, telephone, email, motdepasse)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssss",
            $nom,
            $prenom,
            $telephone,
            $email,
            $passwordHash
        );

        return $stmt->execute();
    }

    public static function emailExists($email) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare(
            "SELECT id FROM utilisateur WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }
}
