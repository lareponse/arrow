<header class="page-header">
    <h1>Formateurs</h1>
    <nav class="page-actions">
        <a href="/admin/trainer/alter" class="btn">Nouveau formateur</a>
    </nav>
</header>

<section class="content-filters">
    <nav class="filter-tabs">
        <a href="/admin/trainer/list"
            class="<?= empty($current_status) ? 'active' : '' ?>">Tous</a>
        <a href="/admin/trainer/list?status=active"
            class="<?= $current_status === 'active' ? 'active' : '' ?>">Actifs</a>
        <a href="/admin/trainer/list?status=inactive"
            class="<?= $current_status === 'inactive' ? 'active' : '' ?>">Inactifs</a>
    </nav>

    <form method="get" class="search-form">
        <?php if ($current_status): ?>
            <input type="hidden" name="status" value="<?= htmlspecialchars($current_status) ?>">
        <?php endif; ?>
        <input type="search"
            name="q"
            value="<?= htmlspecialchars($search ?? '') ?>"
            placeholder="Rechercher formateurs...">
        <button type="submit">Rechercher</button>
        <?php if ($search): ?>
            <a href="/admin/trainer/list<?= $current_status ? '?status=' . urlencode($current_status) : '' ?>" class="btn secondary">Effacer</a>
        <?php endif; ?>
    </form>
</section>

<?php if (empty($trainers)): ?>
    <div class="panel empty-state">
        <p>Aucun formateur trouvé.</p>
        <a href="/admin/trainer/alter" class="btn">Ajouter le premier formateur</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Formateur</th>
                    <th>Email</th>
                    <th>Date d'embauche</th>
                    <th>Formations</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trainers as $trainer): ?>
                    <tr>
                        <td>
                            <div class="trainer-info">
                                <?php if ($trainer['avatar']): ?>
                                    <img src="<?= htmlspecialchars($trainer['avatar']) ?>"
                                        alt="<?= htmlspecialchars($trainer['label']) ?>"
                                        class="trainer-avatar">
                                <?php else: ?>
                                    <div class="trainer-avatar-placeholder">
                                        <?= strtoupper(substr($trainer['label'], 0, 2)) ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <strong><?= htmlspecialchars($trainer['label']) ?></strong>
                                    <?php if ($trainer['bio']): ?>
                                        <br><small class="trainer-bio">
                                            <?= htmlspecialchars(substr($trainer['bio'], 0, 80)) ?>
                                            <?= strlen($trainer['bio']) > 80 ? '...' : '' ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($trainer['email']): ?>
                                <a href="mailto:<?= htmlspecialchars($trainer['email']) ?>">
                                    <?= htmlspecialchars($trainer['email']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Non renseigné</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($trainer['hire_date']): ?>
                                <time datetime="<?= $trainer['hire_date'] ?>">
                                    <?= date('d/m/Y', strtotime($trainer['hire_date'])) ?>
                                </time>
                                <br><small class="text-muted">
                                    <?php
                                    $years = floor((time() - strtotime($trainer['hire_date'])) / (365.25 * 24 * 3600));
                                    echo $years > 0 ? "{$years} an" . ($years > 1 ? 's' : '') : 'Moins d\'un an';
                                    ?>
                                </small>
                            <?php else: ?>
                                <span class="text-muted">Non renseignée</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= $trainer['training_count'] ?></strong>
                            <?php if ($trainer['training_count'] > 0): ?>
                                <br><a href="/admin/training/list?trainer_id=<?= $trainer['id'] ?>" class="text-link">
                                    Voir les formations
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status <?= $trainer['enabled_at'] ? 'active' : 'inactive' ?>">
                                <?= $trainer['enabled_at'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="/admin/trainer/alter/<?= $trainer['slug'] ?>" class="btn small">Modifier</a>
                            <?php if ($trainer['enabled_at']): ?>
                                <a href="/trainer/<?= $trainer['slug'] ?>" class="btn small secondary" target="_blank">Voir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pagination['total_pages'] > 1): ?>
        <nav class="pagination">
            <?php
            $query_params = [];
            if ($search) $query_params['q'] = $search;
            if ($current_status) $query_params['status'] = $current_status;
            $query_string = $query_params ? '&' . http_build_query($query_params) : '';
            ?>

            <?php if ($pagination['page'] > 1): ?>
                <a href="?page=<?= $pagination['page'] - 1 ?><?= $query_string ?>">« Précédent</a>
            <?php endif; ?>

            <span>Page <?= $pagination['page'] ?> sur <?= $pagination['total_pages'] ?></span>

            <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="?page=<?= $pagination['page'] + 1 ?><?= $query_string ?>">Suivant »</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<style>
    .trainer-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .trainer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .trainer-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .trainer-bio {
        color: #6b7280;
        line-height: 1.3;
    }

    .status.active {
        background: #dcfce7;
        color: #166534;
    }

    .status.inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .text-muted {
        color: #6b7280;
    }

    .text-link {
        color: #3b82f6;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .text-link:hover {
        text-decoration: underline;
    }
</style>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
