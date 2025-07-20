<div class="flex items-center justify-between pl-20 pr-8 py-2 border-b border-gray-200 bg-white">
    <span class="font-bold text-xl">Abdoulaye Diallo</span>
    <div class="flex-1 flex justify-center">
        <div class="w-[50px] h-[50px] bg-[#d4b896] rounded-lg flex items-center justify-center">
            <span class="text-white text-[10px] font-bold">MAXITSA</span>
        </div>
    </div>
    <div class="flex items-center space-x-6 relative">
        <span class="text-[#c4a676] font-bold text-xl">Gerer comptes</span>
        
        <div class="relative">
            <img src="/images/uploads/icones/Frame 14.svg" alt="Dropdown" class="w-6 h-6 cursor-pointer" onclick="toggleDropdown()" />
            <!-- Dropdown Menu -->
            <div id="accountDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                
                <div class="py-2 px-4">
                    <p class="text-gray-700 font-medium mb-2">Gérer mes comptes</p>
                    <a 
                        href="/compte/nouveau" 
                        class="block py-2 px-4 text-sm text-gray-600 hover:bg-gray-100 rounded"
                    >
                        Nouveau Compte
                    </a>
                    <a href="#" class="block py-2 px-4 text-sm text-gray-600 hover:bg-gray-100 rounded">
                        Changer de Compte
                    </a>
                </div>
            </div>
        </div>
        <img src="/images/uploads/icones/material-symbols_search.svg"  alt="Recherche" class="w-6 h-6" />
    </div>
</div>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('accountDropdown');
    dropdown.classList.toggle('hidden');
}

// Fermer le dropdown en cliquant ailleurs sur la page
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('accountDropdown');
    const dropdownButton = document.querySelector('[alt="Dropdown"]');
    
    if (!dropdown.contains(event.target) && event.target !== dropdownButton) {
        dropdown.classList.add('hidden');
    }
});
</script>