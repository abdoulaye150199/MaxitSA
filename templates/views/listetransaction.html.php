<div class="container mx-auto px-4 ">
    <div class="flex justify-between items-center ">
        <h1 class="text-2xl font-bold">Liste des Transactions</h1>
    </div>

    <!-- Formulaire de filtres -->
    <div class="bg-white rounded-lg shadow p-2 mb-2">
        <form method="get" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Filtre par date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rechercher par date
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="date" 
                            placeholder="JJ/MM/AAAA"
                            value="<?= htmlspecialchars($_GET['date'] ?? '') ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#d4b896]"
                            pattern="\d{2}/\d{2}/\d{4}"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Filtre par type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Type de transaction
                    </label>
                    <select 
                        name="type" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#d4b896]" >
                        <option value="">Tous les types</option>
                        <option value="DEPOT" <?= ($_GET['type'] ?? '') === 'DEPOT' ? 'selected' : '' ?>>Dépôt</option>
                        <option value="RETRAIT" <?= ($_GET['type'] ?? '') === 'RETRAIT' ? 'selected' : '' ?>>Retrait</option>
                        <option value="PAIEMENT" <?= ($_GET['type'] ?? '') === 'PAIEMENT' ? 'selected' : '' ?>>Paiement</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex items-end space-x-4">
                    <button 
                        type="submit"
                        class="flex-1 bg-[#d4b896] text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition-colors">
                        Rechercher
                    </button>
                    <?php if (!empty($_GET['date']) || !empty($_GET['type'])): ?>
                        <a 
                            href="?page=1" 
                            class="flex-1 text-center px-6 py-2.5 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors"
                        >
                            Réinitialiser
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Table des transactions -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#d4b896] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-medium">Type-Transaction</th>
                        <th class="px-6 py-4 text-left text-sm font-medium">Bénéficiaire</th>
                        <th class="px-6 py-4 text-right text-sm font-medium">Montant</th>
                        <th class="px-6 py-4 text-center text-sm font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                        ?>"
                                    >
                                        <?= htmlspecialchars($transaction['type_transaction'] ?? '') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($transaction['numero_telephone'] ?? '') ?>
                                </td>
                                <td class="px-6 py-4 text-right <?= $transaction['montant'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                    <?= number_format(abs($transaction['montant']), 0, ',', ' ') ?> FCFA
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?= $transaction['date_transaction'] ? date('d/m/Y H:i', strtotime($transaction['date_transaction'])) : '' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Aucune transaction trouvée
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center gap-4 p-4 border-t border-gray-100">
                <?php if ($currentPage > 1): ?>
                    <a 
                        href="?page=<?= $currentPage - 1 ?><?= !empty($_GET['date']) ? '&date=' . htmlspecialchars($_GET['date']) : '' ?><?= !empty($_GET['type']) ? '&type=' . htmlspecialchars($_GET['type']) : '' ?>" 
                        class="px-4 py-2 bg-[#d4b896] text-white rounded-lg hover:bg-opacity-90"
                    >
                        &laquo; Précédent
                    </a>
                <?php endif; ?>
                
                <span class="px-4 py-2">
                    Page <?= $currentPage ?> sur <?= $totalPages ?>
                </span>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a 
                        href="?page=<?= $currentPage + 1 ?><?= !empty($_GET['date']) ? '&date=' . htmlspecialchars($_GET['date']) : '' ?><?= !empty($_GET['type']) ? '&type=' . htmlspecialchars($_GET['type']) : '' ?>" 
                        class="px-4 py-2 bg-[#d4b896] text-white rounded-lg hover:bg-opacity-90"
                    >
                        Suivant &raquo;
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>