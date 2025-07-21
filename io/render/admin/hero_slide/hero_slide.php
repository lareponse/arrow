<?php
// io/render/admin/hero_slide/hero_slide.php - CSRF protected version
?>
<header class="page-header">
    <h1>Hero Slides - Page d'accueil</h1>
    <nav class="page-actions">
        <a href="/admin/hero_slide/alter" class="btn">Nouveau slide</a>
        <?php if (count($slides) > 1): ?>
            <button data-widget="reorder-toggle" class="btn secondary" data-text-on="Terminer" data-text-off="Réorganiser"><span class="reorder-text">Réorganiser</span></button>
        <?php endif; ?>
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

    <div id="reorder-status" class="panel info" style="display:none;">
        <p>✋ <strong>Mode réorganisation activé</strong> - Glissez les lignes pour changer l'ordre, puis cliquez sur "Terminer"</p>
    </div>
<?php endif; ?>

<div class="panel">
    <p><strong>Astuce :</strong> Les slides sont affichés sur la page d'accueil par ordre croissant du champ "Ordre".</p>
</div>

<style>
    .drag-handle {
        cursor: grab;
        width: 40px;
        text-align: center;
        background: #f9fafb;
        user-select: none;
    }

    .drag-handle:active {
        cursor: grabbing;
    }

    .drag-icon {
        color: #9ca3af;
        font-weight: bold;
        line-height: 1;
    }

    .sortable-mode tbody tr {
        transition: background-color 0.2s;
    }

    .sortable-mode tbody tr:hover {
        background-color: #f9fafb;
    }

    .sortable-mode tbody tr.dragging {
        opacity: 0.5;
        background-color: #e5e7eb;
    }

    .sortable-mode .actions {
        pointer-events: none;
        opacity: 0.5;
    }

    .drop-zone {
        border-top: 2px solid #3b82f6;
        background-color: #dbeafe;
    }

    .reorder-saving {
        opacity: 0.7;
        pointer-events: none;
    }
</style>

<script type="module" src="/asset/js/reorder.js" nonce="<?= csp_nonce() ?>"></script>
<script type="module">
    import ReorderTable from '/asset/js/reorder.js';
    ReorderTable.init();
</script>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
