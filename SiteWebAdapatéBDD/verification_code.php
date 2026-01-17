<?php include 'menu_lateral.php'; ?>
<script src="messagerie.js" defer></script>
<?php
// 1. Récupération des données
$email = $_POST['email'] ?? '';
$code = $_POST['code'] ?? '';

$message = "";
$success = false;

// 2. Traitement de la base de données
if (!empty($email) && !empty($code)) {
    $conn = new mysqli("localhost", "root", "", "bdd");

    if ($conn->connect_error) {
        die("La connexion a échoué: " . $conn->connect_error);
    }

    // Vérification du code
    $stmt = $conn->prepare("SELECT id FROM utilisateur WHERE email = ? AND confirmation_code = ?");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Mise à jour de l'utilisateur
        $stmt = $conn->prepare("UPDATE utilisateur SET est_confirme = 1, confirmation_code = NULL WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $message = "Compte confirmé avec succès 🎉";
        $success = true;
    } else {
        $message = "Le code de confirmation est incorrect ou expiré ❌";
        $success = false;
    }
    $conn->close();
} else {
    $message = "Veuillez remplir le formulaire de confirmation.";
    $success = false;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation - Pawmenade</title>
    <style>
        /* Styles respectant votre charte graphique */
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; padding: 0; background: #f5f7fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        
        .container { 
            background: #ffffff; 
            width: 400px; 
            padding: 25px 30px; 
            border-radius: 10px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            text-align: center; 
        }

        .container h1 { margin-bottom: 20px; color: #9d7153; }

        .message-text { 
            font-size: 16px; 
            color: #333333; 
            margin-bottom: 25px; 
            line-height: 1.5; 
        }

        /* Bouton stylisé en pur CSS */
        .btn-link { 
            display: block; 
            text-decoration: none; 
            width: 100%; 
            padding: 12px; 
            border-radius: 5px; 
            background: #9d7153; 
            color: #f5f5dc; 
            font-size: 16px; 
            font-weight: bold; 
            transition: background 0.2s; 
        }

        .btn-link:hover { background: #825d43; }

        .footer-text { margin-top: 15px; font-size: 12px; color: #777777; }
        .footer-text a { color: #9d7153; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h1><?php echo $success ? "Félicitations !" : "Erreur"; ?></h1>
    
    <div class="message-text">
        <?php echo $message; ?>
    </div>

    <?php if ($success): ?>
        <a href="inscription.php" class="btn-link">Se connecter</a>
    <?php else: ?>
        <a href="inscription.php" class="btn-link">Retour à l'inscription</a>
    <?php endif; ?>

    <div class="footer-text">
        <p>© 2026 Pawmenade - <a href="#">Aide</a></p>
    </div>
</div>

</body>
</html>
