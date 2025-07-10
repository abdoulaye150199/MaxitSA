<form method="post" action="/sign" enctype="multipart/form-data">
    <div class="flex flex-col items-center mt-10">
        <div class="flex flex-col items-center mb-8">
            <div class="rounded-full bg-custom-brown w-36 h-36 flex items-center justify-center mb-8">
                <span class="text-white text-xl font-bold">MaxItSA</span>
            </div>
            <h1 class="text-2xl font-bold mb-8 text-center">BIENVENUE SUR VOTRE APPLICATION MAXIT</h1>
        </div>
        <div class="flex gap-6 mb-4 w-[1000px]">
            <div class="flex flex-col w-1/2">
                <label class="text-custom-brown font-bold mb-1">PRENOM(S) *</label>
                <input type="text" name="prenom" placeholder="Entrz votre Prénom(s)" class="border-2 border-custom-border rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border" >
                <label class="text-custom-brown font-bold mb-1">ADRESSE *</label>
                <input type="text" name="adresse" placeholder="Entrz votre Adresse" class="border-2 border-custom-border rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border" >
                <label class="text-custom-brown font-bold mb-1">NUMERO D’IDENTITÉ *</label>
                <input type="text" name="numero_carte_identite" placeholder="Entrz le numéro de votre carte d’identité" class="border-2 border-custom-border rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border" >
            </div>
            <div class="flex flex-col w-1/2">
                <label class="text-custom-brown font-bold mb-1">NOM *</label>
                <input type="text" name="nom" placeholder="Entrz votre nom" class="border-2 border-custom-border rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border" >
                <label class="text-custom-brown font-bold mb-1">TÉLÉPHONE*</label>
                <input type="text" name="telephone" placeholder="Entrz votre numéro téléphone" class="border-2 border-custom-border rounded-lg px-6 py-4 mb-4 text-custom-brown placeholder-custom-border" >
            </div>
        </div>
        <div class="mb-4 w-[1000px]">
            <label class="text-custom-brown font-bold mb-1">Veuillez Techarger votre carte d’identité *</label>
            <div class="flex gap-6 mt-2">
                <div class="flex flex-col items-center border-2 border-dashed border-custom-border rounded-lg w-1/2 py-6">
                    <span class="mb-2">RECTO</span>
                    <input type="file" name="photo_identite_recto" accept="image/*" >
                </div>
                <div class="flex flex-col items-center border-2 border-dashed border-custom-border rounded-lg w-1/2 py-6">
                    <span class="mb-2">VERSO</span>
                    <input type="file" name="photo_identite_verso" accept="image/*" >
                </div>
            </div>
        </div>
        <button
            type="submit"
            class="bg-custom-brown text-white font-bold rounded-lg px-6 py-4 w-[500px] text-lg mb-4"
        >
            S’inscrire
        </button>
        <div class="text-right w-[1000px]">
            <span class="text-black">j'ai déjà un compte !</span>
            <a href="/login" class="text-custom-brown">Se Connecter</a>
        </div>
    </div>
</form>