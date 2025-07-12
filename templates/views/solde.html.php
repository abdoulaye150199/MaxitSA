<div class="flex flex-col gap-8">
    <!-- Actions rapides -->
    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex-1 bg-white rounded-xl shadow p-8">
            <h2 class="text-xl font-bold mb-6">Action rapide</h2>
            <div class="flex flex-col gap-4">
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-4 rounded-lg font-semibold">
                    Transfert
                    <img src="/assets/ic-transfert.svg" alt="Transfert" class="w-6 h-6" />
                </button>
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-4 rounded-lg font-semibold">
                    Paiement
                    <img src="/assets/ic-paiement.svg" alt="Paiement" class="w-6 h-6" />
                </button>
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-4 rounded-lg font-semibold">
                    Forfait Sakanal
                    <img src="/assets/ic-forfait.svg" alt="Forfait" class="w-6 h-6" />
                </button>
            </div>
        </div>
        <!-- Solde principal -->
        <div class="w-full md:w-80 bg-[#232323] rounded-xl shadow p-8 flex flex-col justify-between text-white">
            <div class="flex items-center justify-between mb-4">
                <img src="/assets/logo-solde.png" alt="Logo" class="w-10 h-10" />
                <span class="bg-[#d4b896] text-white px-3 py-1 rounded-lg">Principal</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold">1500FCFA</span>
                <img src="/assets/ic-eye.svg" alt="Voir" class="w-6 h-6" />
            </div>
            <a href="#" class="text-[#d4b896] mt-4 block">Voir l'historique &rarr;</a>
        </div>
    </div>
    <!-- Services -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <img src="/assets/ic-woyofal.svg" alt="Woyofal" class="w-10 h-10 mb-2" />
            <span class="font-semibold">Woyofal</span>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <img src="/assets/ic-retrait.svg" alt="Retrait" class="w-10 h-10 mb-2" />
            <span class="font-semibold">Retrait</span>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <img src="/assets/ic-pass.svg" alt="Pass internet" class="w-10 h-10 mb-2" />
            <span class="font-semibold">Pass internet</span>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <img src="/assets/ic-plus.svg" alt="Voir Plus" class="w-10 h-10 mb-2" />
            <span class="font-semibold">Voir Plus</span>
        </div>
    </div>
    <!-- Dernier envoi -->
    <div class="bg-white rounded-xl shadow p-6 mt-8">
        <h3 class="font-semibold mb-4">Dernier envoi</h3>
        <div class="flex items-center gap-4">
            <img src="/assets/ic-user.svg" alt="User" class="w-10 h-10 rounded-full" />
            <div>
                <div class="font-semibold">Khouss Ngom</div>
                <div class="text-sm text-gray-500">+221778232295</div>
            </div>
            <div class="ml-auto font-bold">FCFA</div>
        </div>
    </div>
    <!-- Bannière -->
    <div class="bg-white rounded-xl shadow p-6 mt-8 flex items-center justify-center">
        <img src="/assets/banner.png" alt="Bannière" class="w-full h-32 object-cover rounded-lg" />
    </div>
</div>