<script src="messagerie.js" defer></script>
<?php include 'menu_lateral.php'; ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// 1️⃣ TRAITEMENT DU FORMULAIRE
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];
    $confirmation = $_POST['confirmation'];

    if ($motdepasse !== $confirmation) {
        header("Location: inscription.php?erreur=mdp");
        exit;
    }

    $hash = password_hash($motdepasse, PASSWORD_DEFAULT);
    $code = rand(100000, 999999);

    $conn = new mysqli("localhost", "root", "", "bdd");
    if ($conn->connect_error) {
        die("Erreur DB");
    }

    $stmt = $conn->prepare("
        INSERT INTO utilisateur (nom, prenom, telephone, email, motdepasse, confirmation_code, est_confirme)
        VALUES (?, ?, ?, ?, ?, ?, 0)
    ");
    $stmt->bind_param("ssssss", $nom, $prenom, $telephone, $email, $hash, $code);
    $stmt->execute();

    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pawmenadeofficiel@gmail.com';
        $mail->Password = 'mafw gbxw iwcg ctto'; // Gmail demande un mot de passe spécifique "App Password"
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Définir l'expéditeur et le destinataire
        $mail->setFrom('pawmenadeofficiel@gmail.com', 'Pawmenade');
        $mail->addAddress($email, $prenom);

        // Définir le sujet de l'email
        $mail->Subject = "Confirmation de votre inscription";

        // Définir le corps du message
        $mail->Body = "Bonjour $prenom,\n\nVotre code de confirmation est : $code\n\nMerci !";

        // Envoyer l'email
        $mail->send();
        echo 'Email envoyé !';
        $mail->send();
        echo 'Email envoyé !';
    } catch (Exception $e) {
        echo "Échec de l'envoi : {$mail->ErrorInfo}";
    }

    // Envoi de l'email
    $sujet = "Confirmation de votre inscription";
    $message = "Bonjour $prenom,\n\nVotre code de confirmation est : $code\n\nMerci !";
    $headers = "From: pawmenadeofficiel@gmail.com";

    mail($email, $sujet, $message, $headers);

    header("Location: confirmation.php?email=" . urlencode($email));
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Pawmenade</title>

    <style>
    /* -------------------- VARIABLES -------------------- */
    :root {
      --bg: #f4eedc;
      --white: #ffffff;
      --brown: #a06a43;
      --brown-dark: #7b4f30;
      --green: #00c7a0;
      --gray: #7c6f66;
      --shadow: 0 6px 18px rgba(24,20,18,0.08);
      --radius: 14px;
      --wrap: 1180px;
    }

    /* -------------------- GLOBAL -------------------- */
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: var(--bg);
      color: var(--brown-dark);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    main {
      flex: 1;
    }

    .container {
      max-width: var(--wrap);
      margin: 0 auto;
      padding: 0 24px;
    }

    img {
      display: block;
    }

    button, input, select {
      font-family: inherit;
    }

    /* -------------------- HEADER -------------------- */
    header {
      background: var(--white);
      border-bottom: 1px solid #e6dfd4;
      padding: 25px;
    }

    .header-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .marque {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .header-logo {
      width: 100px;
      height: auto;
      object-fit: contain;
    }

    .marque-text h1 {
      margin: 0;
      font-size: 22px;
      font-weight: 800;
      color: var(--brown-dark);
    }

    .marque-text .sous-titre {
      margin: 0;
      font-size: 14px;
      color: var(--gray);
    }

    .main-nav {
      display: flex;
      gap: 28px;
    }

    .main-nav a {
      text-decoration: none;
      font-weight: 600;
      font-size: 15px;
      color: var(--brown-dark);
    }

    .main-nav a:hover {
      color: var(--brown);
    }

    .actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .actions .signin {
      border: none;
      background: none;
      font-weight: 600;
      color: var(--green);
      cursor: pointer;
      padding: 6px 10px;
    }

    .actions .signup {
      background: var(--brown);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 8px 18px;
      font-weight: 700;
      cursor: pointer;
      font-size: 14px;
      transition: 0.2s;
    }

    .actions .signup:hover {
      background: var(--brown-dark);
    }

    .signup {
      background: var(--brown);
      border: none;
      color: white;
      padding: 8px 14px;
      border-radius: 8px;
      font-weight: 700;
      cursor: pointer;
    }

    /* -------------------- SECTIONS -------------------- */
    .section {
      padding: 40px 0;
    }

    .grille-section {
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 40px;
    }

    .titre-section {
      font-size: 48px;
      font-weight: 900;
      margin: 0 0 12px;
    }

    .soustitre-section {
      color: var(--gray);
      max-width: 520px;
      margin-bottom: 26px;
      font-size: 21px;
    }

    .barre-recherche {
      background: var(--white);
      padding: 18px;
      border-radius: 22px;
      display: flex;
      gap: 12px;
      align-items: center;
      box-shadow: var(--shadow);
    }

    .champ {
      background: var(--white);
      border: 1px solid #e6dfd4;
      border-radius: 10px;
      padding: 10px 12px;
      display: flex;
      align-items: center;
    }

    .champ input {
      border: none;
      outline: none;
      width: 100%;
      font-size: 14px;
    }

    .btn {
      background: var(--brown);
      color: white;
      border: none;
      padding: 10px 18px;
      border-radius: 10px;
      font-weight: 700;
      cursor: pointer;
    }

    .astuce {
      font-size: 13px;
      color: #958c80;
      margin-top: 6px;
    }

    .soustitre-HD {
      font-size: 22px;
      font-weight: 800;
      margin-bottom: 12px;
    }

    .list-chien {
      display: flex;
      flex-direction: column;
      gap: 38px;
    }

    .chien {
      background: var(--white);
      padding: 20px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      box-shadow: var(--shadow);
    }

    .chien img {
      width: 62px;
      height: 62px;
      border-radius: 10px;
      object-fit: cover;
    }

    .chien .info h4 {
      margin: 0;
      font-weight: 800;
    }

    .chien .info {
      flex: 1;
    }

    .view {
      background: rgba(0, 199, 160, 0.15);
      border: none;
      padding: 6px 14px;
      border-radius: 8px;
      color: var(--green);
      font-weight: 700;
      cursor: pointer;
      white-space: nowrap;
    }

    .pager {
      display: flex;
      gap: 20px;
      margin-top: 12px;
      align-items: center;
      justify-content: center;
    }

    .etape-icon {
      display: block;
      width: 22px;
      height: 22px;
      object-fit: contain;
      margin: 0 auto 12px;
    }

    .etapes {
      display: flex;
      gap: 20px;
      margin-top: 26px;
    }

    .etape {
      flex: 1;
      background: var(--white);
      padding: 20px;
      border-radius: var(--radius);
      text-align: center;
      box-shadow: var(--shadow);
    }

    .etape h3 {
      margin: 0 0 8px;
    }

    .etape p {
      margin: 0;
      font-size: 13px;
      color: var(--gray);
    }

    .pagination {
      display: flex;
      gap: 12px;
      margin: 26px auto 0;
      justify-content: center;
      align-items: center;
    }

    .pagination button {
      background: var(--white);
      border: 1px solid #e6dfd4;
      padding: 8px 14px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      color: var(--brown-dark);
      box-shadow: var(--shadow);
      transition: 0.2s ease;
    }

    .pagination button:hover {
      background: #f7f1e3;
      border-color: var(--brown);
    }

    .section h2 {
      font-size: 28px;
      margin: 20px 0;
    }

    .deux-colonne {
      display: grid;
      grid-template-columns: 320px 1fr;
      gap: 30px;
    }

    .filtre {
      background: var(--white);
      padding: 20px;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .filtre label {
      display: block;
      font-weight: 700;
      margin-top: 14px;
    }

    .filtre select,
    .filtre input[type="range"] {
      width: 90%;
      margin-top: 15px;
      border-radius: 8px;
      border: 1px solid #e6dfd4;
    }

    .list-quand {
      margin-top: 8px;
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
    }

    .chip {
      background: white;
      border: 2px solid var(--brown-dark);
      padding: 8px 14px;
      border-radius: 12px;
      font-size: 14px;
      cursor: pointer;
      color: var(--brown-dark);
      font-weight: 600;
      transition: 0.2s;
    }

    .chip:hover {
      background: var(--brown);
      color: white;
    }

    .chip:active,
    .chip.selected {
      background: var(--brown);
      border-color: var(--brown);
      color: white;
    }

    .price-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-top: 6px;
    }

    .price-value {
      font-weight: 700;
      font-size: 15px;
      color: var(--brown-dark);
      min-width: 40px;
      text-align: right;
    }

    .results-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }

    .carte {
      background: var(--white);
      padding: 16px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      gap: 14px;
      box-shadow: var(--shadow);
    }

    .carte img {
      width: 75px;
      height: 75px;
      border-radius: 10px;
      object-fit: cover;
    }

    .info h4 {
      margin: 0;
      font-weight: 800;
    }

    .info p {
      margin: 4px 0;
      font-size: 13px;
      color: var(--gray);
    }

    .carte-actions {
      margin-left: auto;
      display: flex;
      flex-direction: column;
      gap: 6px;
      align-items: flex-end;
    }

    .star {
      color: #f4c843;
      font-size: 16px;
      font-weight: 800;
    }

    .btn-brown {
      background: var(--brown);
      border: none;
      padding: 6px 12px;
      border-radius: 8px;
      color: white;
      cursor: pointer;
    }

    .btn-green {
      background: var(--green);
      border: none;
      padding: 6px 12px;
      border-radius: 8px;
      color: white;
      cursor: pointer;
    }

    .deviens {
      background: #f5dfb9;
      padding: 24px;
      margin-top: 50px;
      margin-bottom: 50px;
      border-radius: var(--radius);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* ---- cartes pour les propriétaires ---- */
    .list-propriétaires {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .propriétaire {
      background: var(--white);
      padding: 20px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      box-shadow: var(--shadow);
    }

    .propriétaire img {
      width: 62px;
      height: 62px;
      border-radius: 10px;
      object-fit: cover;
    }

    /* ---- cartes blanches pour les annonces ---- */
    .annonces {
      background: var(--white);
      padding: 16px;
      border-radius: var(--radius);
      display: flex;
      align-items: center;
      gap: 14px;
      box-shadow: var(--shadow);
    }

    .annonces img {
      width: 75px;
      height: 75px;
      border-radius: 10px;
      object-fit: cover;
    }

    /* -------------------- SECTION PROFIL PROPRIÉTAIRE -------------------- */
    #profil-proprietaire {
      margin-top: 40px;
      margin-bottom: 60px;
      padding: 24px;
      border-radius: var(--radius);
      background: #f0e4cf;
      display: none;
    }

    #profil-proprietaire .profil-entete {
      display: flex;
      gap: 20px;
      align-items: flex-start;
      margin-bottom: 24px;
    }

    #profil-proprietaire .profil-photo {
      width: 90px;
      height: 90px;
      border-radius: 18px;
      object-fit: cover;
      background: #ccc;
    }

    #profil-proprietaire .profil-infos {
      flex: 1;
    }

    #profil-proprietaire .profil-infos h2 {
      margin: 0 0 4px;
      font-size: 22px;
    }

    #profil-proprietaire .profil-infos p {
      margin: 4px 0;
      font-size: 14px;
      color: var(--gray);
    }

    #profil-proprietaire .profil-note {
      font-size: 14px;
      font-weight: 600;
      margin-top: 4px;
    }

    #profil-proprietaire .profil-contact {
      margin-top: 10px;
      font-size: 14px;
    }

    #profil-proprietaire .profil-contact a {
      color: var(--green);
      text-decoration: none;
      font-weight: 600;
    }

    #profil-proprietaire .profil-contact a:hover {
      text-decoration: underline;
    }

    #profil-proprietaire .profil-commentaires {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid rgba(0,0,0,0.1);
      font-size: 14px;
    }

    #profil-proprietaire .profil-commentaires h3 {
      margin: 0 0 6px;
      font-size: 15px;
    }

    #profil-proprietaire .profil-commentaires ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #profil-proprietaire .profil-commentaires li {
      margin-bottom: 6px;
    }

    #profil-proprietaire .profil-annonces {
      margin-top: 24px;
    }

    #profil-proprietaire .profil-annonces h3 {
      margin: 0 0 12px;
    }

    #profil-proprietaire .profil-annonces-liste {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 14px;
    }

    #profil-proprietaire .profil-annonces-liste .carte-annonce {
      background: var(--white);
      border-radius: var(--radius);
      padding: 14px;
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      gap: 4px;
      font-size: 14px;
    }

    #profil-proprietaire .profil-annonces-liste .carte-annonce h4 {
      margin: 0 0 4px;
      font-size: 16px;
    }

    #profil-proprietaire .btn-retour {
      margin-bottom: 14px;
      border: none;
      background: transparent;
      color: var(--brown-dark);
      font-size: 14px;
      cursor: pointer;
      text-decoration: underline;
    }

    /* -------------------- FOOTER -------------------- */
    footer.footer {
      background-color: #9d7153;
      color: #fff;
      padding: 50px 20px 20px;
      margin-top: 60px;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-section h3 {
      font-size: 18px;
      margin-bottom: 20px;
      color: #fff;
      font-weight: 600;
    }

    .footer-section ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .footer-section ul li {
      margin-bottom: 12px;
    }

    .footer-section a {
      color: #fff;
      text-decoration: none;
      transition: color 0.3s ease;
      font-size: 14px;
    }

    .footer-section a:hover {
      color: #5cc8a1;
    }

    .footer-section p {
      color: #fff;
      font-size: 14px;
      line-height: 1.6;
    }

    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .contact-info p {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin: 0;
    }

    .footer .social-links {
      display: flex;
      gap: 20px;
      margin-top: 15px;
    }

    .footer .social-links a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: #7b4f30;
      border-radius: 50%;
      color: #fff;
      font-size: 18px;
      text-decoration: none;
      transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
    }

    .footer .social-links a:hover {
      background-color: #5cc8a1;
      color: #fff;
      transform: translateY(-2px);
    }

    .newsletter-form {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }

    .newsletter-form input {
      flex: 1;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      font-size: 14px;
      background-color: #5cc8a1;
      color: #fff;
    }

    .newsletter-form input::placeholder {
      color: #fff;
    }

    .newsletter-form button {
      padding: 10px 20px;
      background-color: #0084ff;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .newsletter-form button:hover {
      background-color: #0066cc;
    }

    .footer-bottom {
      border-top: 1px solid #5cc8a1;
      padding-top: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
    }

    .footer-bottom p {
      color: #fff;
      font-size: 13px;
      margin: 0;
    }

    .footer-links {
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
    }

    .footer-links a {
      color: #fff;
      text-decoration: none;
      font-size: 13px;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: #5cc8a1;
    }

    @media (max-width: 768px) {
      footer.footer {
        padding: 40px 20px 20px;
      }

      .footer-content {
        gap: 30px;
      }

      .footer-bottom {
        flex-direction: column;
        text-align: center;
      }

      .footer-links {
        justify-content: center;
      }

      .newsletter-form {
        flex-direction: column;
      }

      .footer .social-links {
        justify-content: center;
      }
    }

    /* ------------- MODALES (CONNEXION / INSCRIPTION / FAQ / CONF / CGU) ------------- */

    .blurred {
      filter: blur(5px);
      pointer-events: none;
      user-select: none;
    }

    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      backdrop-filter: blur(6px);
    }

    .modal-overlay.active {
      display: flex;
    }

    .modal-box {
      background: rgba(255,255,255,0.95);
      border-radius: 12px;
      padding: 25px 30px;
      width: 380px;
      max-width: 90vw;
      box-shadow: 0 12px 30px rgba(0,0,0,0.3);
      position: relative;
    }

    .modal-box h2 {
      margin: 0 0 20px;
      text-align: center;
      color: #9d7153;
      font-size: 22px;
    }

    .modal-close {
      position: absolute;
      top: 10px;
      right: 12px;
      border: none;
      background: transparent;
      font-size: 20px;
      cursor: pointer;
      color: #999;
    }

    .modal-form-group {
      margin-bottom: 14px;
    }

    .modal-form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
      color: #333;
    }

    .modal-form-group input {
      width: 100%;
      padding: 10px 12px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
      transition: border-color 0.2s, box-shadow 0.2s;
    }

    .modal-form-group input:focus {
      border-color: #9d7153;
      box-shadow: 0 0 0 2px rgba(157,113,83,0.2);
      outline: none;
    }

    .modal-hint {
      font-size: 12px;
      color: #888;
      margin-top: 3px;
    }

    .modal-btn-submit {
      width: 100%;
      padding: 10px 12px;
      border-radius: 5px;
      border: none;
      background: #9d7153;
      color: #f5f7dc;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 5px;
      transition: background 0.2s;
    }

    .modal-btn-submit:hover {
      background: #7c5940;
    }

    .modal-footer-text {
      margin-top: 12px;
      text-align: center;
      font-size: 12px;
      color: #555;
    }

    .modal-footer-text a {
      color: #9d7153;
      text-decoration: none;
    }

    .modal-footer-text a:hover {
      text-decoration: underline;
    }

    .faq-header {
      text-align: center;
      padding: 20px 10px;
      background: #fff;
      border-bottom: 1px solid #ddd;
    }
    .faq-header h2 {
      font-size: 1.8rem;
      margin-bottom: 6px;
      font-weight: 700;
    }
    .faq-header p {
      font-size: 0.95rem;
      color: #666;
      margin: 0;
    }

    .faq {
      max-width: 760px;
      margin: 20px auto 10px;
      padding: 0 5px 10px;
    }
    .faq details {
      background: #fff;
      margin-bottom: 14px;
      border-radius: 14px;
      padding: 14px 18px;
      border: 1px solid #ddd;
      transition: 0.3s ease;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .faq details:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .faq summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
      list-style: none;
      cursor: pointer;
      font-size: 1.05rem;
      font-weight: 600;
    }
    .faq summary::-webkit-details-marker {
      display: none;
    }
    .faq details p {
      margin-top: 10px;
      color: #555;
      line-height: 1.55;
      animation: fadeFaq 0.3s ease;
    }
    @keyframes fadeFaq {
      from { opacity: 0; transform: translateY(-5px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    </style>
</head>
<body>

    <div id="accueil">

        <!-- ===== HEADER ===== -->
        <header>
            <div class="container header-inner">

                <div class="marque">
                    <img src="img/Logo.png" alt="logo" class="header-logo">
                    <div class="marque-text">
                        <h1>Pawmenade</h1>
                        <span class="sous-titre">Trouvez votre Petsitter</span>
                    </div>
                    </a>
                </div>

                <nav class="main-nav">
                    <a href="#">Trouver un Petsitter</a>
                    <a href="publication_annonce.php">Chercher une annonce</a>
                    <a href="#" id="open-faq">FAQ</a>
                    <a href="messagerie.php">Messagerie</a>
                </nav>

                <div class="actions" id="header-actions">
                    <button class="signin" id="open-login">Connexion</button>
                    <button class="signup" id="open-signup">S'inscrire</button>
                </div>

            </div>
        </header>

        <!-- ===== MAIN ===== -->
        <main class="container">
            <section class="section">
                <div class="grille-section">

                    <div>
                        <h2 class="titre-section">Trouvez une annonce qui vous interesse près de chez vous</h2>
                        <p class="soustitre-section">
                            Réservez facilement un animal de confiance près de chez vous.
                            Promenade, garde à domicile, ou visites rapides.
                        </p>

                        <div class="barre-recherche">
                            <div class="champ">
                                <input type="text" placeholder="Ex : PARIS" />
                            </div>
                            <span class="label">Du</span>
                            <input type="date" class="champ" />
                            <input type="date" class="champ" />
                            <button class="btn">Chercher</button>
                        </div>

                        <div class="astuce">
                            Astuce : mettez le nom de votre ville ou votre code postal.
                        </div>

                        <div class="etapes">
                            <div class="etape">
                                <img src="img/C1.png" alt="icône cherche" class="etape-icon">
                                <h3>1. Cherchez</h3>
                                <p>Filtrez selon la distance, le prix et la disponibilité.</p>
                            </div>

                            <div class="etape">
                                <img src="img/Co1.png" alt="icône contacte" class="etape-icon">
                                <h3>2. Contactez</h3>
                                <p>Discutez avec le propriétaire des besoins de l'animal et demandes spécifiques.</p>
                            </div>

                            <div class="etape">
                                <img src="img/R1.png" alt="icône réserve" class="etape-icon">
                                <h3>3. Réservez</h3>
                                <p>Paiement sécurisé et confirmation.</p>
                            </div>
                        </div>

                    </div>

                    <!-- COLONNE DROITE : PROPRIÉTAIRES PRÈS DE CHEZ VOUS -->
                    <aside>
                        <h3 class="soustitre-HD">Propriétaires près de chez vous</h3>

                        <div class="list-propriétaires">

                            <div class="propriétaire" data-proprio-id="monique">
                                <img src="img/image 11.png" alt="Monique">
                                <div class="info">
                                    <h4>Monique - Paris 11e</h4>
                                    <p>J'ai un gentil petit chien du nom de Milo, je cherche une personne de confiance pour le garder de temps en temps.</p>
                                    <p>Merci de me contacter si vous êtes disponible pour garder mon loulou.</p>
                                </div>
                                <button class="view btn-profil">Voir profil</button>
                            </div>

                            <div class="propriétaire" data-proprio-id="hugo">
                                <img src="img/image 12.png" alt="Hugo">
                                <div class="info">
                                    <h4>Hugo - Issy-les-Moulineaux</h4>
                                    <p>Mon chat est très affectueux et gentil.</p>
                                    <p>Accepteriez-vous de la garder en mon abscence ?</p>
                                </div>
                                <button class="view btn-profil">Voir profil</button>
                            </div>

                            <div class="propriétaire" data-proprio-id="sam">
                                <img src="img/image 13.png" alt="Sam">
                                <div class="info">
                                    <h4>Sam - Paris 2e</h4>
                                    <p>Je pars en vacances et j'aurais vraiment besoin de faire garder mon chien.</p>
                                    <p>Contactez moi si l'annonce vous convient !</p>
                                </div>
                                <button class="view btn-profil">Voir profil</button>
                            </div>

                        </div>

                        <div class="pagination">
                            <button>Préc</button>
                            <button>1</button>
                            <button>2</button>
                            <button>Suiv</button>
                        </div>
                    </aside>

                </div>
            </section>

            <!-- ANNONCES -->
            <section class="section">
                <h2>Annonces près de chez vous</h2>

                <div class="deux-colonne">

                    <aside class="filtre">
                        <h2>Filtres</h2>

                        <label>Prix maximum (€ / h)</label>
                        <div class="price-row">
                            <input type="range" id="priceRange" min="5" max="50" value="20">
                            <span id="priceOutput" class="price-value">20 €</span>
                        </div>

                        <label>Disponibilité</label>
                        <select>
                            <option>Aujourd'hui</option>
                            <option>Cette Semaine</option>
                            <option>Ce Mois-ci</option>
                        </select>

                        <label>Type</label>
                        <div class="list-quand">
                            <button class="chip">Promenade</button>
                            <button class="chip">Garde à domicile</button>
                            <button class="chip">Visite rapide</button>
                            <button class="chip">Garde en pension</button>
                        </div>
                    </aside>

                    <div>

                        <div class="results-grid">

                            <div class="annonces" data-proprio-id="monique">
                                <img src="img/image 14.png" alt="Voltaire">
                                <div class="info">
                                    <h4>Voltaire - 4 ans</h4>
                                    <p>Cherche un petsitter pour venir garder Voltaire jeudi de 13h à 23h car je suis déplacement.</p>
                                    <p>Tarif sera de 50€ la journée (discutable).</p>
                                    <p>Contacter moi pour en parler !</p>
                                </div>
                                <div class="carte-actions">
                                    <div class="star">★ 4.9</div>
                                    <button class="btn-brown btn-profil">Voir profil</button>
                                    <button class="btn-green">Contacter</button>
                                </div>
                            </div>

                            <div class="annonces" data-proprio-id="hugo">
                                <img src="img/image 16.png" alt="Milo">
                                <div class="info">
                                    <h4>Milo - 6 ans</h4>
                                    <p>Besoin d'une personne de confiance pour faire des promenades avec Milo.</p>
                                    <p>Prix fixe à la balade selon la durée.</p>
                                </div>
                                <div class="carte-actions">
                                    <div class="star">★ 4.8</div>
                                    <button class="btn-brown btn-profil">Voir profil</button>
                                    <button class="btn-green">Contacter</button>
                                </div>
                            </div>

                            <div class="annonces" data-proprio-id="sam">
                                <img src="img/image 17.jpg" alt="Oslo">
                                <div class="info">
                                    <h4>Oslo — 2 ans</h4>
                                    <p>Chat très doux et affectueux</p>
                                    <p>Disponible ce weekend pour être garder à domicile.</p>
                                </div>
                                <div class="carte-actions">
                                    <div class="star">★ 4.5</div>
                                    <button class="btn-brown btn-profil">Voir profil</button>
                                    <button class="btn-green">Contacter</button>
                                </div>
                            </div>

                            <!-- THOR ATTRIBUÉ À SAM -->
                            <div class="annonces" data-proprio-id="sam">
                                <img src="img/image 6.png" alt="Thor">
                                <div class="info">
                                    <h4>Thor — 8 ans</h4>
                                    <p>Chien calme qui ne sort pas de sa maison, il faut juste lui donner à manger.</p>
                                    <p>Tarif de 25€.</p>
                                </div>
                                <div class="carte-actions">
                                    <div class="star">★ 3.9</div>
                                    <button class="btn-brown btn-profil">Voir profil</button>
                                    <button class="btn-green">Contacter</button>
                                </div>
                            </div>

                        </div>

                        <div class="pagination">
                            <button>Préc</button>
                            <button>1</button>
                            <button>2</button>
                            <button>Suiv</button>
                        </div>

                    </div>
                </div>

                <div class="deviens">
                    <div>
                        <h3>Tu veux devenir Petsitter ?</h3>
                        <p>Crée ton profil et commence à recevoir des demandes.</p>
                    </div>
                    <button class="signup" id="open-signup-cta">
                        Créer mon profil
                    </button>
                </div>

            </section>

            <!-- PROFIL PROPRIÉTAIRE -->
            <section id="profil-proprietaire">
              <button class="btn-retour" id="btn-retour-profil">← Retour</button>
              <div class="profil-entete">
                <img src="" alt="Photo propriétaire" class="profil-photo" id="profil-photo">
                <div class="profil-infos">
                  <h2 id="profil-nom"></h2>
                  <p id="profil-ville"></p>
                  <p class="profil-note" id="profil-note"></p>
                  <div class="profil-contact" id="profil-contact"></div>
                  <div class="profil-commentaires">
                    <h3>Avis des petsitters</h3>
                    <ul id="profil-commentaires-liste"></ul>
                  </div>
                </div>
              </div>
              <div class="profil-annonces">
                <h3>Ses annonces</h3>
                <div class="profil-annonces-liste" id="profil-annonces-liste"></div>
              </div>
            </section>

        </main>

        <footer class="footer">
            <div class="footer-container">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>À Propos</h3>
                        <p>Nous créons des solutions innovantes pour votre entreprise. Notre équipe est dédiée à votre succès.</p>
                        <div class="social-links">
                            <a href="https://facebook.com" title="Facebook" aria-label="Facebook">f</a>
                            <a href="https://twitter.com" title="Twitter" aria-label="Twitter">𝕏</a>
                            <a href="https://linkedin.com" title="LinkedIn" aria-label="LinkedIn">in</a>
                            <a href="https://instagram.com" title="Instagram" aria-label="Instagram">📷</a>
                        </div>
                    </div>

                    <div class="footer-section">
                        <h3>Liens Utiles</h3>
                        <ul>
                            <li><a href="#accueil">Accueil</a></li>
                            <li><a href="#" id="open-services-footer">Services</a></li>
                            <li><a href="#" id="open-contact-footer">Contact</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3>Légal</h3>
                        <ul>
                            <li><a href="#" id="open-confidentialite-link">Politiques de Confidentialité</a></li>
                            <li><a href="#" id="open-conditions-footer">Conditions Générales</a></li>
                            <li><a href="#" id="open-mentions-link">Mentions Légales</a></li>
                        </ul>
                    </div>

                    <div class="footer-section">
                        <h3>Nous Contacter</h3>
                        <div class="contact-info">
                            <p>📍 
                                <a href="https://www.google.com/maps/search/?api=1&query=28+Rue+Notre-Dame-des-Champs+75006+Paris"
                                    target="_blank">
                                    28 Rue Notre-Dame-des-Champs, 75006 Paris
                                </a>
                            </p>
                            <p>📞 <a href="tel:+33123456789">+33 1 23 45 67 89</a></p>
                            <p>✉️ <a href="mailto:pawmenadeofficiel@gmail.com">pawmenadeofficiel@gmail.com</a></p>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p>&copy; 2026 Pawmenade. Tous droits réservés.</p>
                    <div class="footer-links">
                        <a href="#" id="open-confidentialite-footer">Confidentialité</a>
                        <a href="#" id="open-conditions-footer-bottom">Conditions</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- ===== MODAL CONNEXION ===== -->
    <div class="modal-overlay" id="modal-login">
        <div class="modal-box">
            <button class="modal-close" data-close="login">&times;</button>
            <h2>Connexion</h2>

            <form action="#" method="post">
                <div class="modal-form-group">
                    <label for="login-email">Adresse e-mail</label>
                    <input type="email" id="login-email" name="email" required>
                </div>

                <div class="modal-form-group">
                    <label for="login-password">Mot de passe</label>
                    <input type="password" id="login-password" name="motdepasse" required>
                </div>

                <button type="submit" class="modal-btn-submit">Se connecter</button>
            </form>

            <div class="modal-footer-text">
                Pas encore de compte ?
                <a href="#" id="to-signup">Créer un compte</a>
            </div>
        </div>
    </div>

    <!-- ===== MODAL INSCRIPTION ===== -->
    <div class="modal-overlay" id="modal-signup">
        <div class="modal-box">
            <button class="modal-close" data-close="signup">&times;</button>
            <h2>Inscription</h2>

            <form action="inscription.php" method="post">
                <div class="modal-form-group">
                    <label for="signup-nom">Nom</label>
                    <input type="text" id="signup-nom" name="nom" required>
                </div>

                <div class="modal-form-group">
                    <label for="signup-prenom">Prénom</label>
                    <input type="text" id="signup-prenom" name="prenom" required>
                </div>

                <div class="modal-form-group">
                    <label for="signup-telephone">Numéro de téléphone</label>
                    <input type="tel" id="signup-telephone" name="telephone" required>
                    <div class="modal-hint">Exemple : 06 12 34 56 78</div>
                </div>

                <div class="modal-form-group">
                    <label for="signup-email">Adresse e-mail</label>
                    <input type="email" id="signup-email" name="email" required>
                </div>

                <div class="modal-form-group">
                    <label for="signup-password">Mot de passe</label>
                    <input type="password" id="signup-password" name="motdepasse" required minlength="8">
                    <div class="modal-hint">Au moins 8 caractères.</div>
                </div>

                <div class="modal-form-group">
                    <label for="signup-confirmation">Confirmer le mot de passe</label>
                    <input type="password" id="signup-confirmation" name="confirmation" required minlength="8">
                </div>

                <button type="submit" class="modal-btn-submit">Créer mon compte </button>
            </form>

            <div class="modal-footer-text">
                Vous avez déjà un compte ?
                <a href="#" id="to-login">Se connecter</a>
            </div>
        </div>
    </div>

    <!-- ===== MODAL FAQ ===== -->
    <div class="modal-overlay" id="modal-faq">
        <div class="modal-box" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
            <button class="modal-close" data-close="faq">&times;</button>

            <header class="faq-header">
            <section class="faq">
              <details>
                <summary>Comment créer un compte ?</summary>
                <p>Pour créer un compte, cliquez sur "S'inscrire" en haut de la page et complétez le formulaire.</p>
              </details>

              <details>
                <summary>Comment modifier mes informations ?</summary>
                <p>Rendez-vous dans votre espace personnel puis cliquez sur "Modifier mon profil".</p>
              </details>

              <details>
                <summary>Comment contacter l'assistance ?</summary>
                <p>Vous pouvez nous écrire via le formulaire de contact ou par email à support@site.com.</p>
              </details>

              <details>
                <summary>Mon mot de passe ne fonctionne pas, que faire ?</summary>
                <p>Cliquez sur "Mot de passe oublié ?" et suivez les instructions pour le réinitialiser.</p>
              </details>
            </section>
        </div>
      </div>

    <!-- ===== MODAL CONFIDENTIALITÉ ===== -->
    <div class="modal-overlay" id="modal-confidentialite">
      <div class="modal-box" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <button class="modal-close" data-close="confidentialite">&times;</button>

        <header style="display:flex;align-items:center;gap:16px;margin-bottom:16px;border-bottom:1px solid #eee;padding-bottom:10px;">
          <img src="img/Logo.png"
                alt="Logo Pawmenade"
                style="width:48px;height:48px;object-fit:contain;border-radius:12px;">
          <div>
              <div style="font-weight:700;letter-spacing:0.03em;font-size:1.1rem;">Pawmenade</div>
              <div style="font-size:0.8rem;color:#7a6a55;">Trouvez votre Petsitter en confiance</div>
          </div>
        </header>

        <main style="max-width:900px;margin:0 auto 10px;">
          <section style="background-color:#ffffff;border-radius:14px;box-shadow:0 8px 20px rgba(0,0,0,0.05);padding:20px 18px 24px;">
            <h1 style="font-size:1.6rem;margin:0 0 8px;color:#b6763c;">Politique de confidentialité</h1>
            <p style="margin:0 0 16px;font-size:0.95rem;color:#7a6a55;">
              Cette page explique comment Pawmenade collecte, utilise et protège vos données personnelles.
            </p>
            <p style="font-size:0.8rem;color:#8a7c65;margin-bottom:16px;">
              Dernière mise à jour : 09/01/2026
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">1. Responsable du traitement</h2>
            <p style="margin:0 0 8px;font-size:0.95rem;">
              Le responsable du traitement des données personnelles est&nbsp;:
              Nom / Raison sociale, Adresse complète, E-mail de contact, Téléphone.
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">2. Données collectées</h2>
            <p style="margin:0 0 8px;font-size:0.95rem;">
              Selon votre utilisation du site, les données suivantes peuvent être collectées :
            </p>
            <ul style="margin:0 0 8px 18px;padding:0;font-size:0.95rem;">
              <li>Données d’identification (nom, prénom, pseudonyme).</li>
              <li>Données de contact (adresse e-mail, numéro de téléphone).</li>
              <li>Données de connexion (adresse IP, logs de connexion, type de navigateur).</li>
              <li>Données liées au service (informations sur les annonces, messages échangés, réservations).</li>
            </ul>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">3. Finalités du traitement</h2>
            <p>Les données sont utilisées pour&nbsp;:</p>
            <ul style="margin:0 0 8px 18px;padding:0;font-size:0.95rem;">
              <li>Fournir et gérer les services proposés sur le site.</li>
              <li>Gérer les comptes utilisateurs et la relation avec les membres.</li>
              <li>Assurer la sécurité du site et prévenir les fraudes.</li>
              <li>Effectuer des statistiques anonymisées pour améliorer le service.</li>
            </ul>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">4. Bases légales</h2>
            <p>Les traitements reposent, selon les cas, sur&nbsp;:</p>
            <ul style="margin:0 0 8px 18px;padding:0;font-size:0.95rem;">
              <li>L’exécution d’un contrat ou de mesures précontractuelles.</li>
              <li>Le respect d’obligations légales.</li>
              <li>L’intérêt légitime de l’éditeur (amélioration du service, sécurité).</li>
              <li>Votre consentement lorsque celui-ci est requis.</li>
            </ul>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">5. Durée de conservation</h2>
            <p>
              Les données sont conservées pendant la durée nécessaire à la fourniture du service,
              augmentée des durées de prescription légales applicables.
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">6. Destinataires des données</h2>
            <p>
              Les données sont destinées uniquement aux équipes habilitées de Pawmenade et, le cas échéant,
              à des prestataires techniques intervenant pour le compte de l’éditeur.
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">7. Transferts hors Union européenne</h2>
            <p>
              Si certains prestataires se situent en dehors de l’Union européenne,
              l’éditeur s’assure que des garanties appropriées encadrent ces transferts
              (clauses contractuelles types, encadrement légal).
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">8. Vos droits</h2>
            <p>
              Conformément à la réglementation, vous disposez d’un droit d’accès,
              de rectification, d’effacement, de limitation du traitement, d’opposition
              et de portabilité de vos données.
            </p>
            <p>
              Vous pouvez également définir des directives relatives au sort de vos données
              après votre décès. Pour exercer ces droits, vous pouvez contacter l’éditeur
              aux coordonnées indiquées ci‑dessus.
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">9. Cookies et traceurs</h2>
            <p>
              Des cookies peuvent être déposés sur votre terminal pour permettre le bon fonctionnement
              du site, mesurer l’audience ou personnaliser certains contenus.
            </p>
            <p>
              Vous pouvez paramétrer votre navigateur pour accepter ou refuser les cookies,
              ou être averti avant leur installation.
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">10. Sécurité des données</h2>
            <p>
              L’éditeur met en œuvre des mesures techniques et organisationnelles appropriées
              pour protéger les données contre la perte, l’accès non autorisé,
              la modification ou la divulgation.
            </p>

            <h2 style="font-size:1.1rem;margin-top:18px;margin-bottom:8px;color:#b6763c;">11. Réclamation auprès de l’autorité de contrôle</h2>
            <p>
              En cas de difficulté liée à la gestion de vos données personnelles,
              vous pouvez introduire une réclamation auprès de l’autorité de contrôle compétente
              (par exemple, la CNIL en France).
            </p>
          </section>
        </main>

        <footer style="text-align:center;font-size:0.8rem;color:#7a6a55;margin-top:10px;">
            &copy; 2026 Pawmenade - Tous droits réservés
        </footer>
      </div>
    </div>

    <!-- ===== MODAL CONDITIONS GÉNÉRALES ===== -->
    <div class="modal-overlay" id="modal-conditions">
      <div class="modal-box" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <button class="modal-close" data-close="conditions">&times;</button>

        <header style="display:flex;align-items:center;gap:16px;margin-bottom:16px;border-bottom:1px solid #eee;padding-bottom:10px;">
          <img src="img/Logo.png"
                alt="Logo Pawmenade"
                style="width:48px;height:48px;object-fit:contain;border-radius:12px;">
          <div>
              <div style="font-weight:700;letter-spacing:0.03em;font-size:1.1rem;">Pawmenade</div>
              <div style="font-size:0.8rem;color:#7a6a55;">Trouvez votre petsitter de confiance</div>
          </div>
        </header>

        <main style="max-width:900px;margin:0 auto 10px;">
          <section style="background-color:#ffffff;border-radius:14px;box-shadow:0 8px 20px rgba(0,0,0,0.05);padding:28px 26px 32px;">
            <h1 style="font-size:1.6rem;margin:0 0 8px;color:#b6763c;">Conditions générales d'utilisation</h1>
            <p style="margin:0 0 24px;font-size:0.95rem;color:#7a6a55;">
              Ces conditions encadrent l'utilisation de la plateforme Pawmenade,
              qui met en relation des propriétaires d'animaux et des petsitters.
            </p>
            <p style="font-size:0.8rem;color:#8a7c65;margin-bottom:20px;">
              Dernière mise à jour : 09/01/2026
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">1. Objet</h2>
            <p style="margin:0 0 8px;font-size:0.95rem;">
              Pawmenade est une plateforme de mise en relation entre des propriétaires
              d'animaux de compagnie et des personnes proposant des services de garde,
              de promenade ou de visite à domicile.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">2. Acceptation des CGU</h2>
            <p>
              En créant un compte ou en utilisant le site, l'utilisateur (propriétaire ou petsitter)
              accepte pleinement les présentes conditions générales d'utilisation.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">3. Accès au site</h2>
            <p>
              Le site est accessible gratuitement aux utilisateurs disposant d'un accès Internet.
              Les coûts de connexion restent à la charge de l'utilisateur.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">4. Comptes propriétaires et petsitters</h2>
            <p>
              Chaque utilisateur s'engage à fournir des informations exactes lors de la création de son profil
              (description, localisation, informations sur l'animal ou sur les services proposés).
            </p>
            <p>
              Les petsitters garantissent disposer des capacités nécessaires pour s’occuper des animaux
              qui leur sont confiés.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">5. Utilisation du service</h2>
            <p>
              Il est interdit d'utiliser la plateforme pour diffuser des contenus illicites, trompeurs,
              violents ou contraires au bien-être animal.
            </p>
            <p>
              Les utilisateurs s'engagent à respecter les horaires, tarifs et conditions convenus
              au moment de la réservation.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">6. Propriété intellectuelle</h2>
            <p>
              La marque Pawmenade, le logo, la charte graphique et les contenus du site restent la propriété
              de l'éditeur ou de ses partenaires.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">7. Données personnelles</h2>
            <p>
              Certaines données personnelles (profil, messages, informations sur l’animal)
              sont collectées pour assurer le bon fonctionnement de la plateforme,
              conformément à la politique de confidentialité.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">8. Cookies</h2>
            <p>
              Des cookies peuvent être utilisés pour sécuriser les connexions,
              mémoriser vos préférences et mesurer la fréquentation des annonces.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">9. Responsabilité</h2>
            <p>
              Pawmenade fournit un outil de mise en relation mais n'est pas partie au contrat
              conclu entre le propriétaire et le petsitter. Chaque utilisateur reste responsable
              du respect de ses engagements.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">10. Comportement et bien-être animal</h2>
            <p>
              Les propriétaires s'engagent à fournir des informations exactes sur la santé,
              le caractère et les besoins spécifiques de leur animal.
            </p>
            <p>
              Les petsitters s'engagent à traiter les animaux avec bienveillance
              et à respecter les consignes données.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">11. Modification des CGU</h2>
            <p>
              Pawmenade peut modifier les présentes conditions à tout moment.
              La version en ligne au moment de la consultation est la seule applicable.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">12. Droit applicable</h2>
            <p>
              Les présentes CGU sont soumises au droit français.
            </p>
          </section>
        </main>

        <footer style="text-align:center;font-size:0.8rem;color:#7a6a55;margin-top:10px;">
            &copy; 2026 Pawmenade - Tous droits réservés
        </footer>
      </div>
    </div>

    <!-- ===== MODAL MENTIONS LÉGALES ===== -->
    <div class="modal-overlay" id="modal-mentions">
      <div class="modal-box" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <button class="modal-close" data-close="mentions">&times;</button>

        <header style="display:flex;align-items:center;gap:16px;margin-bottom:16px;border-bottom:1px solid #eee;padding-bottom:10px;">
          <img src="img/Logo.png"
                alt="Logo Pawmenade"
                style="width:48px;height:48px;object-fit:contain;border-radius:12px;">
          <div>
              <div style="font-weight:700;letter-spacing:0.03em;font-size:1.1rem;">Pawmenade</div>
              <div style="font-size:0.8rem;color:#7a6a55;">Trouvez votre petsitter de confiance</div>
          </div>
        </header>

        <main style="max-width:900px;margin:0 auto 10px;">
          <section style="background-color:#ffffff;border-radius:14px;box-shadow:0 8px 20px rgba(0,0,0,0.05);padding:28px 26px 32px;">
            <h1 style="font-size:1.6rem;margin:0 0 8px;color:#b6763c;">Mentions légales</h1>
            <p style="margin:0 0 24px;font-size:0.95rem;color:#7a6a55;">
              Ces informations permettent d’identifier l’éditeur de la plateforme de petsitting Pawmenade et son hébergeur.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">1. Éditeur du site</h2>
            <p style="margin:0 0 10px;font-size:0.95rem;">
              Dénomination : Pawmenade SAS / micro‑entreprise, etc.<br>
              Adresse du siège : Adresse complète<br>
              Téléphone : Numéro de téléphone<br>
              Adresse email : Email de contact pour le support<br>
              Immatriculation : SIREN / SIRET / RCS<br>
              Directeur de la publication : Nom, prénom
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">2. Hébergeur</h2>
            <p style="margin:0 0 10px;font-size:0.95rem;">
              Hébergeur : Nom de l’hébergeur (ex. OVHcloud, o2switch...)<br>
              Adresse : Adresse de l’hébergeur<br>
              Téléphone : Numéro de l’hébergeur
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">3. Activité</h2>
            <p style="margin:0 0 10px;font-size:0.95rem;">
              Pawmenade est une plateforme de mise en relation entre propriétaires d’animaux et petsitters indépendants
              proposant des services de garde, de promenade ou de visite.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">4. Propriété intellectuelle</h2>
            <p style="margin:0 0 10px;font-size:0.95rem;">
              La structure générale du site, la marque Pawmenade, les éléments graphiques et les textes
              sont protégés par la législation en vigueur sur la propriété intellectuelle.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">5. Responsabilité</h2>
            <p style="margin:0 0 10px;font-size:0.95rem;">
              L’éditeur ne peut être tenu responsable des dommages résultant de l’utilisation du site
              ou de la mise en relation entre utilisateurs, ceux‑ci restant seuls responsables
              des accords conclus entre eux.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">6. Données personnelles</h2>
            <p style="margin:0 0 10px;font-size:0.95rem;">
              Les informations concernant la collecte et le traitement des données des utilisateurs
              sont détaillées dans la politique de confidentialité de Pawmenade.
            </p>

            <h2 style="font-size:1.1rem;margin-top:22px;margin-bottom:8px;color:#b6763c;">7. Droit applicable</h2>
            <p style="margin:0;font-size:0.95rem;">
              Les présentes mentions légales sont soumises au droit français.
            </p>
          </section>
        </main>

        <footer style="text-align:center;font-size:0.8rem;color:#7a6a55;margin-top:10px;">
            &copy; 2026 Pawmenade - Tous droits réservés
        </footer>
      </div>
    </div>

    <!-- MODAL CONTACT -->
    <div class="modal-overlay" id="modal-contact">
        <div class="modal-box" style="max-width: 500px;">
            <button class="modal-close" data-close="contact">&times;</button>
            <h2>Contact Pawmenade</h2>

            <p style="margin-bottom: 10px;">
                <strong>WebProductor</strong><br>
                <a href="https://www.google.com/maps/search/?api=1&query=28+Rue+Notre-Dame-des-Champs+75006+Paris" 
                    target="_blank" style="color: #644834;">
                    28 Rue Notre-Dame-des-Champs, 75006 Paris
                </a>
            </p>

            <p style="margin-bottom: 10px;">
                Téléphone : <a href="tel:0123456789" style="color: #644834;">01 23 45 67 89</a><br>
                Email : <a href="mailto:pawmenadeoficiel@gmail.com" style="color: #644834;">pawmenadeoficiel@gmail.com</a>
            </p>

            <p style="font-size: 0.95rem; color: #555; margin-top: 15px;">
                N'hésitez pas à nous contacter si vous rencontrez le moindre problème 
                auquel notre FAQ ne répond pas. Notre équipe fera de son mieux pour vous aider.
            </p>
        </div>
    </div>

    <!-- MODAL SERVICES -->
    <div class="modal-overlay" id="modal-services">
    <div class="modal-box" style="max-width: 700px; max-height: 90vh; overflow-y: auto;">
        <button class="modal-close" data-close="services">&times;</button>
        <h2>Nos Services</h2>

        <div style="background: #f8f5f0; padding: 20px; border-radius: 12px; margin: 15px 0;">
        <p style="font-size: 1rem; line-height: 1.6; color: #555; margin-bottom: 20px;">
            Pawmenade vous propose une gamme complète de services pour le bien-être de vos animaux de compagnie. 
            Nos solutions s'adaptent à tous vos besoins, que ce soit pour une simple promenade ou une garde complète.
        </p>
        </div>

        <div style="display: grid; gap: 20px; margin-bottom: 20px;">
        
        <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #00c7a0; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 10px; color: #a06a43;">🦮 Promenades</h3>
            <p style="margin: 0 0 8px; color: #555;">
            Une balade quotidienne ou ponctuelle pour votre chien. Nos petsitters sortent vos compagnons 30min, 1h ou plus selon vos besoins.
            </p>
            <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 0.95rem;">
            <li>Durée flexible (30min à 2h)</li>
            <li>Parcs et zones adaptées</li>
            <li>Rapport détaillé après chaque sortie</li>
            <li>Tarif : 12-25€/h</li>
            </ul>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #a06a43; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 10px; color: #a06a43;">🏠 Garde à Domicile</h3>
            <p style="margin: 0 0 8px; color: #555;">
            Votre animal reste chez vous dans son environnement familier. Idéal pour les chats, chiens âgés ou animaux craintifs.
            </p>
            <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 0.95rem;">
            <li>Garde complète (jour/nuit)</li>
            <li>Alimentation, câlins, jeux</li>
            <li>Photos/vidéos régulières</li>
            <li>Tarif : 25-60€/jour</li>
            </ul>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #00c7a0; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 10px; color: #a06a43;">⚡ Visites Rapides</h3>
            <p style="margin: 0 0 8px; color: #555;">
            Passage express pour nourrir, donner les médicaments ou vérifier que tout va bien. Parfait pour les absences courtes.
            </p>
            <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 0.95rem;">
            <li>15-30 minutes</li>
            <li>2 à 4 visites/jour</li>
            <li>Rapport après chaque visite</li>
            <li>Tarif : 10-18€/visite</li>
            </ul>
        </div>

        <div style="background: white; padding: 20px; border-radius: 12px; border-left: 4px solid #a06a43; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 10px; color: #a06a43;">🐱 Garde en Pension</h3>
            <p style="margin: 0 0 8px; color: #555;">
            Hébergement chez un petsitter qualifié. Environnement familial pour vos animaux pendant vos absences prolongées.
            </p>
            <ul style="margin: 0; padding-left: 20px; color: #666; font-size: 0.95rem;">
            <li>Chez le petsitter</li>
            <li>Maximum 2 animaux/pension</li>
            <li>Promenades incluses</li>
            <li>Tarif : 35-70€/jour</li>
            </ul>
        </div>

        </div>

        <p style="text-align: center; font-size: 0.9rem; color: #888; margin-top: 10px;">
        Tous nos services sont assurés et vérifiés. Paiement sécurisé garanti.
        </p>
      </div>
    </div>


    <script>
        // ===== SLIDER PRIX =====
        const slider = document.getElementById("priceRange");
        const output = document.getElementById("priceOutput");
        if (slider && output) {
            output.textContent = slider.value + " €";
            slider.oninput = function () {
            output.textContent = this.value + " €";
        };
      }

        const pageContent = document.getElementById('page-content');
        const modalLogin = document.getElementById('modal-login');
        const modalSignup = document.getElementById('modal-signup');
        const modalFaq = document.getElementById('modal-faq');
        const modalConf = document.getElementById('modal-confidentialite');
        const modalCond = document.getElementById('modal-conditions');
        const modalMentions = document.getElementById('modal-mentions');
        const modalContact = document.getElementById('modal-contact');
        const modalServices = document.getElementById('modal-services');


        const openLoginBtn = document.getElementById('open-login');
        const openSignupBtn = document.getElementById('open-signup');
        const openSignupCta = document.getElementById('open-signup-cta');
        const openSignupNav = document.getElementById('open-signup-nav');
        const openFaqBtn = document.getElementById('open-faq');

        const toSignupLink = document.getElementById('to-signup');
        const toLoginLink = document.getElementById('to-login');

        const openConfLink = document.getElementById('open-confidentialite-link');
        const openConfFooter = document.getElementById('open-confidentialite-footer');
        const openCondFooter = document.getElementById('open-conditions-footer');
        const openCondFooterBottom = document.getElementById('open-conditions-footer-bottom');
        const openMentionsLink = document.getElementById('open-mentions-link');
        const openContactFooter = document.getElementById('open-contact-footer');
        const openServicesFooter = document.getElementById('open-services-footer');

        function openModal(type) {
            // fermer tous les modals
            if (modalLogin) modalLogin.classList.remove('active');
            if (modalSignup) modalSignup.classList.remove('active');
            if (modalFaq) modalFaq.classList.remove('active');
            if (modalConf) modalConf.classList.remove('active');
            if (modalCond) modalCond.classList.remove('active');
            if (modalMentions) modalMentions.classList.remove('active');
            if (modalContact) modalContact.classList.remove('active');
            if (modalServices) modalServices.classList.remove('active');

            if (type === 'login' && modalLogin) {
                modalLogin.classList.add('active');
            } else if (type === 'signup' && modalSignup) {
                modalSignup.classList.add('active');
            } else if (type === 'faq' && modalFaq) {
                modalFaq.classList.add('active');
            } else if (type === 'confidentialite' && modalConf) {
                modalConf.classList.add('active');
            } else if (type === 'conditions' && modalCond) {
                modalCond.classList.add('active');
            } else if (type === 'mentions' && modalMentions) {
                modalMentions.classList.add('active');
            } else if (type === 'contact' && modalContact) {
                modalContact.classList.add('active');
            } else if (type === 'services' && modalServices) {
                modalServices.classList.add('active');
            }

            if (pageContent) pageContent.classList.add('blurred');
        }

        function closeAllModals() {
        if (modalLogin) modalLogin.classList.remove('active');
        if (modalSignup) modalSignup.classList.remove('active');
        if (modalFaq) modalFaq.classList.remove('active');
        if (modalConf) modalConf.classList.remove('active');
        if (modalCond) modalCond.classList.remove('active');
        if (modalMentions) modalMentions.classList.remove('active');
        if (modalContact) modalContact.classList.remove('active');
        if (modalServices) modalServices.classList.remove('active');
        if (pageContent) pageContent.classList.remove('blurred');
        }

        // Écouteurs pour chaque bouton
        if (openLoginBtn) openLoginBtn.addEventListener('click', () => openModal('login'));
        if (openSignupBtn) openSignupBtn.addEventListener('click', () => openModal('signup'));
        if (openSignupCta) openSignupCta.addEventListener('click', () => openModal('signup'));
        if (openSignupNav) {
        openSignupNav.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('signup');
        });
        }
        if (openFaqBtn) {
        openFaqBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('faq');
        });
        }

        if (toSignupLink) {
        toSignupLink.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('signup');
        });
        }
        if (toLoginLink) {
        toLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('login');
        });
        }

        // Modales légales
        if (openConfLink) {
        openConfLink.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('confidentialite');
        });
        }
        if (openConfFooter) {
        openConfFooter.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('confidentialite');
        });
        }
        if (openCondFooter) {
        openCondFooter.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('conditions');
        });
        }
        if (openCondFooterBottom) {
        openCondFooterBottom.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('conditions');
        });
        }
        if (openMentionsLink) {
        openMentionsLink.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('mentions');
        });
        }
        if (openContactFooter) {
        openContactFooter.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('contact');
        });
        }
        if (openServicesFooter) {
        openServicesFooter.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('services');
        });
        }

        // Fermeture des modales (bouton X)
        document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', closeAllModals);
        });

        // Fermeture au clic sur le fond
        [modalLogin, modalSignup, modalFaq, modalConf, modalCond, modalMentions, modalContact, modalServices].forEach(modal => {
        if (!modal) return;
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
            closeAllModals();
            }
        });
        });

       // --- 1. CONNEXION
const loginForm = document.querySelector('#modal-login form');

if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;

        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        try {
            const response = await fetch('connexion_action.php', {
                method: 'POST',
                body: formData
            });

            // On vérifie si la réponse est bien du JSON
            const result = await response.json();

            if (result.success) {
                // On enregistre les données REÇUES DU SERVEUR
                localStorage.setItem('pawmenadeLoggedIn', 'true');
                localStorage.setItem('pawmenadeUser', JSON.stringify({
                    prenom: result.prenom,
                    nom: result.nom
                }));

                alert('Connexion réussie !');
                closeAllModals();
                // On rafraîchit la page pour mettre à jour le header
                window.location.reload(); 
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error("Erreur détaillée :", error);
            alert("Erreur de communication avec le serveur.");
        }
    });
}

// --- 2. AFFICHAGE DU HEADER
function afficherUtilisateurConnecte() {
    const loggedIn = localStorage.getItem('pawmenadeLoggedIn') === 'true';
    const stored = localStorage.getItem('pawmenadeUser');
    const actions = document.getElementById('header-actions');

    if (!actions) return;

    if (loggedIn && stored) {
        const user = JSON.parse(stored);
        actions.innerHTML = `
            <span>Bonjour ${user.prenom || 'Utilisateur'}</span>
            <button class="signin" id="logout-btn">Se déconnecter</button>
        `;

        document.getElementById('logout-btn').addEventListener('click', () => {
            localStorage.removeItem('pawmenadeLoggedIn');
            localStorage.removeItem('pawmenadeUser');
            window.location.href = 'deconnexion.php';
        });
    }
}

document.addEventListener('DOMContentLoaded', afficherUtilisateurConnecte);
        // --- DONNÉES FAKE DES PROPRIÉTAIRES ---
        const proprietaires = {
        monique: {
            id: 'monique',
            nomComplet: 'Monique Dupont',
            ville: 'Paris 11e',
            photo: 'img/image 11.png',
            note: 4.8,
            email: 'monique.dupont@example.com',
            telephone: '06 12 34 56 78',
            commentaires: [
            { auteur: 'Julie', texte: 'Toujours ponctuelle, ses animaux sont adorables.', note: 5 },
            { auteur: 'Paul', texte: 'Communication très facile, maison propre.', note: 4.5 }
            ],
            annonces: [
            { titre: 'Milo petit chien affectueux', description: 'Besoin de personne de confiance pour faire des promenades avec Milo.', details: 'Prix fixe à la durée de la balade - besoin surtout les samedis.' },
            { titre: 'Voltaire chien plein d\'énergie', description: 'Cherche un petsitter pour venir garder Voltaire jeudi de 13h à 23h car je suis déplacement.', details: 'Jeudi 13h-23h - 50€ la journée (discutable).' }
            ]
        },
        hugo: {
            id: 'hugo',
            nomComplet: 'Hugo Martin',
            ville: 'Issy-les-Moulineaux',
            photo: 'img/image 12.png',
            note: 4.6,
            email: 'hugo.martin@example.com',
            telephone: '06 98 76 54 32',
            commentaires: [
            { auteur: 'Camille', texte: 'Missy est adorable, propriétaire sérieux.', note: 4.5 }
            ],
            annonces: [
            { titre: 'Naya jeune chienne joueuse', description: 'Promenades régulières en fin de journée.', details: '15€/promenade - horaires flexibles.' },
            { titre: 'Missy chat d\'appartement', description: 'Besoin de visites quotidiennes nourriture et câlins.', details: '12€/visite - période flexible.' }
            ]
        },
        sam: {
            id: 'sam',
            nomComplet: 'Sam Leroy',
            ville: 'Paris 2e',
            photo: 'img/image 13.png',
            note: 4.9,
            email: 'sam.leroy@example.com',
            telephone: '07 11 22 33 44',
            commentaires: [
            { auteur: 'Léa', texte: 'Thor est un amour, tout s\'est très bien passé.', note: 5 },
            { auteur: 'Nina', texte: 'Explications claires, tout est bien organisé.', note: 4.8 }
            ],
            annonces: [
            { titre: 'Sam chien joueur', description: 'Garde pendant mes vacances.', details: '46€/jour du 1er au 8 mars.' },
            { titre: 'Oslo jeune chat calme', description: 'Chat très doux et affectueux. Disponible ce weekend pour être garder à domicile.', details: '25€/jour que les week-end.' },
            { titre: 'Thor chien calme', description: 'Chien calme qui ne sort pas de sa maison, il faut juste lui donner à manger.', details: '25€/jour - dates flexibles.' }
            ]
        }
        };

        // LOGIQUE D'AFFICHAGE DU PROFIL
        const sectionProfil = document.getElementById('profil-proprietaire');
        const profilPhoto = document.getElementById('profil-photo');
        const profilNom = document.getElementById('profil-nom');
        const profilVille = document.getElementById('profil-ville');
        const profilNote = document.getElementById('profil-note');
        const profilContact = document.getElementById('profil-contact');
        const profilCommentairesListe = document.getElementById('profil-commentaires-liste');
        const profilAnnoncesListe = document.getElementById('profil-annonces-liste');
        const btnRetourProfil = document.getElementById('btn-retour-profil');

        function afficherProfilProprietaire(id) {
        const p = proprietaires[id];
        if (!p) {
            alert('Profil introuvable (maquette).');
            return;
        }

        profilPhoto.src = p.photo;
        profilPhoto.alt = p.nomComplet;
        profilNom.textContent = p.nomComplet;
        profilVille.textContent = p.ville;
        profilNote.textContent = `Note moyenne ${p.note.toFixed(1)}`;
        profilContact.innerHTML = `
            <a href="mailto:${p.email}">${p.email}</a><br>
            <a href="tel:${p.telephone.replace(/\s/g, '')}">${p.telephone}</a>
        `;

        profilCommentairesListe.innerHTML = '';
        p.commentaires.forEach(c => {
            const li = document.createElement('li');
            li.textContent = `${c.auteur} (${c.note}/5): ${c.texte}`;
            profilCommentairesListe.appendChild(li);
        });

        profilAnnoncesListe.innerHTML = '';
        p.annonces.forEach(a => {
            const div = document.createElement('div');
            div.className = 'carte-annonce';
            div.innerHTML = `
            <h4>${a.titre}</h4>
            <p>${a.description}</p>
            <p style="color: var(--gray);">${a.details}</p>
            `;
            profilAnnoncesListe.appendChild(div);
        });

        sectionProfil.style.display = 'block';
        sectionProfil.scrollIntoView({ behavior: 'smooth' });
        }

        // Boutons "Voir profil"
        document.querySelectorAll('.btn-profil').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const parent = e.target.closest('[data-proprio-id]');
            if (!parent) return;
            const id = parent.getAttribute('data-proprio-id');
            afficherProfilProprietaire(id);
        });
        });

        if (btnRetourProfil) {
        btnRetourProfil.addEventListener('click', () => {
            sectionProfil.style.display = 'none';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        }

        document.addEventListener('DOMContentLoaded', afficherUtilisateurConnecte());
    </script>
</body>
</html>
