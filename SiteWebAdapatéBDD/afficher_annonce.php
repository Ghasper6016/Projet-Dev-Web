<?php include 'menu_lateral.php'; ?>
<script src="messagerie.js" defer></script>
<?php
// Connexion à la base de données
require_once 'Database.php';
$mysqli = Database::getConnection();



// Récupération des filtres
$localisation = $_GET['localisation'] ?? '';
$animal = $_GET['animal'] ?? '';
$date = $_GET['date'] ?? '';
$prixMax = $_GET['prix'] ?? '';

// Construction dynamique de la requête
$sql = "SELECT a.id, a.utilisateur_id, a.localisation, a.animal, a.activite, a.date_debut, a.date_fin, a.prix, a.details, a.date_publication,
               u.prenom, u.nom
        FROM annonce a
        JOIN utilisateur u ON a.utilisateur_id = u.id
        WHERE 1=1";



$params = [];
$types = "";

if ($localisation !== '') {
    $sql .= " AND localisation LIKE ?";
    $params[] = "%$localisation%";
    $types .= "s";
}

if ($animal !== '') {
    $sql .= " AND animal LIKE ?";
    $params[] = "%$animal%";
    $types .= "s";
}

if ($date !== '') {
    $sql .= " AND DATE(date_debut) <= ? AND DATE(date_fin) >= ?";
    $params[] = $date;
    $params[] = $date;
    $types .= "ss";
}


if ($prixMax !== '') {
    $sql .= " AND prix <= ?";
    $params[] = $prixMax;
    $types .= "d";
}

$sql .= " ORDER BY date_publication DESC";

$stmt = $mysqli->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Annonces</title>

<style>
    body {
        background-color: #F4F5DC;
        font-family: Arial;
        font-weight: bold;
        margin: 0;
        padding: 0;
    }

    * {
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    .container {
        width: 1000px;
        margin: 40px auto;
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    h1 {
        text-align: center;
        color: #987154;
        margin-bottom: 30px;
    }

    form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 30px;
        justify-content: center;
    }

    input {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button {
        background-color: #987154;
        color: whitesmoke;
        height: 40px;
        width: 200px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background-color: #826148;
    }

    .annonce {
    border: 1px solid #ddd;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;

    display: flex;            /* Flexbox activé */
    align-items: center;      /* Centre verticalement le contenu */
    gap: 300px;                /* Espace entre le bouton et le reste */
}

    .annonce h2 {
        color: #9d7153;
        margin-top: 0;
    }

    .annonce p {
        font-weight: normal;
        margin: 5px 0;
    }

    .prix {
        font-weight: bold;
        color: #4CAF50;
    }
    form {
    margin-bottom: 40px; /* Espace entre les filtres et les résultats */
}
</style>

</head>

<body>

<div class="container">
    <h1>Consulter les annonces</h1>

<!-- Filtres -->
<form method="get">
    <input type="text" name="localisation" placeholder="Ville" value="<?= htmlspecialchars($localisation) ?>">
    <input type="text" name="animal" placeholder="Animal" value="<?= htmlspecialchars($animal) ?>">
    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
    <input type="number" step="0.01" name="prix" placeholder="Prix max (€)" value="<?= htmlspecialchars($prixMax) ?>">
    <button type="submit">Filtrer</button>
</form>

<!-- Affichage des annonces -->
<?php 
// Récupérer toutes les annonces dans un tableau
$annonces = $result->fetch_all(MYSQLI_ASSOC);

if (!empty($annonces)):
    foreach ($annonces as $annonce):
?>
    <div class="annonce" data-id="<?= $annonce['id'] ?>" data-auteur="<?= $annonce['utilisateur_id'] ?>">
        <div class="annonce-info">
        <h2><?= htmlspecialchars($annonce['activite']) ?> - <?= htmlspecialchars($annonce['animal']) ?></h2>

        <p><strong>Ville :</strong> <?= htmlspecialchars($annonce['localisation']) ?></p>
        <p><strong>Du :</strong> <?= date('d/m/Y H:i', strtotime($annonce['date_debut'])) ?></p>
        <p><strong>Au :</strong> <?= date('d/m/Y H:i', strtotime($annonce['date_fin'])) ?></p>
        <p class="prix"><?= number_format($annonce['prix'], 2, ',', ' ') ?> €</p>

        <?php if (!empty($annonce['details'])): ?>
            <p><strong>Détails :</strong> <?= htmlspecialchars($annonce['details']) ?></p>
        <?php endif; ?>

        <p><em>Publié le <?= date('d/m/Y', strtotime($annonce['date_publication'])) ?> par <?= htmlspecialchars($annonce['prenom'] . ' ' . $annonce['nom']) ?></em></p>
        </div>

        <!-- Bouton "Je suis intéressé" -->
        <button class="interesse-btn" 
                data-auteur="<?= $annonce['utilisateur_id'] ?>" 
                data-annonce="<?= $annonce['id'] ?>">
            Je suis intéressé
        </button>
    </div>
<?php 
    endforeach; 
else: 
?>
    <p>Aucune annonce ne correspond à votre recherche.</p>
<?php endif; ?>
<!-- POPUP ENVOI DE MESSAGE -->
<div class="popup-message" id="popupMessage" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div style="background:white; padding:20px; border-radius:10px; width:400px; max-width:90%;">
        <h3>Envoyer un message</h3>
        <textarea id="messageText" placeholder="Votre message..." style="width:100%; height:100px;"></textarea>
        <input type="hidden" id="destinataireId">
        <input type="hidden" id="annonceId">
        <div style="margin-top:10px; text-align:right;">
            <button onclick="envoyerMessage()">Envoyer</button>
            <button onclick="fermerPopup()">Annuler</button>
        </div>
    </div>
</div>

</body>
</html>
<script>
    // Ouvrir popup au clic sur "Je suis intéressé"
document.querySelectorAll('.interesse-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const auteurId = btn.getAttribute('data-auteur');
        const annonceId = btn.getAttribute('data-annonce');

        document.getElementById('destinataireId').value = auteurId;
        document.getElementById('annonceId').value = annonceId;
        document.getElementById('messageText').value = "Bonjour, je suis intéressé par votre annonce.";

        document.getElementById('popupMessage').style.display = 'flex';
    });
});

// Fermer popup
function fermerPopup() {
    document.getElementById('popupMessage').style.display = 'none';
}
function envoyerMessage() {
    const destinataireId = document.getElementById('destinataireId').value;
    const annonceId = document.getElementById('annonceId').value;
    const contenu = document.getElementById('messageText').value;

    fetch('envoyer_message.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `destinataire_id=${destinataireId}&contenu=${encodeURIComponent(contenu)}&annonce_id=${annonceId}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Message envoyé !');
            fermerPopup();
        } else {
            alert('Erreur : ' + data.message);
        }
    });
}

</script>
