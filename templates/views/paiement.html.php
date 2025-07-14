<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">FAIRE UN PAYEMENT</h1>
    
    <!-- Services de paiement -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow cursor-pointer">
            <h2 class="text-xl font-bold mb-2">Woyofal</h2>
            <!-- <img src="/images/uploads/icones/woyofal.svg" alt="Woyofal" class="w-12 h-12 mb-2"> -->
        </div>

        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow cursor-pointer">
            <h2 class="text-xl font-bold mb-2">BOUTIQUE</h2>
            <!-- <img src="/images/uploads/icones/boutique.svg" alt="Boutique" class="w-12 h-12 mb-2"> -->
        </div>

        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow cursor-pointer">
            <h2 class="text-xl font-bold mb-2">Pass internet</h2>
            <!-- <img src="/images/uploads/icones/internet.svg" alt="Pass internet" class="w-12 h-12 mb-2"> -->
        </div>

        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow cursor-pointer">
            <h2 class="text-xl font-bold mb-2">Voir Plus</h2>
            <!-- <img src="/images/uploads/icones/plus.svg" alt="Voir plus" class="w-12 h-12 mb-2"> -->
        </div>
    </div>

    <!-- Formulaire de paiement -->
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    VEUILLEZ CHOISIR UN PAIEMENT
                </label>
                <select class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#d4b896]">
                    <option value="">Sélectionnez un type de paiement</option>
                    <option value="woyofal">Woyofal</option>
                    <option value="boutique">Boutique</option>
                    <option value="pass_internet">Pass Internet</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    MONTANT
                </label>
                <div class="relative">
                    <input 
                        type="number"
                        name="montant"
                        placeholder="Entrez le montant"
                        class="w-full border rounded-lg px-4 py-3 pr-16 focus:outline-none focus:ring-2 focus:ring-[#d4b896]"
                        min="100"
                    >
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                        FCFA
                    </span>
                </div>
            </div>

            <button 
                type="submit"
                class="w-full bg-[#d4b896] text-white font-semibold py-4 rounded-lg hover:bg-opacity-90 transition-all duration-300"
            >
                ENVOYER
            </button>
        </div>
    </div>
</div>