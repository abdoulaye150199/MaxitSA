<div class="min-h-screen bg-white p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-8 text-center">MES COMPTES</h1>

        <!-- Liste des comptes -->
        <div class="space-y-4">
            <?php foreach ($comptes as $compte): ?>
                <a 
                    href="/switch-compte/<?= htmlspecialchars($compte['id']) ?>"
                    class="block bg-white rounded-xl border-2 border-[#d4b896] p-6 hover:shadow-lg transition-all duration-300"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-lg font-semibold text-gray-800">
                                Compte <?= $compte['type_compte'] ?>
                            </p>
                            <p class="text-sm text-gray-600">
                                <?= htmlspecialchars($compte['numero_telephone']) ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-[#c4a676]">
                                <?= number_format($compte['solde'], 0, ',', ' ') ?> FCFA
                            </p>
                            <p class="text-sm text-gray-500">
                                <?= $compte['est_principal'] ? 'Principal' : 'Secondaire' ?>
                            </p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>