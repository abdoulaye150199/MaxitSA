<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8 text-center">TRANSFERT D'ARGENT</h1>
    
    <!-- Options de transfert -->
    <div class="flex justify-center gap-4 mb-8">
        <button class="px-6 py-3 bg-white rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#d4b896]">
            COMPTE SECONDAIRE
        </button>
        <button class="px-6 py-3 bg-[#d4b896] text-white rounded-lg hover:bg-opacity-90">
            UN BÉNÉFICIAIRE
        </button>
    </div>

    <!-- Formulaire de transfert -->
    <div class="max-w-2xl mx-auto bg-white rounded-lg p-8 shadow-lg">
        <form method="post" action="/transfert" class="space-y-6">
            <!-- Numéro bénéficiaire -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    VEUILLEZ ENTRER LE NUMERO DU BÉNÉFICIAIRE
                </label>
                <div class="flex">
                    <div class="flex items-center px-4 py-3 border rounded-l-lg bg-gray-50">
                        <img src="/images/uploads/icones/sn-flag.svg" alt="Sénégal" class="w-6 h-4 mr-2">
                        <span class="text-gray-500">+221</span>
                    </div>
                    <input 
                        type="tel"
                        name="numero_beneficiaire"
                        placeholder="Numéro de téléphone"
                        class="flex-1 border rounded-r-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent"
                        pattern="[0-9]{9}"
                        required
                    >
                </div>
            </div>

            <!-- Montant -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    MONTANT
                </label>
                <div class="relative">
                    <input 
                        type="number"
                        name="montant"
                        placeholder="Entrez le montant"
                        class="w-full border rounded-lg px-4 py-3 pr-16 focus:outline-none focus:ring-2 focus:ring-[#d4b896]"
                        min="100"
                        required
                    >
                    <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                        FCFA
                    </span>
                </div>
            </div>

            <!-- Bouton Envoyer -->
            <button 
                type="submit"
                class="w-full bg-[#d4b896] text-white font-semibold py-4 rounded-lg hover:bg-opacity-90 transition-all duration-300"
            >
                ENVOYER
            </button>
        </form>
    </div>
</div>