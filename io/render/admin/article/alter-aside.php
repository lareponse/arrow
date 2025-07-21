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
        <label class="checkbox-label">
            <input
                type="checkbox"
                name="featured"
                value="1"
                <?= !empty($article['featured']) ? 'checked' : '' ?>>
            <span class="checkbox-text">Article à la une</span>
        </label>
    </fieldset>
    <button type="submit" class="btn">Sauver</button>

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

<?php if ($is_edit): ?>
    <?php
    $dropzone_relative_path = 'article/avatar/' . $article['slug'];
    include('app/io/render/admin/dropzone.php')
    ?>

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
    import('/asset/js/emojis-unicode.js').then(({
        default: createPicker
    }) => {
        createPicker('#picker');
    });

    // import createPicker from '/asset/js/emojis-unicode.js';
    // // only show “education” & “achievements” for instance:
    // createPicker('#picker');
    // // optional global callback:
    // window.onEmojiSelect = info => console.log('picked:', info);
</script>