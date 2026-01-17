<script src="messagerie.js" defer></script>
<?php
$messages = $messages ?? [];
?>
<?php
$nbMessagesNonLus = $nbMessagesNonLus ?? 0;
?>

<!-- ===== HEADER ===== -->
<header class="topbar">
     <span class="menu-btn" onclick="toggleMenu()">☰</span>
    <div class="menu-container">

        <div class="menu" id="menu">
            <a href="inscription.php">Accueil</a>
            <a href="publication_annonce.php">Trouver un Petsitter</a>
            <a href="#">Mon profil</a>
            <a href="messagerie.php">Messagerie</a>
        </div>
    </div>
</header>
<style>
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: white;
    border-bottom: 1px solid #ddd;
    position: relative;
}

.menu-container {
    position: relative;
}

.menu-btn {
    position: fixed; /* 🔑 reste toujours en haut à droite */
    top: 35px;
    right: 400px;
    font-size: 24px;
    cursor: pointer;
    z-index: 1001; /* au-dessus du contenu */
}

.menu {
    position: fixed; /* menu aussi en haut à droite */
    top: 35px;
    right: 150px;
    width: 220px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    display: none;
    flex-direction: column;
    z-index: 1000;
}

.menu.show {
    display: flex;
}



.menu a {
    padding: 12px 15px;
    text-decoration: none;
    color: #333;
}

.menu a:hover {
    background: #f2f2f2;
}
</style>

<script>
function toggleMenu() {
    const menu = document.getElementById('menu');
    menu.classList.toggle('show');
}

// Optionnel : fermer menu si clic à l'extérieur
document.addEventListener('click', function(e){
    const menu = document.getElementById('menu');
    const btn = document.querySelector('.menu-btn');

    if(!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.classList.remove('show');
    }
});
</script>