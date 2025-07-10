<?php
// io/route/admin/faq/alter.php
return function ($args) {
    $id = $args[0] ?? null;
    $faq = null;

    if ($id) {
        $faq = dbq(db(), "SELECT * FROM faq WHERE id = ?", [$id])->fetch();
        if (!$faq) {
            header('HTTP/1.0 404 Not Found');
            exit('FAQ non trouvée');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $label = trim($_POST['label'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (!$label || !$content) {
            return [
                'title' => $id ? 'Modifier la FAQ' : 'Nouvelle FAQ',
                'faq' => $_POST,
                'error' => 'Le titre et le contenu sont obligatoires'
            ];
        }

        if (!$slug) {
            $slug = preg_replace(
                '/[^a-z0-9-]/',
                '-',
                preg_replace(
                    '/[àáâäç]/u',
                    'a',
                    preg_replace(
                        '/[èéêë]/u',
                        'e',
                        strtolower($label)
                    )
                )
            );
            $slug = preg_replace('/-+/', '-', trim($slug, '-'));
        }

        // Check slug uniqueness
        $existing = dbq(
            db(),
            "SELECT id FROM faq WHERE slug = ? AND id != ?",
            [$slug, $id ?: 0]
        )->fetch();
        if ($existing) {
            $slug .= '-' . time();
        }

        if ($_POST['action'] ?? '' === 'delete' && $id) {
            dbq(db(), "DELETE FROM faq WHERE id = ?", [$id]);
            header('Location: /admin/faq');
            exit;
        }

        if ($id) {
            dbq(db(), "
                UPDATE faq 
                SET label = ?, slug = ?, content = ?, updated_at = NOW() 
                WHERE id = ?
            ", [$label, $slug, $content, $id]);
        } else {
            dbq(db(), "
                INSERT INTO faq (label, slug, content, created_at, updated_at) 
                VALUES (?, ?, ?, NOW(), NOW())
            ", [$label, $slug, $content]);
        }

        header('Location: /admin/faq');
        exit;
    }

    return [
        'title' => $id ? 'Modifier la FAQ' : 'Nouvelle FAQ',
        'faq' => $faq
    ];
};
