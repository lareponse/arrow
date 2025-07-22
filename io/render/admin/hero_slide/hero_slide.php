<?php
// io/render/admin/hero_slide/hero_slide.php - CSRF protected version
?>
<header class="page-header">
    <h1>Hero Slides - Page d'accueil</h1>
    <nav class="page-actions">
        <a href="/admin/hero_slide/alter" class="btn">Nouveau slide</a>
    </nav>
</header>

<!-- Hidden CSRF token for AJAX -->
<?php if (count($slides) > 1): ?>
    <div id="csrf-data"
        data-token="<?= csrf_token() ?>"
        data-field="<?= htmlspecialchars(csrf_field(3600)) ?>"
        style="display:none;"></div>
<?php endif; ?>

<?php if (empty($slides)): ?>
    <div class="panel empty-state">
        <p>Aucun slide configuré.</p>
        <a href="/admin/hero_slide/alter" class="btn">Créer le premier slide</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table
            data-widget="reorder"
            data-csrf-name="csrf_token"
            data-csrf-token="<?= csrf_token(3600) ?>"
            data-reorder-url="/admin/hero_slide/reorder"
            class="slides-table">
            <thead>
                <tr>
                    <th class="drag-header" style="display:none;">
                        <span class="sr-only">Glisser</span>
                    </th>
                    <th>Ordre</th>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Aperçu</th>
                    <th>CTA</th>
                    <th>Créé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody data-widget="sortable-slides">
                <?php foreach ($slides as $slide): ?>
                    <tr data-id="<?= $slide['id'] ?>">
                        <td class="drag-handle" style="display:none;">
                            <span class="drag-icon">⋮⋮</span>
                        </td>
                        <td>
                            <span class="badge badge--primary order-badge"><?= $slide['sort_order'] ?></span>
                        </td>
                        <td>
                            <?php if ($slide['image_path']): ?>
                                <img src="<?= htmlspecialchars($slide['image_path']) ?>"
                                    alt="<?= htmlspecialchars($slide['alt_text'] ?: $slide['title']) ?>"
                                    style="width:60px;height:40px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <span class="text-muted">Aucune</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($slide['title']): ?>
                                <strong><?= htmlspecialchars($slide['title']) ?></strong>
                            <?php else: ?>
                                <span class="text-muted">Sans titre</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($slide['preview']): ?>
                                <small><?= htmlspecialchars($slide['preview']) ?><?= strlen($slide['preview']) >= 100 ? '...' : '' ?></small>
                            <?php else: ?>
                                <span class="text-muted">Aucune description</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($slide['cta_text'] && $slide['cta_url']): ?>
                                <a href="<?= htmlspecialchars($slide['cta_url']) ?>" target="_blank" class="btn small secondary">
                                    <?= htmlspecialchars($slide['cta_text']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Aucun</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <time datetime="<?= $slide['created_at'] ?>">
                                <?= date('d/m/Y', strtotime($slide['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/hero_slide/alter/<?= $slide['id'] ?>" class="btn small">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr data-role="reorder-message-row" hidden>
                    <td colspan="8">
                        <div class="panel success" data-message="success">
                            <p>Ordre sauvegardé avec succès</p>
                        </div>
                        <div class="panel error" data-message="error">
                            <p>Erreur lors de la sauvegarde</p>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php endif; ?>

<div class="panel">
    <p><strong>Astuce :</strong> Les slides sont affichés sur la page d'accueil par ordre croissant du champ "Ordre".</p>
</div>


<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
