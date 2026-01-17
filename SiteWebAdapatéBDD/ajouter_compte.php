<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];
    $confirmation = $_POST['confirmation'];

    // Vérifier si les mots de passe correspondent
    if ($motdepasse !== $confirmation) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Sécuriser le mot de passe (hacher le mot de passe avant de le stocker)
    $motdepasse_hache = password_hash($motdepasse, PASSWORD_DEFAULT);

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root"; // Utilisateur par défaut de XAMPP
    $password = ""; // Mot de passe vide par défaut sur XAMPP
    $dbname = "utilisateurs"; // Nom de la base de données

    // Créer la connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Préparer la requête SQL pour insérer les données dans la table 'comptes'
    $sql = "INSERT INTO utilisateur (nom, prenom, telephone, email, motdepasse) 
            VALUES ('$nom', '$prenom', '$telephone', '$email', '$motdepasse_hache')";

    // Exécuter la requête
    if ($conn->query($sql) === TRUE) {
        echo "Le compte a été créé avec succès!";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    // Fermer la connexion
    $conn->close();
}
?>