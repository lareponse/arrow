<?php
extract($io ?? []);
$user = whoami();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?: 'Copro Academy Admin') ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <meta name="robots" content="noindex,nofollow">
</head>
<body class="admin">
    <header class="admin-header">
        <div class="admin-nav">
            <a href="/admin" class="logo">Copro Academy</a>
            <nav>
                <a href="/admin/article/list">Articles</a>
                <a href="/admin/event/list">Événements</a>
                <a href="/admin/training/list">Formations</a>
                <a href="/admin/trainer/list">Formateurs</a>
                <a href="/admin/contact/list">Contact</a>
            </nav>
        </div>
        <div class="admin-user">
            <span><?= htmlspecialchars($user) ?></span>
            <a href="/admin/logout">Déconnexion</a>
        </div>
    </header>
    <main class="admin-main">
        <?= $main ?? ''; ?>
    </main>
    <script nonce="<?= csp_nonce() ?>">
        // Admin UI interactions
        document.addEventListener('click', e => {
            if (e.target.matches('[data-confirm]')) {
                if (!confirm(e.target.dataset.confirm)) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>

</html>