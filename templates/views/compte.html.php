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
                        <span class="text-gray-500">+221</span>
                    </div>
                    <input 
                        type="tel"
                        name="numero_telephone"
                        placeholder="Numéro de téléphone"
                        value="<?= htmlspecialchars($_POST['numero_telephone'] ?? '') ?>"
                        class="flex-1 border rounded-r-lg px-4 py-3"
                        pattern="[0-9]{9}"
                        required
                    >
                </div>
            </div>

            <!-- Montant initial (optionnel) -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    MONTANT INITIAL (Optionnel)
                </label>
                <div class="relative">
                    <input 
                        type="number"
                        name="montant_initial"
                        placeholder="Montant initial (0 FCFA par défaut)"
                        value="<?= htmlspecialchars($_POST['montant_initial'] ?? '') ?>"
                        class="w-full border rounded-lg px-4 py-3 pr-16"
                        min="0"
                    >
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">FCFA</span>
                </div>
            </div>

            <!-- Bouton Créer -->
            <button 
                type="submit"
                class="w-full bg-[#d4b896] text-white py-3 rounded-lg"
            >
                CRÉER
            </button>
        </form>
    </div>
</div>