
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création code secret - MaxItSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <!-- Logo -->
        <div class="rounded-full bg-[#c4a676] w-32 h-32 flex items-center justify-center mb-8">
            <span class="text-white text-2xl font-bold">MaxItSA</span>
        </div>

        <!-- Titre -->
        <h1 class="text-2xl font-bold mb-8 text-center">
            BIENVENUE SUR VOTRE APPLICATION MAXIT
        </h1>

        <!-- Message -->
        <p class="text-center text-gray-600 mb-8">
            Créez votre code secret à 4 chiffres pour sécuriser votre compte
        </p>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-full max-w-[500px]">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $field => $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/code-secret" class="w-full max-w-[500px] flex flex-col items-center gap-6">
            <input 
                type="password" 
                name="code_secret"
                placeholder="CREEZ VOTRE CODE SECRET"
                class="border border-[#d4b896] rounded-lg px-6 py-4 w-full text-center text-lg"
                maxlength="4"
                pattern="[0-9]{4}"
                inputmode="numeric"
                autocomplete="new-password"
                required
            >

            <button 
                type="submit"
                class="bg-[#c4a676] text-white font-bold rounded-lg px-6 py-4 w-full hover:bg-opacity-90 transition-all"
            >
                VALIDER
            </button>
        </form>
    </div>
</body>
</html>