
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-8">Liste des Transactions</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-[#d4b896] text-white">
                <tr>
                    <th class="px-6 py-3 text-left">Type-Transaction</th>
                    <th class="px-6 py-3 text-left">Bénéficiaire</th>
                    <th class="px-6 py-3 text-right">Montant</th>
                    <th class="px-6 py-3 text-center">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($transactions as $transaction): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <?= htmlspecialchars($transaction['type_transaction']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <?= htmlspecialchars($transaction['numero_telephone']) ?>
                        </td>
                        <td class="px-6 py-4 text-right <?= $transaction['montant'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                            <?= number_format(abs($transaction['montant']), 0, ',', ' ') ?> FCFA
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?= date('d/m/Y H:i', strtotime($transaction['date_transaction'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center space-x-2 p-4 border-t">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1 ?>" class="px-4 py-2 bg-[#d4b896] text-white rounded-lg hover:bg-opacity-90">&laquo; Précédent</a>
                <?php endif; ?>
                
                <span class="px-4 py-2">
                    Page <?= $currentPage ?> sur <?= $totalPages ?>
                </span>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1 ?>" class="px-4 py-2 bg-[#d4b896] text-white rounded-lg hover:bg-opacity-90">Suivant &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>