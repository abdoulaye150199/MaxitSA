<div class="max-w-6xl mx-auto">
    <!-- Barre de recherche -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="get" action="/service-commercial/transactions" class="flex flex-wrap items-center gap-4">
            <input type="hidden" name="numero" value="<?= htmlspecialchars($numero) ?>">
            
            <div class="flex-1 min-w-[200px]">
                <input 
                    type="date"
                    name="date"
                    value="<?= htmlspecialchars($dateFilter) ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-custom-brown"
                    placeholder="Rechercher par Date"
                >
            </div>
            
            <div class="min-w-[150px]">
                <select 
                    name="type"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-custom-brown"
                >
                    <option value="">Filtrer par Type</option>
                    <option value="PAIEMENT" <?= $typeFilter === 'PAIEMENT' ? 'selected' : '' ?>>PAIEMENT</option>
                    <option value="DEPOT" <?= $typeFilter === 'DEPOT' ? 'selected'  : '' ?>>DEPOT</option>
                    <option value="RETRAIT" <?= $typeFilter === 'RETRAIT' ? 'selected' : '' ?>>RETRAIT</option>
                </select>
            </div>
            
            <button 
                type="submit"
                class="bg-custom-brown text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition-colors"
            >
                Rechercher
            </button>
        </form>
    </div>

    <!-- Liste des transactions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800">Toutes les Transactions - <?= htmlspecialchars($numero) ?></h3>
            <a 
                href="/service-commercial/search?numero=<?= urlencode($numero) ?>" 
                class="text-custom-brown hover:underline"
            >
                ← Retour aux détails du compte
            </a>
        </div>

        <?php if (!empty($transactions)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-custom-brown text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium">Type-Transactions</th>
                            <th class="px-4 py-3 text-left text-sm font-medium">Bénéfici aire</th>
                            <th class="px-4 py-3 text-right text-sm font-medium">Montant</th>
                            <th class="px-4 py-3 text-center text-sm font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach($transactions as $transaction): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        <?php 
                                            switch($transaction['type_transaction']) {
                                                case 'DEPOT':
                                                    echo 'bg-green-100 text-green-800';
                                                    break;
                                                case 'RETRAIT':
                                                    echo 'bg-red-100 text-red-800';
                                                    break;
                                                case 'PAIEMENT':
                                                    echo 'bg-blue-100 text-blue-800';
                                
                                                    break;
                                                default:
                                                    echo 'bg-gray-100 text-gray-800';
                                            }
                                        ?>">
                                        <?= htmlspecialchars($transaction['type_transaction']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    <?= htmlspecialchars($transaction['numero_telephone']) ?>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium
                                    <?= $transaction['type_transaction'] === 'DEPOT' ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= $transaction['type_transaction'] === 'DEPOT' ? '+' : '-' ?><?= number_format(abs($transaction['montant']), 0, ',', ' ') ?> CFA
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($transaction['date_transaction'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center items-center space-x-2 mt-6 pt-4 border-t">
                    <?php if ($currentPage >  1): ?>
                        <a href="?numero=<?= urlencode($numero) ?>&date=<?= urlencode($dateFilter) ?>&type=<?= urlencode($typeFilter) ?>&page=<?= $currentPage - 1 ?>" 
                           class="px-4 py-2 bg-custom-brown text-white rounded-lg hover:bg-opacity-90">
                            &laquo; Précédent
                        </a>
                    <?php endif; ?>
                    
                    <span class="px-4 py-2">
                        Page <?= $currentPage ?> sur <?= $totalPages ?>
                    </span>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?numero=<?= urlencode($numero)  ?>&date=<?= urlencode($dateFilter) ?>&type=<?= urlencode($typeFilter) ?>&page=<?= $currentPage + 1 ?>" 
                           class="px-4 py-2 bg-custom-brown text-white rounded-lg hover:bg-opacity-90">
                            Suivant &raquo;
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-500">Aucune transaction trouvée pour les critères sélectionnés.</p>
            </div>
        <?php endif; ?>
    </div>
</div>