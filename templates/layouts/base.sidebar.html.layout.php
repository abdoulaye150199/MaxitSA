<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="fixed left-0 top-0 h-full w-20 bg-[#232323] flex flex-col items-center py-6">
        <div class="mb-8">
            <img src="/images/uploads/icones/image 1.svg" alt="Orange Money" class="w-8 h-8" />
        </div>
        
        <div class="flex-1 flex flex-col items-center justify-center gap-8">
            <a href="/accueil" class="nav-link relative p-3 rounded-xl hover:bg-[#d4b896] hover:bg-opacity-10 transition-all duration-300 group">
                <img src="/images/uploads/icones/iconsax-card-pos.svg" alt="Cartes" class="w-8 h-8 transition-all duration-300 group-hover:scale-110" />
            </a>
            
            <a href="/listetransaction" class="nav-link relative p-3 rounded-xl hover:bg-[#d4b896] hover:bg-opacity-10 transition-all duration-300 group">
                <img src="/images/uploads/icones/iconsax-convert-card.svg" alt="transaction" class="w-8 h-8 transition-all duration-300 group-hover:scale-110" />
            </a>
            
            <a href="/transfert" class="nav-link relative p-3 rounded-xl hover:bg-[#d4b896] hover:bg-opacity-10 transition-all duration-300 group">
                <img src="/images/uploads/icones/iconsax-arrow-transfer-01.svg" alt="Transfert" class="w-8 h-8 transition-all duration-300 group-hover:scale-110" />
            </a>
            
            <a href="/paiement" id="paiementLink" class="nav-link relative p-3 rounded-xl hover:bg-[#d4b896] hover:bg-opacity-10 transition-all duration-300 group">
                <img src="/images/uploads/icones/Component.svg" alt="Paiement" class="w-8 h-8 transition-all duration-300 group-hover:scale-110" />
            </a>
        </div>
        
        <div class="mt-auto mb-2">
            <a href="/logout" class="group">
                <img src="/images/uploads/icones/iconsax-logout-02.svg" alt="Déconnexion" class="w-8 h-8 hover:scale-110 transition-all duration-300" />
            </a>
        </div>
    </div>

    <script>
        // Fonction pour définir l'onglet actif
        function setActiveTab() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                // Retirer les classes active de tous les liens
                link.classList.remove('bg-[#d4b896]', 'shadow-lg');
                link.querySelector('img').classList.remove('brightness-0', 'invert');
                
                // Vérifier si le href correspond au chemin actuel
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('bg-[#d4b896]', 'shadow-lg');
                    link.querySelector('img').classList.add('brightness-0', 'invert');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', setActiveTab);
        
        setTimeout(() => {
            const paiementLink = document.querySelector('[href="/"]');
            paiementLink.classList.add('bg-[#d4b896]', 'shadow-lg');
            paiementLink.querySelector('img').classList.add('brightness-0', 'invert');
        }, 1000);
    </script>
</body>
</html>