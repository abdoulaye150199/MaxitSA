<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - MaxItSA</title>
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
<body class="bg-white min-h-screen flex items-center justify-center">
    <main class="w-full">
        <?php if (isset($ContentForLayout)) echo $ContentForLayout; ?>
    </main>
</body>
</html>
