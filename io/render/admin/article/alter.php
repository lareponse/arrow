<?php

[$data, $output] = $args;
$article = $data['article'] ?? [];
$categories = $data['categories'] ?? [];
$is_edit = !empty($article['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier l\'article' : 'Nouvel article' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/article/list" class="btn secondary">Retour à la liste</a>
            <?php if ($article['enabled_at']): ?>
                <a href="/article/<?= $article['slug'] ?>" class="btn secondary" target="_blank">
                    Voir sur le site
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</header>

<form method="post" class="article-form" enctype="multipart/form-data">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $article['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Titre *</label>
            <input
                type="text"
                name="label"
                value="<?= htmlspecialchars($article['label'] ?? '') ?>"
                required
                maxlength="200"
                aria-describedby="label-help">

            <label for="label">Slug *</label>
            <input
                type="text"
                name="slug"
                value="<?= htmlspecialchars($article['slug'] ?? '') ?>"
                required
                maxlength="200"
                aria-describedby="label-help">
            <small id="label-help">Le slug sera généré automatiquement</small>
        </fieldset>
        <script type="module">
            import slugify from '/assets/js/slug.js';
            document.addEventListener('DOMContentLoaded', () => {
                const labelInput = document.querySelector('input[name="label"]');
                const slugInput = document.querySelector('input[name="slug"]');
                

                labelInput.addEventListener('input', () => {
                    slugInput.value = slugify(labelInput.value);
                });
            });
        </script>

        <fieldset class="form-group">
            <label for="summary">Résumé</label>
            <textarea
                id="summary"
                name="summary"
                rows="3"
                maxlength="500"
                aria-describedby="summary-help"><?= htmlspecialchars($article['summary'] ?? '') ?></textarea>
            <small id="summary-help">Description courte pour les réseaux sociaux et moteurs de recherche</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Contenu *</label>
            <textarea
                id="content"
                name="content"
                rows="20"
                required
                class="content-editor"><?= htmlspecialchars($article['content'] ?? '') ?></textarea>
        </fieldset>
    </section>

    <aside class="form-sidebar">
        <section class="publish-box">
            <header>
                <h2>Publication</h2>
            </header>

            <fieldset class="form-group">
                <legend class="sr-only">État de publication</legend>
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="published"
                        value="1"
                        <?= !empty($article['enabled_at']) ? 'checked' : '' ?>>
                    <span class="checkbox-text">Publier l'article</span>
                </label>
                <?php if ($article['enabled_at']): ?>
                    <small>
                        Publié le
                        <time datetime="<?= $article['enabled_at'] ?>">
                            <?= strftime('%d %B %Y à %H:%M', strtotime($article['enabled_at'])) ?>
                        </time>
                    </small>
                <?php endif; ?>
            </fieldset>

            <fieldset class="form-group">
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="featured"
                        value="1"
                        <?= !empty($article['featured']) ? 'checked' : '' ?>>
                    <span class="checkbox-text">Article à la une</span>
                </label>
            </fieldset>
        </section>

        <section class="meta-box">
            <header>
                <h2>Métadonnées</h2>
            </header>

            <fieldset class="form-group">
                <label for="category_id">Catégorie</label>
                <select id="category_id" name="category_id">
                    <option value="">Aucune catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                        <option
                            value="<?= $cat['id'] ?>"
                            <?= ($article['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['label']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset class="form-group">
                <label for="reading_time">Temps de lecture (minutes)</label>
                <input
                    type="number"
                    id="reading_time"
                    name="reading_time"
                    min="1"
                    max="60"
                    value="<?= $article['reading_time'] ?? '' ?>"
                    aria-describedby="reading-help">
                <small id="reading-help">Laissez vide pour calcul automatique</small>
            </fieldset>
        </section>

        <section class="media-box">
            <header>
                <h2>Image</h2>
            </header>

            <?php if (!empty($article['avatar'])): ?>
                <figure class="current-image">
                    <img
                        src="<?= htmlspecialchars($article['avatar']) ?>"
                        alt="Image actuelle"
                        loading="lazy">
                    <figcaption>Image actuelle</figcaption>
                </figure>
            <?php endif; ?>

            <fieldset class="form-group">
                <label for="avatar">
                    <?= !empty($article['avatar']) ? 'Changer l\'image' : 'Ajouter une image' ?>
                </label>
                <input
                    type="file"
                    id="avatar"
                    name="avatar"
                    accept="image/jpeg,image/png,image/webp"
                    aria-describedby="avatar-help">
                <small id="avatar-help">JPEG, PNG ou WebP. Max 2MB.</small>
            </fieldset>
        </section>

        <?php if ($is_edit): ?>
            <section class="stats-box">
                <header>
                    <h2>Statistiques</h2>
                </header>

                <dl class="stats-list">
                    <dt>Créé le</dt>
                    <dd>
                        <time datetime="<?= $article['created_at'] ?>">
                            <?= strftime('%d %B %Y', strtotime($article['created_at'])) ?>
                        </time>
                    </dd>

                    <?php if ($article['updated_at']): ?>
                        <dt>Modifié le</dt>
                        <dd>
                            <time datetime="<?= $article['updated_at'] ?>">
                                <?= strftime('%d %B %Y à %H:%M', strtotime($article['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <dt>Slug</dt>
                    <dd><code><?= htmlspecialchars($article['slug'] ?? 'auto-généré') ?></code></dd>
                </dl>
            </section>
        <?php endif; ?>
    </aside>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer l\'article' ?>
        </button>
        <a href="/admin/article/list" class="btn secondary">Annuler</a>

        <?php if ($is_edit): ?>
            <button
                type="submit"
                name="action"
                value="delete"
                class="btn danger"
                data-confirm="Êtes-vous sûr de vouloir supprimer cet article ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/morph/admin_layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
