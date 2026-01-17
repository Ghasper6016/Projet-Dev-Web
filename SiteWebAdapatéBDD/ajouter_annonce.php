<?php
require_once 'AnnonceModel.php';

// 1️⃣ Vérifier que le formulaire a été soumis
if (!isset($_POST['localisation'])) {
    die("Formulaire non soumis");
}

// 2️⃣ Récupération des données du formulaire
$localisation = trim($_POST['localisation']);
$animal = $_POST['animal'] ?? null;

// ATTENTION : dans ton HTML, le name contient un accent
$activite = $_POST['activité'] ?? null;

$dateDebut = $_POST['date1'];
$dateFin = $_POST['date2'];

// Nettoyage du prix (ex: "10,00€" → "10.00")
$prix = str_replace(['€', ','], ['', '.'], $_POST['prix']);

$telephone = $_POST['numerodetelephone'] ?? null;

// ATTENTION : name avec accent
$precision = $_POST['précision'] ?? null;

// 3️⃣ ID utilisateur (à adapter selon ton système de connexion)
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté");
}

$utilisateurId = $_SESSION['user_id'];

// 4️⃣ Appel au Modèle
$result = AnnonceModel::create(
    $utilisateurId,
    $localisation,
    $animal,
    $activite,
    $dateDebut,
    $dateFin,
    (float)$prix,
    $telephone,
    $precision
);

// 5️⃣ Gestion du résultat
if ($result) {
    header("Location: resultat_annonce.php?status=success");
} else {
    header("Location: resultat_annonce.php?status=error");
}
exit;

