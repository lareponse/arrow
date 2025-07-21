<?php
// io/render/admin/hero_slide/alter.php
$is_edit = !empty($slide['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier le slide' : 'Nouveau slide' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/hero_slide" class="btn secondary">Retour à la liste</a>
        </nav>
    <?php endif; ?>
</header>

<?php if (isset($error)): ?>
    <div class="panel error">
        <p><?= htmlspecialchars($error) ?></p>
    </div>
<?php endif; ?>

<form method="post" class="alter-form">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $slide['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="image_path">Chemin de l'image *</label>
            <input type="text" name="image_path" id="image_path"
                value="<?= htmlspecialchars($slide['image_path'] ?? '') ?>"
                required maxlength="255"
                placeholder="/static/assets/hero-1.webp">
            <small>Chemin relatif ou URL complète vers l'image</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="alt_text">Texte alternatif</label>
            <input type="text" name="alt_text" id="alt_text"
                value="<?= htmlspecialchars($slide['alt_text'] ?? '') ?>"
                maxlength="255"
                placeholder="Description de l'image pour l'accessibilité">
        </fieldset>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="title">Titre principal</label>
                <input type="text" name="title" id="title"
                    value="<?= htmlspecialchars($slide['title'] ?? '') ?>"
                    maxlength="255">
            </fieldset>

            <fieldset class="form-group">
                <label for="subtitle">Sous-titre</label>
                <input type="text" name="subtitle" id="subtitle"
                    value="<?= htmlspecialchars($slide['subtitle'] ?? '') ?>"
                    maxlength="255">
            </fieldset>
        </div>

        <fieldset class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"
                class="content-editor"><?= htmlspecialchars($slide['description'] ?? '') ?></textarea>
            <small>Texte descriptif optionnel (HTML autorisé)</small>
        </fieldset>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="cta_text">Texte du bouton</label>
                <input type="text" name="cta_text" id="cta_text"
                    value="<?= htmlspecialchars($slide['cta_text'] ?? '') ?>"
                    maxlength="100"
                    placeholder="Découvrir">
            </fieldset>

            <fieldset class="form-group">
                <label for="cta_url">Lien du bouton</label>
                <input type="text" name="cta_url" id="cta_url"
                    value="<?= htmlspecialchars($slide['cta_url'] ?? '') ?>"
                    maxlength="255"
                    placeholder="/contact">
            </fieldset>
        </div>

        <fieldset class="form-group">
            <label for="sort_order">Ordre d'affichage</label>
            <input type="number" name="sort_order" id="sort_order"
                value="<?= $slide['sort_order'] ?? 0 ?>"
                min="0" max="999">
            <small>Les slides sont triés par ordre croissant (0 = premier)</small>
        </fieldset>
    </section>

    <?php if ($is_edit): ?>
        <aside>
            <section class="panel stats-box">
                <header>
                    <h2>Informations</h2>
                </header>
                <dl class="stats-list">
                    <dt>Ordre</dt>
                    <dd><?= $slide['sort_order'] ?></dd>

                    <?php if ($slide['created_at']): ?>
                        <dt>Créé</dt>
                        <dd>
                            <time datetime="<?= $slide['created_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($slide['created_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <?php if ($slide['updated_at']): ?>
                        <dt>Modifié</dt>
                        <dd>
                            <time datetime="<?= $slide['updated_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($slide['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>
                </dl>
            </section>
        </aside>
    <?php endif; ?>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer le slide' ?>
        </button>
        <a href="/admin/hero_slide" class="btn secondary">Annuler</a>

        <?php if ($is_edit): ?>
            <button type="submit" name="action" value="delete" class="btn danger"
                data-confirm="Supprimer ce slide ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
