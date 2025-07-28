
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaxItSA - Authentification</title>
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
<body class="min-h-screen flex items-center justify-center bg-[#f8f6f3]">
    <div class="w-full max-w-2xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <?php if (isset($ContentForLayout)) echo $ContentForLayout; ?>
    </div>
</body>
</html>
