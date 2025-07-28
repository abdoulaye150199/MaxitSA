    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-10 text-center text-gray-800 tracking-wide">FAIRE UN PAYEMENT</h1>
        
        <!-- Services de paiement -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 hover:scale-105 border border-gray-100">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Woyofal</h2>
                <img src="/images/uploads/icones/woyofal.svg" alt="Woyofal" class="w-12 h-12 mb-2">
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 hover:scale-105 border border-gray-100">
                <h2 class="text-xl font-bold mb-4 text-gray-800">BOUTIQUE</h2>
                <img src="/images/uploads/icones/boutique.svg" alt="Boutique" class="w-12 h-12 mb-2">
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 hover:scale-105 border border-gray-100">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Pass internet</h2>
                <img src="/images/uploads/icones/internet.svg" alt="Pass internet" class="w-12 h-12 mb-2">
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 hover:scale-105 border border-gray-100">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Voir Plus</h2>
                <img src="/images/uploads/icones/plus.svg" alt="Voir plus" class="w-12 h-12 mb-2">
            </div>
        </div>
        
        <!-- Formulaire de paiement -->
        <div class="bg-white p-10 rounded-2xl shadow-xl max-w-2xl mx-auto border border-gray-100">
            <div class="space-y-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                        VEUILLEZ CHOISIR UN PAIEMENT
                    </label>
                    <select class="w-full border border-gray-300 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent transition-all duration-300 text-gray-700 shadow-sm">
                        <option value="">Sélectionnez un type de paiement</option>
                        <option value="woyofal">Woyofal</option>
                        <option value="boutique">Boutique</option>
                        <option value="pass_internet">Pass Internet</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                        MONTANT
                    </label>
                    <div class="relative shadow-sm">
                        <input 
                            type="number"
                            name="montant"
                            placeholder="Entrez le montant"
                            class="w-full border border-gray-300 rounded-xl px-4 py-4 pr-20 focus:outline-none focus:ring-2 focus:ring-[#d4b896] focus:border-transparent transition-all duration-300 text-gray-700 placeholder-gray-400"
                            min="100"
                        >
                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium bg-white px-2">
                            FCFA
                        </span>
                    </div>
                </div>
                
                <button 
                    type="submit"
                    class="w-full bg-[#d4b896] text-white font-bold py-5 rounded-xl hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-[#d4b896] focus:ring-opacity-50 uppercase tracking-wide text-lg"
                >
                    ENVOYER
                </button>
            </div>
        </div>
    </div>