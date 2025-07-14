<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Commercial - MaxItSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-brown': '#c4a676',
                        'custom-light': '#f8f6f3',
                        'custom-border': '#d4b896'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-custom-light min-h-screen">
    <!-- Header -->
    <div class="bg-[#232323] text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-custom-brown rounded-lg flex items-center justify-center">
                    <span class="text-white text-xs font-bold">MAXITSA</span>
                </div>
                <h1 class="text-xl font-bold">SERVICE COMMERCIAL</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-custom-brown font-bold">77 291 77 70</span>
                <a href="/logout" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg transition-colors">
                    Déconnexion
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <?php if (isset($ContentForLayout)) echo $ContentForLayout; ?>
    </main>
</body>
</html>