<?php include 'menu_lateral.php'; ?>
<script src="messagerie.js" defer></script>
<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Publication d'annonce</title>

<style>
    body {
        background-color: #F4F5DC;
        font-family: Arial;
        font-weight: bold;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    * {
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    .container {
        background: #ffffff;
        width: 500px;
        padding: 25px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        text-align: center;
    }

    h1 {
        color: #987154;
        font-weight: bolder;
        margin-bottom: 15px;
    }

    p {
        font-size: 15px;
        color: #555555;
        margin-bottom: 25px;
    }

    button {
        background-color: #987154;
        color: whitesmoke;
        height: 30px;
        width: 200px;
        margin: 20px auto 0;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background-color: #826148;
        color: whitesmoke;
    }

    .success {
        color: #4CAF50;
    }

    .error {
        color: #D32F2F;
    }
</style>

</head>

<body>

<div class="container">
    <h1>Publication d'annonce</h1>

    <!-- Message de succès -->
    <p class="success">
        Annonce publiée avec succès ✅
    </p>

    <!-- Message d’erreur (à utiliser à la place si besoin) -->
    <!--
    <p class="error">
        Erreur lors de la publication de l'annonce ❌
    </p>
    -->

    <button onclick="window.location.href='afficher_annonce.php'">
        Consulter les annonces
    </button>
    <br>
    <button onclick="window.location.href='inscription.php'">
        Retour à la page d'accueil
    </button>
</div>

</body>
</html>
