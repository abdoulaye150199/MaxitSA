<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaxItSA - Connexion</title>
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
<body class="min-h-screen flex flex-col items-center justify-center bg-[#f8f6f3]">
    <main class="w-full max-w-md mx-auto p-6">
        <?php if (isset($ContentForLayout)) echo $ContentForLayout; ?>
    </main>
</body>
</html>
