<?php
// io/render/admin/service/service.php - CSRF protected version
?>
<header class="page-header">
    <h1>Services - Page d'accueil</h1>
    <nav class="page-actions">
        <a href="/admin/service/alter" class="btn">Nouveau service</a>
        <?php if (count($services) > 1): ?>
            <button data-widget="reorder-toggle" class="btn secondary" data-text-on="Terminer" data-text-off="Réorganiser"><span class="reorder-text">Réorganiser</span></button>
        <?php endif; ?>
    </nav>
</header>

<!-- Hidden CSRF token for AJAX -->
<?php if (count($services) > 1): ?>
    <div id="csrf-data"
        data-token="<?= csrf_token() ?>"
        data-field="<?= htmlspecialchars(csrf_field(3600)) ?>"
        style="display:none;"></div>
<?php endif; ?>

<?php if (empty($services)): ?>
    <div class="panel empty-state">
        <p>Aucun service configuré.</p>
        <a href="/admin/service/alter" class="btn">Créer le premier service</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table
            data-widget="reorder"
            data-csrf-name="csrf_token"
            data-csrf-token="<?= csrf_token(3600) ?>"
            data-reorder-url="/admin/service/reorder"
            class="services-table">
            <thead>
                <tr>
                    <th class="drag-header" style="display:none;">
                        <span class="sr-only">Glisser</span>
                    </th>
                    <th>Ordre</th>
                    <th>Service</th>
                    <th>Aperçu</th>
                    <th>Image</th>
                    <th>Lien</th>
                    <th>Créé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody data-widget="sortable-services">
                <?php foreach ($services as $service): ?>
                    <tr data-id="<?= $service['id'] ?>">
                        <td class="drag-handle" style="display:none;">
                            <span class="drag-icon">⋮⋮</span>
                        </td>
                        <td>
                            <span class="badge badge--primary order-badge"><?= $service['sort_order'] ?></span>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($service['label']) ?></strong>
                        </td>
                        <td>
                            <small><?= htmlspecialchars($service['preview']) ?><?= strlen($service['preview']) >= 100 ? '...' : '' ?></small>
                        </td>
                        <td>
                            <?php if ($service['image_src']): ?>
                                <img src="<?= htmlspecialchars($service['image_src']) ?>"
                                    alt="<?= htmlspecialchars($service['label']) ?>"
                                    style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <span class="text-muted">Aucune</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($service['link']): ?>
                                <a href="<?= htmlspecialchars($service['link']) ?>" target="_blank" class="btn small secondary">
                                    <?= htmlspecialchars($service['link_text'] ?: 'Voir') ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Aucun</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <time datetime="<?= $service['created_at'] ?>">
                                <?= date('d/m/Y', strtotime($service['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/service/alter/<?= $service['id'] ?>" class="btn small">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr data-role="reorder-message-row" hidden>
                    <td colspan="7">
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
    <p><strong>Astuce :</strong> Les services sont affichés sur la page d'accueil par ordre croissant du champ "Ordre".</p>
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
    // when *you* decide the DOM is ready (e.g. in your own loader):
    ReorderTable.init();
</script>


<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
