<div class="flex flex-col items-center">
    <!-- Logo -->
    <div class="rounded-full bg-[#c4a676] w-32 h-32 flex items-center justify-center mb-8">
        <span class="text-white text-2xl font-bold">MaxItSA</span>
    </div>

    <!-- Titre -->
    <h1 class="text-2xl font-bold text-black mb-8 text-center">
        BIENVENUE SUR VOTRE APPLICATION MAXIT
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-[500px]">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $field => $message): ?>
                    <li><?= htmlspecialchars($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/login" class="flex flex-col items-center w-full">
        <!-- Champ de code -->
        <input
            type="password"
            name="code"
            placeholder="ENTREZ VOTRE CODE SECRET"
            maxlength="4"
            pattern="[0-9]{4}"
            class="border-2 border-custom-border rounded-lg px-6 py-4 mb-6 w-[500px] text-center"
        />

        <!-- Bouton de connexion -->
        <button
            type="submit"
            class="bg-[#c4a676] text-white font-bold rounded-lg px-6 py-4 w-[500px] mb-6"
        >
            SE CONNECTER
        </button>

        <!-- Lien d'inscription -->
        <div class="text-center">
            <span>j'ai pas de compte ! </span>
            <a href="/sign" class="text-[#c4a676] hover:underline">S'inscrire</a>
        </div>
    </form>
</div>