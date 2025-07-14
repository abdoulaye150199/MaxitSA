<div class="max-w-6xl mx-auto">
    <!-- Barre de recherche -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="post" action="/service-commercial/search" class="flex items-center space-x-4">
            <div class="flex-1">
                <input 
                    type="text"
                    name="numero"
                    placeholder="Rechercher un autre compte"
                    value="<?= htmlspecialchars($numero) ?>"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-custom-brown"
                >
            </div>
            <button 
                type="submit"
                class="bg-custom-brown text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition-colors"
            >
                Rechercher
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations du solde -->
        <div class="lg:col-span-1">
            <div class="bg-[#232323] text-white rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-8 h-8 bg-custom-brown rounded-full flex items-center justify-center">
                        <span class="text-xs font-bold">M</span>
                
                    </div>
                    <span class="text-custom-brown text-sm font-medium">SOLDE</span>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold mb-2"><?= number_format($solde, 0, ',', ' ') ?>FCFA</div>
                    <div class="text-sm text-gray-300">
                        <?= htmlspecialchars($user->getPrenom() . ' ' . $user->getNom()) ?>
                    </div>
                    <div class="text-sm text-custom-brown">
                        <?= htmlspecialchars($numero) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des transactions -->
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Historique Des Transactions</h3>
                    <a 
                        href="/service-commercial/transactions?numero=<?= urlencode($numero) ?>" 
                        class="text-custom-brown hover:underline flex items-center space-x-1"
                    >
                        <span>Voir Plus</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-custom-brown text-white">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium">Type-Transactions</th>
                                <th class="px-4 py-3 text-left text-sm font-medium">Bénéficiaire</th>
                                <th class="px-4 py-3 text-right text-sm font-medium">Montant</th>
                                <th class="px-4 py-3 text-center text-sm font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($transactions)): ?>
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
                                                            echo 'bg-gray-100  text-gray-800';
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
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                        Aucune transaction trouvée
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>