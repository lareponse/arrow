<header class="page-header">
    <h1>Formations</h1>
    <nav class="page-actions">
        <a href="/admin/training/alter" class="btn">Nouvelle formation</a>
    </nav>
</header>

<section class="content-filters">
    <nav class="filter-tabs">
        <a href="/admin/training/list"
            class="<?= empty($current_status) ? 'active' : '' ?>">Toutes</a>
        <a href="/admin/training/list?status=upcoming"
            class="<?= $current_status === 'upcoming' ? 'active' : '' ?>">À venir</a>
        <a href="/admin/training/list?status=past"
            class="<?= $current_status === 'past' ? 'active' : '' ?>">Terminées</a>
        <a href="/admin/training/list?status=published"
            class="<?= $current_status === 'published' ? 'active' : '' ?>">Publiées</a>
        <a href="/admin/training/list?status=draft"
            class="<?= $current_status === 'draft' ? 'active' : '' ?>">Brouillons</a>
    </nav>

    <form method="get" class="search-form">
        <?php if ($current_status): ?>
            <input type="hidden" name="status" value="<?= htmlspecialchars($current_status) ?>">
        <?php endif; ?>
        <input type="search"
            name="q"
            value="<?= htmlspecialchars($search ?? '') ?>"
            placeholder="Rechercher formations...">
        <button type="submit">Rechercher</button>
        <?php if ($search): ?>
            <a href="/admin/training/list<?= $current_status ? '?status=' . urlencode($current_status) : '' ?>" class="btn secondary">Effacer</a>
        <?php endif; ?>
    </form>
</section>

<?php if (empty($trainings)): ?>
    <div class="empty-state">
        <p>Aucune formation trouvée.</p>
        <a href="/admin/training/alter" class="btn">Créer la première formation</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Formation</th>
                    <th>Niveau</th>
                    <th>Durée</th>
                    <th>Date de début</th>
                    <th>Formateur</th>
                    <th>Inscriptions</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trainings as $training): ?>
                    <?php
                    $is_past = strtotime($training['start_date']) < time();
                    $is_upcoming = strtotime($training['start_date']) > time();
                    $end_date = date('Y-m-d', strtotime($training['start_date'] . ' + ' . ($training['duration_days'] - 1) . ' days'));
                    ?>
                    <tr class="<?= $is_past ? 'training-past' : ($is_upcoming ? 'training-upcoming' : '') ?>">
                        <td>
                            <strong><?= htmlspecialchars($training['label']) ?></strong>
                            <?php if ($training['certification']): ?>
                                <span class="badge certification">Certifiante</span>
                            <?php endif; ?>
                            <?php if ($training['description']): ?>
                                <br><small class="description"><?= htmlspecialchars(substr($training['description'], 0, 100)) ?><?= strlen($training['description']) > 100 ? '...' : '' ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="level-badge level-<?= strtolower($training['level_label'] ?? 'unknown') ?>">
                                <?= htmlspecialchars($training['level_label'] ?? 'Non défini') ?>
                            </span>
                        </td>
                        <td>
                            <strong><?= $training['duration_days'] ?> jour<?= $training['duration_days'] > 1 ? 's' : '' ?></strong>
                            <br><small><?= $training['duration_hours'] ?>h au total</small>
                        </td>
                        <td>
                            <time datetime="<?= $training['start_date'] ?>">
                                <?= date('d/m/Y', strtotime($training['start_date'])) ?>
                            </time>
                            <?php if ($training['duration_days'] > 1): ?>
                                <br><small>au <?= date('d/m/Y', strtotime($end_date)) ?></small>
                            <?php endif; ?>
                            <?php if ($is_past): ?>
                                <br><span class="badge past">Terminée</span>
                            <?php elseif ($is_upcoming): ?>
                                <br><span class="badge upcoming">À venir</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($training['trainer_label']): ?>
                                <?= htmlspecialchars($training['trainer_label']) ?>
                            <?php else: ?>
                                <span class="text-muted">Non assigné</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= $training['bookings_count'] ?? 0 ?></strong>
                            <?php if ($training['places_max']): ?>
                                / <?= $training['places_max'] ?>
                                <?php
                                $fill_rate = $training['places_max'] > 0 ? (($training['bookings_count'] ?? 0) / $training['places_max']) * 100 : 0;
                                if ($fill_rate >= 90): ?>
                                    <span class="badge full">Complet</span>
                                <?php elseif ($fill_rate >= 75): ?>
                                    <span class="badge filling">Bientôt complet</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <br><small>Illimité</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= number_format($training['price_ht'], 0) ?> €</strong>
                            <br><small>HT</small>
                        </td>
                        <td>
                            <span class="status <?= $training['enabled_at'] ? 'published' : 'draft' ?>">
                                <?= $training['enabled_at'] ? 'Publiée' : 'Brouillon' ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="/admin/training/alter/<?= $training['slug'] ?>" class="btn small">Modifier</a>
                            <?php if ($training['enabled_at']): ?>
                                <a href="/training/<?= $training['slug'] ?>" class="btn small secondary" target="_blank">Voir</a>
                            <?php endif; ?>
                            <?php if (($training['bookings_count'] ?? 0) > 0): ?>
                                <a href="/admin/booking/list?training_id=<?= $training['id'] ?>" class="btn small tertiary">Inscrits</a>
                            <?php endif; ?>
                            <a href="/admin/training/program/<?= $training['id'] ?>" class="btn small quaternary">Programme</a>
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

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
