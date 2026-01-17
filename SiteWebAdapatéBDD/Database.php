<?php

class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = new mysqli(
                "localhost",
                "root",
                "",
                "bdd"
            );

            if (self::$conn->connect_error) {
                die("Erreur DB : " . self::$conn->connect_error);
            }
        }
        return self::$conn;
    }
}
