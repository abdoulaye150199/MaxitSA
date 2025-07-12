<div class="flex flex-col gap-8">
    <!-- Actions rapides -->
    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex-1 bg-white rounded-xl shadow p-8">
            <h2 class="text-xl font-bold mb-6">Action rapide</h2>
            <div class="flex flex-col gap-4">
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-4 rounded-lg font-semibold">
                    Transfert
                    <img src="/public/" alt="Transfert" class="w-6 h-6" />
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

    <!-- Historique des transactions -->
    <div class="bg-white rounded-xl shadow p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Historique Des Transactions</h2>
            <a href="/historique" class="text-[#d4b896]">Voir Plus →</a>
        </div>
        
        <table class="w-full">
            <thead class="bg-[#d4b896] text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Type-Transactions</th>
                    <th class="py-3 px-4 text-center">Date</th>
                    <th class="py-3 px-4 text-right">Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($latestTransactions) && !empty($latestTransactions)): ?>
                    <?php foreach($latestTransactions as $transaction): ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4"><?= htmlspecialchars($transaction['type_transaction']) ?></td>
                            <td class="py-3 px-4 text-center">
                                <?= date('d/m/Y à H:i', strtotime($transaction['date'])) ?>
                            </td>
                            <td class="py-3 px-4 text-right <?= $transaction['montant'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                <?= number_format($transaction['montant'], 0, ',', ' ') ?> CFA
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">
                            Aucune transaction disponible
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>