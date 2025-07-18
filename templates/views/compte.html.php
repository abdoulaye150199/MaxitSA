<div class="min-h-screen bg-white p-8">
    <div class="max-w-2xl mx-auto">
        <!-- Titre -->
        <h1 class="text-2xl font-bold mb-8 text-center">CREATION D'UN COMPTE SECONDAIRE</h1>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $field => $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/compte/create" class="space-y-6">
            <!-- Numéro de téléphone -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    NUMÉRO DU COMPTE SECONDAIRE
                </label>
                <div class="flex">
                    <div class="flex items-center px-4 py-3 border rounded-l-lg bg-gray-50">
                        <img src="<?= asset('images/uploads/icones/sn-flag.svg') ?>" alt="Sénégal" class="w-6 h-4 mr-2">
                        <span class="text-gray-500">+221</span>
                    </div>
                    <input 
                        type="tel"
                        name="numero_telephone"
                        placeholder="Entrez le numéro de téléphone"
                        value="<?= htmlspecialchars($_POST['numero_telephone'] ?? '') ?>"
                        class="flex-1 border rounded-r-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent"
                        pattern="[0-9]{9}"
                        required
                    >
                </div>
            </div>

            <!-- Code Secret -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    CODE SECRET
                </label>
                <input 
                    type="password"
                    name="code_secret"
                    placeholder="Créez un code secret à 4 chiffres"
                    class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent"
                    maxlength="4"
                    pattern="[0-9]{4}"
                    required
                >
            </div>

            <!-- Montant initial -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    MONTANT INITIAL
                </label>
                <div class="relative">
                    <input 
                        type="number"
                        name="montant_initial"
                        placeholder="Entrez le montant initial"
                        value="<?= htmlspecialchars($_POST['montant_initial'] ?? '') ?>"
                        class="w-full border rounded-lg px-4 py-3 pr-16 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent"
                        min="500"
                        required
                    >
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                        FCFA
                    </span>
                </div>
                <p class="text-sm text-gray-500">Minimum: 500 FCFA</p>
            </div>

            <!-- Bouton Créer -->
            <button 
                type="submit"
                class="w-full bg-[#d4b896] text-white font-semibold py-4 rounded-lg hover:bg-opacity-90 transition-all duration-300"
            >
                CRÉER
            </button>
        </form>
    </div>
</div>