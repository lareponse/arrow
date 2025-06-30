<?php
extract($io ?? []);
$user = auth();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Copro Academy Admin') ?></title>
    <link rel="stylesheet" href="/asset/css/admin/variables.css">
    <link rel="stylesheet" href="/asset/css/admin/base.css">
    <link rel="stylesheet" href="/asset/css/admin/utilities.css">
    <link rel="stylesheet" href="/asset/css/admin/layout.css">
    <link rel="stylesheet" href="/asset/css/admin/components.css">

    <meta name="robots" content="noindex,nofollow">
    <?php if (isset($css)) : ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
    <?php endif; ?>
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
            <span><?= htmlspecialchars(auth()) ?></span>
            <a href="/logout">Déconnexion</a>
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

    <script type="module">
        import slugify from '/asset/js/slug.js';
        document.addEventListener('DOMContentLoaded', () => {

            // auto-generate slug from label input
            const labelInput = document.querySelector('input[name="label"]');
            const slugInput = document.querySelector('input[name="slug"]');
            labelInput.addEventListener('input', () => {
                slugInput.value = slugify(labelInput.value);
            });


            // file upload drag-and-drop
            document.querySelectorAll('.file-drop').forEach(drop => {
                const input = drop.querySelector('input[type="file"]');

                drop.addEventListener('dragover', e => {
                    e.preventDefault();
                    drop.style.borderColor = '#3b82f6';
                });

                drop.addEventListener('dragleave', () => {
                    drop.style.borderColor = '#d1d5db';
                });

                drop.addEventListener('drop', e => {
                    e.preventDefault();
                    drop.style.borderColor = '#d1d5db';
                    input.files = e.dataTransfer.files;
                });
            });
        });
    </script>
</body>

</html>