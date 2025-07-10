<?php
// io/render/admin/service/alter.php
$is_edit = !empty($service['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier le service' : 'Nouveau service' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/service/list" class="btn secondary">Retour à la liste</a>
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
    <input type="hidden" name="id" value="<?= $service['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Titre du service *</label>
            <input type="text" name="label" id="label"
                value="<?= htmlspecialchars($service['label'] ?? '') ?>"
                required maxlength="255">
        </fieldset>

        <fieldset class="form-group">
            <label for="content">Description *</label>
            <textarea name="content" id="content" rows="6" required
                class="content-editor"><?= htmlspecialchars($service['content'] ?? '') ?></textarea>
        </fieldset>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="image_src">Chemin de l'image *</label>
                <input type="text" name="image_src" id="image_src"
                    value="<?= htmlspecialchars($service['image_src'] ?? '') ?>"
                    required maxlength="255"
                    placeholder="/static/assets/service-1.webp">
                <small>Chemin relatif depuis la racine du site</small>
            </fieldset>

            <fieldset class="form-group">
                <label for="alt_text">Texte alternatif *</label>
                <input type="text" name="alt_text" id="alt_text"
                    value="<?= htmlspecialchars($service['alt_text'] ?? '') ?>"
                    required maxlength="255"
                    placeholder="Description de l'image pour l'accessibilité">
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="link">Lien (optionnel)</label>
                <input type="text" name="link" id="link"
                    value="<?= htmlspecialchars($service['link'] ?? '') ?>"
                    maxlength="255"
                    placeholder="https://exemple.com">
            </fieldset>

            <fieldset class="form-group">
                <label for="link_text">Texte du lien</label>
                <input type="text" name="link_text" id="link_text"
                    value="<?= htmlspecialchars($service['link_text'] ?? '') ?>"
                    maxlength="100"
                    placeholder="En savoir plus">
            </fieldset>
        </div>

        <fieldset class="form-group">
            <label for="sort_order">Ordre d'affichage</label>
            <input type="number" name="sort_order" id="sort_order"
                value="<?= $service['sort_order'] ?? 0 ?>"
                min="0" max="999">
            <small>Les services sont triés par ordre croissant (0 = premier)</small>
        </fieldset>
    </section>

    <?php if ($is_edit): ?>
        <aside>
            <section class="panel media-box">
                <header>
                    <h2>Aperçu</h2>
                </header>
                <?php if ($service['image_src']): ?>
                    <div class="current-image">
                        <img src="<?= htmlspecialchars($service['image_src']) ?>"
                            alt="<?= htmlspecialchars($service['alt_text']) ?>"
                            style="max-width:100%;height:auto;border-radius:8px;">
                    </div>
                <?php endif; ?>
            </section>

            <section class="panel stats-box">
                <header>
                    <h2>Informations</h2>
                </header>
                <dl class="stats-list">
                    <dt>Ordre</dt>
                    <dd><?= $service['sort_order'] ?></dd>

                    <?php if ($service['created_at']): ?>
                        <dt>Créé</dt>
                        <dd>
                            <time datetime="<?= $service['created_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($service['created_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <?php if ($service['updated_at']): ?>
                        <dt>Modifié</dt>
                        <dd>
                            <time datetime="<?= $service['updated_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($service['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>
                </dl>
            </section>
        </aside>
    <?php endif; ?>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer le service' ?>
        </button>
        <a href="/admin/service/list" class="btn secondary">Annuler</a>

        <?php if ($is_edit): ?>
            <button type="submit" name="action" value="delete" class="btn danger"
                data-confirm="Supprimer ce service ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
