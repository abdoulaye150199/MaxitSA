<div class="min-h-screen flex flex-col items-center justify-center bg-white relative">
    <!-- Logo -->
    <div class="w-32 h-32 bg-custom-brown rounded-full flex items-center justify-center mx-auto mb-10">
        <span class="text-white text-2xl font-semibold">MaxItSA</span>
    </div>
    
    <!-- Titre -->
    <h1 class="text-center text-black text-2xl font-bold mb-10 tracking-wide">
        Créez votre code
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-full max-w-lg">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $field => $message): ?>
                    <li><?= htmlspecialchars($message) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Formulaire -->
    <form class="space-y-8 w-full max-w-lg" method="post" action="/code-secret">
        <!-- Champ Code Secret -->
        <div class="relative">
            <label class="absolute -top-3 left-4 bg-white px-2 text-custom-brown text-sm font-medium">
                CRÉER UN CODE
            </label>
            <input 
                type="password" 
                name="code_secret"
                value="<?= htmlspecialchars($_POST['code_secret'] ?? '') ?>"
                class="w-full border-2 <?= isset($errors['code_secret']) ? 'border-red-500' : 'border-custom-border' ?> rounded-xl bg-custom-light px-4 py-4 text-custom-brown placeholder-custom-border focus:outline-none focus:border-custom-brown transition-colors"
                placeholder="CRÉER VOTRE CODE SECRET"
                maxlength="4"
                pattern="[0-9]{4}"
                required
            >
        </div>
        
        <!-- Bouton Créer -->
        <button 
            type="submit" 
            class="w-full bg-custom-brown text-white py-4 rounded-xl text-lg font-semibold hover:bg-opacity-90 transition-all duration-300 mt-8"
        >
            CRÉER
        </button>
    </form>
</div>