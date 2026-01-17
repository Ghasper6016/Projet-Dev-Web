<?php include 'menu_lateral.php'; ?>
<script src="messagerie.js" defer></script>
<?php
session_start();
require_once 'Database.php';
$mysqli = Database::getConnection();


// ID de l'utilisateur connecté
$monId = $_SESSION['user_id'] ?? 1;

// ID de l'autre participant
$autreId = intval($_GET['user'] ?? 0);
if (!$autreId) { echo "Utilisateur non trouvé."; exit; }

// Récupérer les messages
$sql = "SELECT m.*, u1.prenom AS expediteurPrenom, u1.nom AS expediteurNom
        FROM message m
        JOIN utilisateur u1 ON m.expediteur_id = u1.id
        WHERE (m.expediteur_id = ? AND m.destinataire_id = ?)
           OR (m.expediteur_id = ? AND m.destinataire_id = ?)
        ORDER BY m.date_envoi ASC";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiii", $monId, $autreId, $autreId, $monId);
$stmt->execute();
$result = $stmt->get_result();
$messages = $result->fetch_all(MYSQLI_ASSOC);

// Récupérer le nom de l'autre utilisateur
$sqlUser = "SELECT prenom, nom FROM utilisateur WHERE id = ?";
$stmtUser = $mysqli->prepare($sqlUser);
$stmtUser->bind_param("i", $autreId);
$stmtUser->execute();
$autreUser = $stmtUser->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Conversation avec <?= htmlspecialchars($autreUser['prenom'] . ' ' . $autreUser['nom']) ?></title>
<style>
body { font-family: Arial; background: #F4F5DC; margin:0; padding:0; }
.container { width: 600px; margin: 40px auto; background:white; padding:20px; border-radius:10px; }
#chat { max-height:400px; overflow-y:auto; padding:10px; border:1px solid #ccc; border-radius:10px; background:#f9f9f9; }
.message { padding:10px; border-radius:10px; margin-bottom:10px; max-width:80%; }
.message.moi { background:#987154; color:white; margin-left:auto; text-align:right; }
.message.autre { background:#ddd; color:black; margin-right:auto; text-align:left; }
.message p { margin:0; }
form { display:flex; gap:10px; margin-top:20px; }
input[type=text] { flex:1; padding:10px; border-radius:5px; border:1px solid #ccc; }
button { padding:10px 20px; border:none; background:#987154; color:white; border-radius:5px; cursor:pointer; }
button:hover { background:#826148; }
</style>
</head>
<body>
<div class="container">
    <h2>Conversation avec <?= htmlspecialchars($autreUser['prenom'] . ' ' . $autreUser['nom']) ?></h2>

    <!-- Messages -->
    <div id="chat">
        <?php foreach($messages as $msg): ?>
        <div class="message <?= $msg['expediteur_id'] == $monId ? 'moi' : 'autre' ?>">
            <p><strong><?= $msg['expediteur_id'] == $monId ? 'Moi' : htmlspecialchars($msg['expediteurPrenom'] . ' ' . $msg['expediteurNom']) ?></strong></p>
            <p><?= htmlspecialchars($msg['contenu']) ?></p>
            <small><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></small>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Formulaire d'envoi -->
    <form id="formMessage">
        <input type="text" id="messageText" placeholder="Écrire un message..." required>
        <input type="hidden" id="destinataireId" value="<?= $autreId ?>">
        <button type="submit">Envoyer</button>
    </form>
</div>

<script>
const form = document.getElementById('formMessage');
const chat = document.getElementById('chat');

// Scroll initial en bas
chat.scrollTop = chat.scrollHeight;

form.addEventListener('submit', function(e){
    e.preventDefault();
    const destinataireId = document.getElementById('destinataireId').value;
    const contenu = document.getElementById('messageText').value.trim();
    if(!contenu) return;

    fetch('envoyer_message.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `destinataire_id=${destinataireId}&contenu=${encodeURIComponent(contenu)}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const div = document.createElement('div');
            div.className = 'message moi';
            div.innerHTML = `<p><strong>Moi</strong></p><p>${contenu}</p><small>Maintenant</small>`;
            chat.appendChild(div);
            chat.scrollTop = chat.scrollHeight;
            document.getElementById('messageText').value = '';
        } else {
            alert('Erreur : ' + data.message);
        }
    })
    .catch(err => console.error(err));
});
</script>
</body>
</html>
