<div class="flex flex-col gap-8">
    <!-- Actions rapides -->
    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex-1 bg-white rounded-xl shadow p-8">
            <h2 class="text-xl font-bold mb-6">Action rapide</h2>
            <div class="flex flex-col gap-4">
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-4 rounded-lg font-semibold">
                    Transfert
                    <!-- <img src="/assets/ic-transfert.svg" alt="Transfert" class="w-6 h-6" /> -->
                </button>
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-4 rounded-lg font-semibold">
                    Paiement
                    <!-- <img src="/assets/ic-paiement.svg" alt="Paiement" class="w-6 h-6" /> -->
                </button>
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-4 rounded-lg font-semibold">
                    Forfait Sakanal
                    <!-- <img src="/assets/ic-forfait.svg" alt="Forfait" class="w-6 h-6" /> -->
                </button>
            </div>
        </div>
        <!-- Solde principal -->
        <div class="w-full md:w-80 bg-[#232323] rounded-xl shadow p-8 flex flex-col justify-between text-white">
            <div class="flex items-center justify-between mb-4">
                <!-- <img src="/assets/logo-solde.png" alt="Logo" class="w-10 h-10" /> -->
                <span class="bg-[#d4b896] text-white px-3 py-1 rounded-lg">Principal</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold">1500FCFA</span>
                <!-- <img src="/assets/ic-eye.svg" alt="Voir" class="w-6 h-6" /> -->
            </div>
            <a href="#" class="text-[#d4b896] mt-4 block">Voir l'historique &rarr;</a>
        </div>
    </div>
  
    
    </div>
   

    <h2 class="font-bold text-lg mb-4">Historique Des Transactions</h2>
     <div class="text-right">
        <a href="/transactions" class="text-[#d4b896] font-bold hover:underline">Voir Plus &rarr;</a>
    </div>
    <table class="w-full rounded-xl overflow-hidden shadow mb-4">
        <thead class="bg-[#d4b896] text-white">
            <tr>
                <th class="py-2 px-4 text-left">Type-Transactions</th>
                <th class="py-2 px-4 text-left">Bénéficiaire</th>
                <th class="py-2 px-4 text-left">Montant</th>
                <th class="py-2 px-4 text-left">Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $tx): ?>
                <tr class="border-b">
                    <td class="py-2 px-4 align-middle"><?= htmlspecialchars($tx['type_transaction']) ?></td>
                    <td class="py-2 px-4 align-middle"><?= htmlspecialchars($tx['user_id'] ?? 'N/A') ?></td>
                    <td class="py-2 px-4 align-middle"><?= number_format($tx['montant'], 0, ',', ' ') ?> CFA</td>
                    <td class="py-2 px-4 align-middle"><?= date('d/m/Y à H:i', strtotime($tx['date'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
   
</div>