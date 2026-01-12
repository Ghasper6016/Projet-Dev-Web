function tR() {
            var b = document.getElementById('rb');
            if (b.classList.contains('active')) {
                b.classList.remove('active');
                b.textContent = 'Lus ▼';
            } else {
                b.classList.add('active');
                b.textContent = 'Non lus ▼';
            }
        }

        // Ouvrir popup recherche
        function oS() {
            document.getElementById('sp').classList.add('show');
            document.getElementById('si').focus();
        }

        // Fermer popup recherche
        function cS() {
            document.getElementById('sp').classList.remove('show');
            document.getElementById('si').value = '';
        }

        // Rechercher un utilisateur
        function sU() {
            var v = document.getElementById('si').value.toLowerCase().trim();
            
            if (v) {
                var m = document.querySelectorAll('.m');
                var f = false;
                
                m.forEach(function(msg) {
                    var n = msg.querySelector('h3').textContent.toLowerCase();
                    
                    if (n.includes(v)) {
                        msg.style.display = 'flex';
                        f = true;
                    } else {
                        msg.style.display = 'none';
                    }
                });
                
                if (!f) {
                    alert('Aucun utilisateur trouvé avec le nom : ' + v);
                }
                
                cS();
            }
        }

        // Réafficher tous les messages
        function rS() {
            document.querySelectorAll('.m').forEach(function(m) {
                m.style.display = 'flex';
            });
        }

        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cS();
            }
        });

        // Toggle menu services
        function tS() {
            document.getElementById('smenu').classList.toggle('show');
        }

        // Filtrer par service
        function fS() {
            var c = document.querySelectorAll('#smenu input[type="checkbox"]');
            var s = Array.from(c).filter(function(cb) {
                return cb.checked;
            }).map(function(cb) {
                return cb.value;
            });
            
            var m = document.querySelectorAll('.m');
            
            m.forEach(function(msg) {
                var sv = msg.getAttribute('data-service');
                
                if (s.length === 0) {
                    msg.style.display = 'flex';
                } else if (s.includes(sv)) {
                    msg.style.display = 'flex';
                } else {
                    msg.style.display = 'none';
                }
            });
        }

        // Fermer menu si clic ailleurs
        document.addEventListener('click', function(e) {
            var b = document.getElementById('sb');
            var m = document.getElementById('smenu');
            
            if (!b.contains(e.target) && !m.contains(e.target)) {
                m.classList.remove('show');
            }
        });

        // Toggle panneau notifications
        function tN() {
            document.getElementById('np').classList.toggle('show');
        }

        // Toggle menu latéral
        function tM() {
            document.getElementById('sm').classList.toggle('show');
        }

        // Déconnexion
        function lo() {
            alert('Déconnexion en cours...');
        }
