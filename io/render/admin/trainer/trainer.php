<header class="page-header">
    <h1>Formateurs</h1>
    <nav class="page-actions">
        <a href="/admin/trainer/alter" class="btn">Nouveau formateur</a>
    </nav>
</header>
<section class="content-filters">
    <nav class="filter-tabs">
        <button type="button" class="filter-tab active" data-status="">Tous</button>
        <button type="button" class="filter-tab" data-status="active">Actifs</button>
        <button type="button" class="filter-tab" data-status="inactive">Inactifs</button>
    </nav>

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
                    <tr data-status="<?= $trainer['enabled_at'] ? 'active' : 'inactive' ?>">
                        <!-- existing row content stays the same -->
                        <td>
                            <div class="trainer-info">
                                <?php if ($trainer['avatar']): ?>
                                    <img src="/asset/image/trainer/avatar/<?= $trainer['slug'] ?>.webp"
                                        alt="<?= htmlspecialchars($trainer['label']) ?>"
                                        class="trainer-avatar">
                                <?php else: ?>
                                    <img src="https://xsgames.co/randomusers/avatar.php?g=male"
                                        alt="<?= htmlspecialchars($trainer['label']) ?>"
                                        class="trainer-avatar">
                                    <div class=" trainer-avatar-placeholder">
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
                                <br><a href="/admin/training?trainer_id=<?= $trainer['id'] ?>" class="text-link">
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

<?php endif; ?>

<style>
    .filter-tabs {
        display: flex;
        gap: var(--space-sm);
        margin-bottom: var(--space-md);
    }

    .filter-tab {
        padding: var(--space-sm) var(--space-md);
        background: none;
        border: none;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        cursor: pointer;
        font: inherit;
    }

    .filter-tab.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.filter-tab');
        const rows = document.querySelectorAll('tbody tr[data-status]');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const status = tab.dataset.status;
                rows.forEach(row => {
                    row.style.display = !status || row.dataset.status === status ? '' : 'none';
                });
            });
        });
    });
</script>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
