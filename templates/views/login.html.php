<form method="post" action="/login">
    <div class="flex flex-col items-center mt-10">
        <div class="flex flex-col items-center mb-8">
            <div class="rounded-full bg-custom-brown w-36 h-36 flex items-center justify-center mb-8">
                <span class="text-white text-xl font-bold">MaxItSA</span>
            </div>
            <h1 class="text-2xl font-bold mb-8 text-center">BIENVENUE SUR VOTRE APPLICATION MAXIT</h1>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-[500px]">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-[500px]">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $field => $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <input
            type="password"
            name="code"
            placeholder="ENTREZ VOTRE CODE SECRET"
            value="<?= htmlspecialchars($_POST['code'] ?? '') ?>"
            maxlength="4"
            pattern="[0-9]{4}"
            class="border-2 <?= isset($errors['code']) ? 'border-red-500' : 'border-custom-border' ?> rounded-lg px-6 py-4 mb-6 w-[500px] text-custom-brown placeholder-custom-border text-center text-lg"
        />
        
        <button
            type="submit"
            class="bg-custom-brown text-white font-bold rounded-lg px-6 py-4 w-[500px] text-lg mb-4 hover:bg-opacity-90 transition-all duration-300"
        >
            SE CONNECTER
        </button>
        
        <div class="text-right w-[500px]">
            <span class="text-black">j'ai pas de compte !</span>
            <a href="/sign" class="text-custom-brown hover:underline">S'inscrire</a>
        </div>
    </div>
</form>