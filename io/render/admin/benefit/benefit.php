<?php
// io/render/admin/benefit/benefit.php - CSRF protected version
?>
<header class="page-header">
    <h1>Benefits - Page d'accueil</h1>
    <nav class="page-actions">
        <a href="/admin/benefit/alter" class="btn">Nouveau benefit</a>
    </nav>
</header>

<!-- Hidden CSRF token for AJAX -->
<?php if (count($benefits) > 1): ?>
    <div id="csrf-data"
        data-token="<?= csrf_token() ?>"
        data-field="<?= htmlspecialchars(csrf_field(3600)) ?>"
        style="display:none;"></div>
<?php endif; ?>

<?php if (empty($benefits)): ?>
    <div class="panel empty-state">
        <p>Aucun benefit configuré.</p>
        <a href="/admin/benefit/alter" class="btn">Créer le premier benefit</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table
            data-widget="reorder"
            data-csrf-name="csrf_token"
            data-csrf-token="<?= csrf_token(3600) ?>"
            data-reorder-url="/admin/benefit/reorder"
            class="benefits-table">
            <thead>
                <tr>
                    <th class="drag-header" style="display:none;">
                        <span class="sr-only">Glisser</span>
                    </th>
                    <th>Ordre</th>
                    <th>Icon</th>
                    <th>Benefit</th>
                    <th>Aperçu</th>
                    <th>Statut</th>
                    <th>Créé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody data-widget="sortable-benefits">
                <?php foreach ($benefits as $benefit): ?>
                    <tr data-id="<?= $benefit['id'] ?>">
                        <td class="drag-handle" style="display:none;">
                            <span class="drag-icon">⋮⋮</span>
                        </td>
                        <td>
                            <span class="badge badge--primary order-badge"><?= $benefit['sort_order'] ?></span>
                        </td>
                        <td>
                            <span class="benefit-icon"><?= htmlspecialchars($benefit['icon']) ?></span>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($benefit['title']) ?></strong>
                        </td>
                        <td>
                            <small><?= htmlspecialchars($benefit['preview']) ?><?= strlen($benefit['preview']) >= 100 ? '...' : '' ?></small>
                        </td>
                        <td>
                            <?php if ($benefit['is_active']): ?>
                                <span class="badge badge--success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge--muted">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <time datetime="<?= $benefit['created_at'] ?>">
                                <?= date('d/m/Y', strtotime($benefit['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/benefit/alter/<?= $benefit['id'] ?>" class="btn small">Modifier</a>
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
    <p><strong>Astuce :</strong> Les benefits sont affichés sur la page d'accueil par ordre croissant du champ "Ordre".</p>
</div>
<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
