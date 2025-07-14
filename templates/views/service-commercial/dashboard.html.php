<div class="max-w-4xl mx-auto">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">BIENVENUE SUR VOTRE APPLICATION MAXITSA</h2>
        <p class="text-gray-600">Recherchez un compte client pour consulter ses informations</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 max-w-md mx-auto">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md mx-auto">
        <form method="post" action="/service-commercial/search" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rechercher un compte par numéro
                </label>
                <div class="relative">
                    <input 
                        type="text"
                        name="numero"
                        placeholder="Rechercher un compte par numéro"
                        value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-custom-brown focus:border-transparent"
                        required
                    >
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <button 
                type="submit"
                class="w-full bg-custom-brown text-white font-semibold py-3 rounded-lg hover:bg-opacity-90 transition-all duration-300"
            >
                Rechercher
            </button>
        </form>
    </div>
</div>