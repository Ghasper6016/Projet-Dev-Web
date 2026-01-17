<?php include 'menu_lateral.php'; ?>
<script src="messagerie.js" defer></script>
<?php
require_once 'Database.php';
$mysqli = Database::getConnection();

// === ID de l'utilisateur connecté ===
$monId = 1; // À remplacer par la session ou authentification réelle

// === Récupérer tous les messages pour cet utilisateur ===
$sql = "SELECT m.id, m.contenu, m.service, m.lu, m.date_envoi,
               m.expediteur_id,  -- ajouter ceci
               u.prenom, u.nom
        FROM message m
        JOIN utilisateur u ON m.expediteur_id = u.id
        WHERE m.destinataire_id = ?
        ORDER BY m.date_envoi DESC";


$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $monId);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages - Pawmenade</title>
    <link rel="stylesheet" href="messagerie.css" />
    <script src="messagerie.js" defer></script>
</head>
<body>

<div class="c">

    <!-- ===== ONGLETS ===== -->
    <div class="tabs"><span>Messages</span></div>

    <!-- ===== FILTRES ===== -->
    <div class="f">
        <button class="active" id="rb" onclick="tR()">Non lus ▼</button>
        <button onclick="oS()">Utilisateurs ▼</button>
        <button id="sb" onclick="tS()">Services ▼</button>

        <!-- Menu déroulant services -->
        <div class="sm" id="smenu">
            <label><input type="checkbox" value="promenade" onchange="fS()"> Promenade</label>
            <label><input type="checkbox" value="garde" onchange="fS()"> Garde à domicile</label>
            <label><input type="checkbox" value="visite" onchange="fS()"> Visite</label>
        </div>
    </div>

    <!-- ===== LISTE DES MESSAGES ===== -->
    <div class="msgs">
        <?php foreach($messages as $msg): ?>
        <div class="m <?= $msg['lu'] ? '' : 'unread' ?>" 
     data-service="<?= htmlspecialchars($msg['service']) ?>" 
     data-id="<?= $msg['id'] ?>"
     data-user="<?= $msg['expediteur_id'] ?>"> <!-- ← ici -->
    <img src="https://robohash.org/<?= htmlspecialchars($msg['prenom']) ?>.png?set=set4&size=150x150" alt="<?= htmlspecialchars($msg['prenom']) ?>">
    <div class="mi">
        <h3>
            <?= htmlspecialchars($msg['prenom'] . ' ' . $msg['nom']) ?>
            <span class="sb <?= htmlspecialchars($msg['service']) ?>"><?= htmlspecialchars($msg['service']) ?></span>
        </h3>
        <p><?= htmlspecialchars($msg['contenu']) ?></p>
    </div>
    <div class="mt"><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></div>
</div>

        <?php endforeach; ?>
    </div>

    <!-- ===== PAGINATION ===== -->
    <div class="pg">
        <button>‹</button>
        <button>›</button>
    </div>
</div>

<!-- ===== POPUP RECHERCHE ===== -->
<div class="sp" id="sp">
    <div class="sb2">
        <h3>Rechercher un utilisateur</h3>
        <input type="text" id="si" placeholder="Entrez le nom de l'utilisateur..." onkeypress="if(event.key==='Enter')sU()">
        <button onclick="sU()">Rechercher</button>
        <button class="cancel" onclick="cS();rS()">Annuler</button>
    </div>
</div>

<!-- ===== PANNEAU NOTIFICATIONS ===== -->
<div class="np" id="np">
    <?php foreach(array_slice($messages, 0, 5) as $msg): ?>
    <div class="ni <?= $msg['lu'] ? '' : 'unread' ?>">
        <h4>Nouveau message de <?= htmlspecialchars($msg['prenom']) ?></h4>
        <p><?= htmlspecialchars(substr($msg['contenu'], 0, 50)) ?>...</p>
        <div class="nt"><?= date('H:i', strtotime($msg['date_envoi'])) ?></div>
    </div>
    <?php endforeach; ?>
</div>

<script>
document.querySelectorAll('.m').forEach(msg => {
    msg.addEventListener('click', () => {
        const userId = msg.getAttribute('data-user'); // ajouter data-user="ID_UTILISATEUR" sur chaque div .m
        window.location.href = `conversation.php?user=${userId}`;
    });
});
</script>
</body>
</html>

