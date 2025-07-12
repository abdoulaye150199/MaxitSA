<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaxItSA - Solde</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8f6f3] min-h-screen">
    <?php include __DIR__ . '/base.sidebar.html.layout.php'; ?>
    <div class="ml-20">
        <?php include __DIR__ . '/base.header.html.layout.php'; ?>
        <main class="p-8">
            <?php if (isset($ContentForLayout)) echo $ContentForLayout; ?>
        </main>
    </div>
</body>
</html>