<div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-10 text-center text-gray-800 tracking-wide">TRANSFERT D'ARGENT</h1>
        
        <!-- Options de transfert -->
        <div class="flex justify-center gap-6 mb-10">
            <button class="px-8 py-4 bg-white rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-[#d4b896] focus:ring-opacity-50 border border-gray-200">
                <span class="font-semibold text-gray-700">COMPTE SECONDAIRE</span>
            </button>
            <button class="px-8 py-4 bg-[#d4b896] text-white rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 hover:bg-opacity-90">
                <span class="font-semibold">UN BÉNÉFICIAIRE</span>
            </button>
        </div>

        <!-- Formulaire de transfert -->
        <div class="max-w-2xl mx-auto bg-white rounded-2xl p-8 shadow-xl border border-gray-100">
            <form method="post" action="/transfert" class="space-y-8">
                <!-- Numéro bénéficiaire -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wide">
                        VEUILLEZ ENTRER LE NUMERO DU BÉNÉFICIAIRE
                    </label>
                    <div class="flex shadow-sm">
                        <div class="flex items-center px-4 py-4 border border-r-0 rounded-l-xl bg-gray-50 border-gray-300">
                            <img src="/images/uploads/icones/sn-flag.svg" alt="Sénégal" class="w-6 h-4 mr-2">
                            <span class="text-gray-600 font-medium">+221</span>
                        </div>
                        <input 
                            type="tel"
                            name="numero_beneficiaire"
                            placeholder="Numéro de téléphone"
                            class="flex-1 border border-gray-300 rounded-r-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent transition-all duration-300 text-gray-700 placeholder-gray-400"
                            pattern="[0-9]{9}"
                            required
                        >
                    </div>
                </div>

                <!-- Montant -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wide">
                        MONTANT
                    </label>
                    <div class="relative shadow-sm">
                        <input 
                            type="number"
                            name="montant"
                            placeholder="Entrez le montant"
                            class="w-full border border-gray-300 rounded-xl px-4 py-4 pr-20 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent transition-all duration-300 text-gray-700 placeholder-gray-400"
                            min="100"
                            required
                        >
                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium bg-white px-2">
                            FCFA
                        </span>
                    </div>
                </div>

                <!-- Bouton Envoyer -->
                <button 
                    type="submit"
                    class="w-full bg-[#d4b896] text-white font-bold py-5 rounded-xl hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#d4b896] focus:ring-opacity-50 uppercase tracking-wide text-lg"
                >
                    ENVOYER
                </button>
            </form>
        </div>
    </div>