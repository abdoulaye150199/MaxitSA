<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-xl flex flex-col items-center px-4">
        <!-- Logo -->
        <div class="rounded-full bg-[#c4a676] w-32 h-32 flex items-center justify-center mb-8">
            <span class="text-white text-2xl font-bold">MaxItSA</span>
        </div>

        <!-- Loading Spinner - Caché par défaut -->
        <div id="loadingSpinner" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-5 rounded-lg flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#c4a676] mb-3"></div>
                <p class="text-gray-700">Chargement des informations...</p>
            </div>
        </div>

        <!-- Titre -->
        <h1 class="text-2xl font-bold mb-8 text-center">
            BIENVENUE SUR VOTRE APPLICATION MAXIT
        </h1>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-full">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $field => $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/sign" enctype="multipart/form-data" class="w-full space-y-6">
            <!-- Numéro d'identité en première position -->
            <div>
                <label class="block text-[#c4a676] text-sm font-medium mb-2">NUMERO D'IDENTITÉ *</label>
                <input 
                    type="text" 
                    name="numero_carte_identite" 
                    id="numero_carte_identite"
                    placeholder="Entrez les 13 chiffres de votre carte d'identité"
                    class="w-full border border-[#d4b896] rounded-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]"
                    pattern="[0-9]{13}"
                    maxlength="13"
                    required
                    oninput="fetchUserData(this.value)"
                >
            </div>

            <!-- Champs pré-remplis automatiquement -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[#c4a676] text-sm font-medium mb-2">PRENOM(S)</label>
                    <input type="text" name="prenom" id="prenom" readonly class="w-full border border-[#d4b896] rounded-lg px-4 py-3 bg-gray-50">
                </div>
                <div>
                    <label class="block text-[#c4a676] text-sm font-medium mb-2">NOM</label>
                    <input type="text" name="nom" id="nom" readonly class="w-full border border-[#d4b896] rounded-lg px-4 py-3 bg-gray-50">
                </div>
            </div>

            <!-- Adresse modifiable et Téléphone -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[#c4a676] text-sm font-medium mb-2">ADRESSE *</label>
                    <input type="text" name="adresse" id="adresse" required class="w-full border border-[#d4b896] rounded-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]">
                </div>
                <div>
                    <label class="block text-[#c4a676] text-sm font-medium mb-2">TÉLÉPHONE *</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 text-gray-500 bg-gray-50 border border-r-0 border-[#d4b896] rounded-l-lg">
                            +221
                        </span>
                        <input 
                            type="tel" 
                            name="numero" 
                            placeholder="7X XXX XX XX"
                            class="flex-1 border border-[#d4b896] rounded-r-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]"
                            pattern="7[0-9]{8}"
                            maxlength="9"
                            required
                        >
                    </div>
                </div>
            </div>

            <!-- Photos d'identité -->
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[#c4a676] text-sm font-medium mb-2">PHOTO CNI RECTO</label>
                    <div class="relative">
                        <img id="preview_recto" class="w-full h-32 object-cover rounded-lg border border-[#d4b896]" 
                             src="/images/uploads/placeholder.png" alt="CNI Recto">
                        <input type="hidden" name="photo_identite_recto" id="photo_identite_recto">
                    </div>
                </div>
                <div>
                    <label class="block text-[#c4a676] text-sm font-medium mb-2">PHOTO CNI VERSO</label>
                    <div class="relative">
                        <img id="preview_verso" class="w-full h-32 object-cover rounded-lg border border-[#d4b896]" 
                             src="/images/uploads/placeholder.png" alt="CNI Verso">
                        <input type="hidden" name="photo_identite_verso" id="photo_identite_verso">
                    </div>
                </div>
            </div>

            <button 
                type="submit"
                class="w-full bg-[#c4a676] text-white font-medium py-4 rounded-lg hover:bg-opacity-90 transition-all"
            >
                S'inscrire
            </button>

            <div class="text-center">
                <span class="text-gray-600">j'ai déjà un compte ! </span>
                <a href="/login" class="text-[#c4a676] hover:underline">Se Connecter</a>
            </div>
        </form>
    </div>
</div>

<script>
async function fetchUserData(nci) {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const fields = ['prenom', 'nom', 'adresse'];

    if (nci.length === 13) {
        try {
            // Afficher le spinner
            loadingSpinner.classList.remove('hidden');

            const response = await fetch('https://appdaf-g15c.onrender.com/api/citoyens');
            if (!response.ok) throw new Error('Erreur réseau');
            
            const citoyens = await response.json();
            const citoyen = citoyens.data.find(c => c.nci === nci);
            
            if (citoyen) {
                // Remplir les champs texte
                document.getElementById('prenom').value = citoyen.prenom || '';
                document.getElementById('nom').value = citoyen.nom || '';
                document.getElementById('adresse').value = citoyen.lieu_naissance || '';

                // Remplir les champs cachés des photos et afficher les images
                document.getElementById('photo_identite_recto').value = citoyen.url_carte_recto || '';
                document.getElementById('photo_identite_verso').value = citoyen.url_carte_verso || '';
                
                // Afficher les images
                document.getElementById('preview_recto').src = citoyen.url_carte_recto || '/images/uploads/placeholder.png';
                document.getElementById('preview_verso').src = citoyen.url_carte_verso || '/images/uploads/placeholder.png';
            } else {
                alert('Numéro de carte d\'identité non trouvé');
                // Réinitialiser tous les champs
                fields.forEach(field => document.getElementById(field).value = '');
                document.getElementById('photo_identite_recto').value = '';
                document.getElementById('photo_identite_verso').value = '';
                document.getElementById('preview_recto').src = '/images/uploads/placeholder.png';
                document.getElementById('preview_verso').src = '/images/uploads/placeholder.png';
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la récupération des données');
            // Réinitialiser tous les champs
            fields.forEach(field => document.getElementById(field).value = '');
            document.getElementById('photo_identite_recto').value = '';
            document.getElementById('photo_identite_verso').value = '';
            document.getElementById('preview_recto').src = '/images/uploads/placeholder.png';
            document.getElementById('preview_verso').src = '/images/uploads/placeholder.png';
        } finally {
            loadingSpinner.classList.add('hidden');
        }
    } else {
        // Réinitialiser tous les champs si le NCI n'a pas 13 chiffres
        fields.forEach(field => document.getElementById(field).value = '');
        document.getElementById('photo_identite_recto').value = '';
        document.getElementById('photo_identite_verso').value = '';
        document.getElementById('preview_recto').src = '/images/uploads/placeholder.png';
        document.getElementById('preview_verso').src = '/images/uploads/placeholder.png';
    }
}

function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
