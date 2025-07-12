<form method="post" action="/sign" enctype="multipart/form-data">
    <div class="flex flex-col items-center mt-10">
        <div class="flex flex-col items-center mb-8">
            <div class="rounded-full bg-custom-brown w-36 h-36 flex items-center justify-center mb-8">
                <span class="text-white text-xl font-bold">MaxItSA</span>
            </div>
            <h1 class="text-2xl font-bold mb-8 text-center">BIENVENUE SUR VOTRE APPLICATION MAXIT</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 w-[1000px]">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $field => $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="flex gap-6 mb-4 w-[1000px]">
            <div class="flex flex-col w-1/2">
                <label class="text-custom-brown font-bold mb-1">PRENOM(S) *</label>
                <input 
                    type="text" 
                    name="prenom" 
                    placeholder="Entrez votre Prénom(s)" 
                    value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                    class="border-2 <?= isset($errors['prenom']) ? 'border-red-500' : 'border-custom-border' ?> rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border"
                >
                
                <label class="text-custom-brown font-bold mb-1">ADRESSE *</label>
                <input 
                    type="text" 
                    name="adresse" 
                    placeholder="Entrez votre Adresse" 
                    value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>"
                    class="border-2 <?= isset($errors['adresse']) ? 'border-red-500' : 'border-custom-border' ?> rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border"
                >
                
                <label class="text-custom-brown font-bold mb-1">NUMERO D'IDENTITÉ *</label>
                <input 
                    type="text" 
                    name="numero_carte_identite" 
                    placeholder="Entrez le numéro de votre carte d'identité" 
                    value="<?= htmlspecialchars($_POST['numero_carte_identite'] ?? '') ?>"
                    class="border-2 <?= isset($errors['numero_carte_identite']) ? 'border-red-500' : 'border-custom-border' ?> rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border"
                >
            </div>
            <div class="flex flex-col w-1/2">
                <label class="text-custom-brown font-bold mb-1">NOM *</label>
                <input 
                    type="text" 
                    name="nom" 
                    placeholder="Entrez votre nom" 
                    value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                    class="border-2 <?= isset($errors['nom']) ? 'border-red-500' : 'border-custom-border' ?> rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border"
                >
                
                <label class="text-custom-brown font-bold mb-1">TÉLÉPHONE*</label>
                <input 
                    type="text" 
                    name="numero" 
                    placeholder="Entrez votre numéro téléphone" 
                    value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>"
                    class="border-2 <?= isset($errors['numero']) ? 'border-red-500' : 'border-custom-border' ?> rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border"
                >
            </div>
        </div>
        
        <div class="mb-4 w-[1000px]">
            <label class="text-custom-brown font-bold mb-1">Veuillez Télécharger votre carte d'identité *</label>
            <div class="flex gap-6 mt-2">
                <div class="flex flex-col items-center border-2 border-dashed border-custom-border rounded-lg w-1/2 py-6">
                    <span class="mb-2">RECTO</span>
                    <input type="file" name="photo_identite_recto" accept="image/*">
                </div>
                <div class="flex flex-col items-center border-2 border-dashed border-custom-border rounded-lg w-1/2 py-6">
                    <span class="mb-2">VERSO</span>
                    <input type="file" name="photo_identite_verso" accept="image/*">
                </div>
            </div>
        </div>
        
        <button
            type="submit"
            class="bg-custom-brown text-white font-bold rounded-lg px-6 py-4 w-[500px] text-lg mb-4 hover:bg-opacity-90 transition-all duration-300"
        >
            S'inscrire
        </button>
        
        <div class="text-right w-[1000px]">
            <span class="text-black">j'ai déjà un compte !</span>
            <a href="/login" class="text-custom-brown hover:underline">Se Connecter</a>
        </div>
    </div>
</form>