<?php
// io/render/admin/article/alter.php
$is_edit = !empty($article['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier l\'article' : 'Nouvel article' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/article" class="btn secondary">Retour à la liste</a>
            <?php if ($article['enabled_at']): ?>
                <a href="/article/detail/<?= $article['slug'] ?>" class="btn secondary" target="_blank">
                    Voir sur le site
                </a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</header>

<form method="post" class="alter-form" enctype="multipart/form-data">
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

    <aside>
        <section class="panel publish-box">
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

        <section class="panel meta-box">
            <header>
                <h2>Métadonnées</h2>
            </header>

            <fieldset class="form-group">
                <label for="category_slug">Catégorie</label>
                <select name="category_slug">
                    <?php foreach (($categories) as $slug => $label): ?>
                        <option value="<?= $slug ?>"
                            <?= ($article['category_slug'] ?? '') == $slug ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset class="form-group">
                <label for="reading_time">Temps de lecture (minutes)</label>
                <input
                    type="number"
                    name="reading_time"
                    id="reading_time"
                    min="1"
                    max="60"
                    value="<?= $article['reading_time'] ?? '' ?>"
                    aria-describedby="reading-help">
                <small id="reading-help">Laissez vide pour calcul automatique</small>
            </fieldset>

            <script type="module">
                import reading_time from '/asset/js/reading-time.js';

                document.addEventListener('DOMContentLoaded', () => {
                    const textArea = document.querySelector('textarea[name="content"]');
                    const timeInput = document.querySelector('input[name="reading_time"]');

                    if (!textArea || !timeInput) return;

                    const updateReadingTime = () => {
                        const content = textArea.value.trim();
                        if (content === '') {
                            timeInput.value = '';
                        } else {
                            // reading_time() returns a Number of whole minutes
                            timeInput.value = reading_time(content);
                        }
                    };

                    // Compute on load (in case the textarea is pre-filled)
                    updateReadingTime();

                    // Recompute on every edit
                    textArea.addEventListener('input', updateReadingTime);
                });
            </script>

        </section>

        
        <section class="media-box panel drop-zone" data-upload="/admin/upload/article/avatar/<?= $article['slug'] ?>">
            <figure>
                <img src="/asset/image/article/avatar/<?= $article['slug'] ?>.webp" class="drop-preview" alt=" - Photo manquante - " loading="lazy" />
                <figcaption>Photo principale</figcaption>
            </figure>
            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" hidden>
            <label for="avatar" class="drop-label">
                <span></span>
                <strong>JPEG, PNG ou WebP.<br>Max 2MB.</strong>
            </label>
        </section>

        <?php if ($is_edit): ?>
            <section class="panel stats-box">
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

        <div id="picker" class="panel emoji-picker"></div>
        <div class="output">
            Selected: <span id="selected">None</span><br>
            Unicode: <span id="unicode">-</span><br>
            Hex: <span id="hex">-</span>
        </div>


        <script type="module">
            import createPicker from '/asset/js/emojis-unicode.js';
            // only show “education” & “achievements” for instance:
            createPicker('#picker');
            // optional global callback:
            window.onEmojiSelect = info => console.log('picked:', info);
        </script>
    </aside>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer l\'article' ?>
        </button>
        <a href="/admin/article" class="btn secondary">Retour</a>

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
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
