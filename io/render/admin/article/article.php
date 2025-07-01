<header class="page-header">
    <h1>Articles</h1>
    <nav class="page-actions">
        <a href="/admin/article/alter" class="btn">Nouvel article</a>
    </nav>
</header>

<section class="content-filters">
    <form method="get" class="search-form">
        <input type="search"
            name="q"
            value="<?= htmlspecialchars($search ?? '') ?>"
            placeholder="Rechercher articles...">
        <button type="submit">Rechercher</button>
        <?php if ($search): ?>
            <a href="/admin/article" class="btn secondary">Effacer</a>
        <?php endif; ?>
    </form>
</section>

<?php if (empty($articles)): ?>
    <div class="panel empty-state">
        <p>Aucun article trouvé.</p>
        <a href="/admin/article/alter" class="btn">Créer le premier article</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($article['label']) ?></strong>
                            <?php if ($article['featured']): ?>
                                <span class="badge featured">À la une</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($article['category_label'] ?? 'Non classé') ?></td>
                        <td>
                            <span class="status <?= $article['enabled_at'] ? 'published' : 'draft' ?>">
                                <?= $article['enabled_at'] ? 'Publié' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td>
                            <time datetime="<?= $article['created_at'] ?>">
                                <?= date('d/m/Y', strtotime($article['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/article/alter/<?= $article['slug'] ?>" class="btn small">Modifier</a>
                            <?php if ($article['enabled_at']): ?>
                                <a href="/article/<?= $article['slug'] ?>" class="btn small secondary" target="_blank">Voir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination['total_pages'] > 1): ?>
        <nav class="pagination">
            <?php if ($pagination['page'] > 1): ?>
                <a href="?page=<?= $pagination['page'] - 1 ?><?= $search ? '&q=' . urlencode($search) : '' ?>">« Précédent</a>
            <?php endif; ?>

            <span>Page <?= $pagination['page'] ?> sur <?= $pagination['total_pages'] ?></span>

            <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="?page=<?= $pagination['page'] + 1 ?><?= $search ? '&q=' . urlencode($search) : '' ?>">Suivant »</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
