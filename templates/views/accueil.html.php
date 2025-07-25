<div class="h-[calc(92vh-60px)] flex flex-col flex-1 overflow-hidden">
    <!-- Actions rapides -->
    <div class="flex flex-col md:flex-row gap-6 mb-2">
        <div class="flex-1 bg-white rounded-xl shadow p-4">
            <h2 class="text-xl font-bold mb-4">Action rapide</h2>
            <div class="flex flex-col gap-3">
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-3 rounded-lg font-semibold">
                    Transfert
                    <img src="/public/" alt="Transfert" class="w-6 h-6" />
                </button>
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-3 rounded-lg font-semibold">
                    Paiement
                </button>
                <button class="flex items-center justify-between bg-[#232323] text-white px-6 py-3 rounded-lg font-semibold">
                    Forfait Sakanal
                </button>
            </div>
        </div>
        <!-- Solde principal -->
        <div class="w-full md:w-80 bg-[#232323] rounded-xl shadow p-4 flex flex-col justify-between text-white">
            <div class="flex items-center justify-between mb-4">
                <span class="bg-[#d4b896] text-white px-3 py-1 rounded-lg">Principal</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold">
                     <?php 
                        $solde = isset($compte) && $compte ? number_format($compte->getSolde(), 0, ',', ' ') : '0';
                        echo $solde;
                    ?> FCFA</span>
            </div>
            <a href="#" class="text-[#d4b896] mt-4 block">Voir l'historique &rarr;</a>
        </div>
    </div>

    <!-- Historique des transactions -->
    <div class="h-[calc(76vh-60px)] bg-white rounded-xl shadow p-1 overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Historique Des Transactions</h2>
            <a href="/listetransaction" class="text-[#d4b896]">Voir Plus →</a>
        </div>
        
        <div class="overflow-y-auto scrollbar-hide flex-1">
            <table class="w-full">
                <thead class="bg-[#d4b896] text-white sticky top-0">
                    <tr>
                        <th class="py-3 px-4 text-left">Type-Transactions</th>
                        <th class="py-3 px-4 text-left">Bénéficiaire</th>
                        <th class="py-3 px-4 text-right">Montant</th>
                        <th class="py-3 px-4 text-center">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($latestTransactions) && !empty($latestTransactions)): ?>
                        <?php foreach($latestTransactions as $transaction): ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4">
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
                                        ?>">
                                        <?= htmlspecialchars($transaction['type_transaction']) ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <?= htmlspecialchars($transaction['numero_telephone'] ?? '') ?>
                                </td>
                                <td class="py-3 px-4 text-right font-medium 
                                    <?php 
                                    switch($transaction['type_transaction']) {
                                        case 'DEPOT':
                                            echo 'text-green-600';
                                            break;
                                        case 'RETRAIT':
                                            echo 'text-red-600';
                                            break;
                                        case 'PAIEMENT':
                                            echo 'text-blue-600';
                                            break;
                                        default:
                                            echo 'text-gray-600';
                                    }
                                    ?>">
                                    <?= number_format($transaction['montant'], 0, ',', ' ') ?> FCFA
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <?= date('d/m/Y H:i', strtotime($transaction['date_transaction'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">
                                Aucune transaction disponible
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<style>
    /* Masquer la barre de défilement tout en gardant la fonctionnalité */
    .scrollbar-hide {
        -ms-overflow-style: none;  /* Pour Internet Explorer et Edge */
        scrollbar-width: none;     /* Pour Firefox */
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;            /* Pour Chrome, Safari et Opera */
    }
</style>