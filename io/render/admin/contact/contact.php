<header class="page-header">
    <h1>Demandes de contact</h1>
</header>

<section class="content-filters">
    <nav class="filter-tabs">
        <a href="/admin/contact"
            class="<?= empty($current_status) ? 'active' : '' ?>">Tous</a>
        <?php foreach ($statuses as $slug => $label): ?>
            <a href="/admin/contact?status=<?= $slug ?>"
                class="<?= $current_status === $slug ? 'active' : '' ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>
    </nav>
</section>

<?php if (empty($contact_requests)): ?>
    <div class="panel empty-state">
        <p>Aucune demande de contact trouvée.</p>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Contact</th>
                    <th>Sujet</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contact_requests as $contact): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($contact['label']) ?></strong><br>
                            <small><?= htmlspecialchars($contact['email']) ?></small>
                            <?php if ($contact['company']): ?>
                                <br><small><?= htmlspecialchars($contact['company']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($contact['subject_label'] ?? 'Non classé') ?></td>
                        <td>
                            <span class="status <?= strtolower($contact['status_label'] ?? 'unknown') ?>">
                                <?= htmlspecialchars($contact['status_label'] ?? 'Inconnu') ?>
                            </span>
                        </td>
                        <td>
                            <time datetime="<?= $contact['created_at'] ?>">
                                <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/contact/view/<?= $contact['id'] ?>" class="btn small">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
