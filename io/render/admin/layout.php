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
                <a href="/admin/article">Articles</a>
                <a href="/admin/event">Événements</a>
                <a href="/admin/training">Formations</a>
                <a href="/admin/trainer">Formateurs</a>
                <a href="/admin/contact">Contact</a>
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


            document.querySelectorAll('.drop-zone').forEach(zone => {
                const input = zone.querySelector('input[type="file"]');
                const label = zone.querySelector('.drop-label span');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
                    zone.addEventListener(e, prevent);
                });

                ['dragenter', 'dragover'].forEach(e => {
                    zone.addEventListener(e, () => zone.classList.add('drag-over'));
                });

                ['dragleave', 'drop'].forEach(e => {
                    zone.addEventListener(e, () => zone.classList.remove('drag-over'));
                });

                zone.addEventListener('drop', handleDrop);
                input.addEventListener('change', e => upload(e.target.files[0]));

                function prevent(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function handleDrop(e) {
                    upload(e.dataTransfer.files[0]);
                }

                function upload(file) {
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('avatar', file);

                    label.textContent = 'Uploading...';

                    fetch(zone.dataset.upload, {
                            method: 'POST',
                            body: formData
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                label.innerHTML = `<img src="${data.url}" alt="Uploaded" style="max-width:100px">`;
                                label.parentElement.appendChild(
                                    Object.assign(document.createElement('input'), {
                                        type: 'hidden',
                                        name: labelInput.name,
                                        value: data.url
                                    })
                                );
                            } else {
                                label.textContent = 'Upload failed';
                            }
                        })
                        .catch(() => label.textContent = 'Upload failed');
                }
            });
        });
    </script>
</body>

</html>