
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-xl flex flex-col items-center px-4">
            <!-- Logo -->
            <div class="rounded-full bg-[#c4a676] w-32 h-32 flex items-center justify-center mb-8">
                <span class="text-white text-2xl font-bold">MaxItSA</span>
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

            <form method="post" action="/sign" enctype="multipart/form-data" class="w-full">
                <div class="space-y-6">
                    <!-- Prénom et Nom -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[#c4a676] text-sm font-medium mb-2">PRENOM(S) *</label>
                            <input 
                                type="text" 
                                name="prenom" 
                                placeholder="Entrez votre Prénom(s)"
                                class="w-full border border-[#d4b896] rounded-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-[#c4a676] text-sm font-medium mb-2">NOM *</label>
                            <input 
                                type="text" 
                                name="nom" 
                                placeholder="Entrez votre nom"
                                class="w-full border border-[#d4b896] rounded-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]"
                                required
                            >
                        </div>
                    </div>

                    <!-- Adresse et Téléphone -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[#c4a676] text-sm font-medium mb-2">ADRESSE *</label>
                            <input 
                                type="text" 
                                name="adresse" 
                                placeholder="Entrez votre Adresse"
                                class="w-full border border-[#d4b896] rounded-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-[#c4a676] text-sm font-medium mb-2">TÉLÉPHONE *</label>
                            <input 
                                type="tel" 
                                name="numero" 
                                placeholder="Entrez votre numéro téléphone"
                                class="w-full border border-[#d4b896] rounded-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]"
                                required
                            >
                        </div>
                    </div>

                    <!-- Numéro d'identité -->
                    <div>
                        <label class="block text-[#c4a676] text-sm font-medium mb-2">NUMERO D'IDENTITÉ *</label>
                        <input 
                            type="text" 
                            name="numero_carte_identite" 
                            placeholder="Entrez le numéro de votre carte d'identité"
                            class="w-full border border-[#d4b896] rounded-lg px-4 py-3 focus:outline-none focus:border-[#c4a676]"
                            required
                        >
                    </div>

                    <!-- Upload de documents -->
                    <div>
                        <label class="block text-[#c4a676] text-sm font-medium mb-2">Veuillez Télécharger votre carte d'identité *</label>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="border border-dashed border-[#d4b896] rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-600 mb-2">RECTO</p>
                                <input type="file" name="photo_identite_recto" accept="image/*" class="hidden" id="recto" required>
                                <label for="recto" class="cursor-pointer text-sm text-[#c4a676]">Choisir un fichier</label>
                            </div>
                            <div class="border border-dashed border-[#d4b896] rounded-lg p-4 text-center">
                                <p class="text-sm text-gray-600 mb-2">VERSO</p>
                                <input type="file" name="photo_identite_verso" accept="image/*" class="hidden" id="verso" required>
                                <label for="verso" class="cursor-pointer text-sm text-[#c4a676]">Choisir un fichier</label>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton d'inscription -->
                    <button 
                        type="submit"
                        class="w-full bg-[#c4a676] text-white font-medium py-4 rounded-lg hover:bg-opacity-90 transition-all"
                    >
                        S'inscrire
                    </button>

                    <!-- Lien de connexion -->
                    <div class="text-center">
                        <span class="text-gray-600">j'ai déjà un compte ! </span>
                        <a href="/login" class="text-[#c4a676] hover:underline">Se Connecter</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
