<?php
session_start();
session_unset(); // Supprime les variables
session_destroy(); // Détruit la session
header("Location: inscription.php"); // Redirige vers l'accueil
exit;
?>