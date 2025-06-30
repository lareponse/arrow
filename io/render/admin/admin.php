<?php
extract($io ?? []);
?>
<header class="dashboard-header">
    <h1>Tableau de bord</h1>
    <time datetime="<?= date('Y-m-d') ?>" class="dashboard-date">
        <?= date('Y-m-d') ?>
    </time>
</header>

<?php
$statTypes = [
    ['key' => 'articles', 'label' => 'Articles'],
    ['key' => 'trainings', 'label' => 'Formations'],
    ['key' => 'events', 'label' => 'Événements'],
    ['key' => 'bookings', 'label' => 'Inscriptions']
];
?>

<section class="stats-grid" aria-labelledby="stats-heading">
    <h2 id="stats-heading" class="sr-only">Statistiques générales</h2>

    <?php foreach ($statTypes as $stat): ?>
        <article class="panel empty-state stat-card">
            <header>
                <h3><?= $stat['label'] ?></h3>
                <data value="<?= $stats[$stat['key'] . '_total'] ?>" class="stat-number">
                    <?= number_format($stats[$stat['key'] . '_total']) ?>
                </data>
            </header>
            <footer class="stat-meta">
                <span class="stat-change <?= $stats[$stat['key'] . '_change'] >= 0 ? 'positive' : 'negative' ?>">
                    <?= $stats[$stat['key'] . '_change'] >= 0 ? '+' : '' ?><?= $stats[$stat['key'] . '_change'] ?>
                </span>
                <span>ce mois</span>
            </footer>
        </article>
    <?php endforeach; ?>
</section>

<section class="dashboard-content">
    <section class="recent-activity" aria-labelledby="activity-heading">
        <header class="section-header">
            <h2 id="activity-heading">Activité récente</h2>
            <a href="/admin/contact/list" class="btn secondary small">Voir tout</a>
        </header>

        <?php if (empty($recent['contacts'])): ?>
            <p class="panel empty-state">Aucune demande de contact récente</p>
        <?php else: ?>
            <table class="panel table activity-list">
                <thead>
                    <tr>
                        <th>Contact</th>
                        <th>Statut</th>
                        <th>Sujet</th>
                        <th>Email</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent['contacts'] as $contact): ?>
                        <tr class="dashboard-item">
                            <td><a href="/admin/contact/view/<?= $contact['id'] ?>"><?= htmlspecialchars($contact['label'] ?? '') ?></a></td>
                            <td><span class="status <?= $contact['status'] ?>"><?= htmlspecialchars($contact['status_label'] ?? '') ?></span></td>
                            <td><?= htmlspecialchars($contact['subject_label'] ?? '') ?></td>
                            <td><?= htmlspecialchars($contact['email'] ?? '') ?></td>
                            <td><time datetime="<?= $contact['created_at'] ?>"><?= $contact['created_at'] ?></time></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

    <section class="upcoming-events" aria-labelledby="events-heading">
        <header class="section-header">
            <h2 id="events-heading">Événements à venir</h2>
            <a href="/admin/event/list" class="btn secondary small">Voir tout</a>
        </header>

        <?php if (empty($recent['events'])): ?>
            <p class="panel empty-state">Aucun événement programmé</p>
        <?php else: ?>
            <table class="panel table events-list">
                <thead>
                    <tr>
                        <th>Événement</th>
                        <th>Catégorie</th>
                        <th>Places</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent['events'] as $e): ?>
                        <tr class="dashboard-item">
                            <td><a href="/admin/event/alter/<?= $e['id'] ?>"><?= htmlspecialchars($e['label']) ?></a></td>
                            <td><span class="event-category"><?= ucfirst(htmlspecialchars($e['category_label'])) ?></span></td>
                            <td><?= $e['places_max'] ? "{$e['bookings_count']}/{$e['places_max']} places" : '&mdash;' ?></td>
                            <td><time datetime="<?= $e['event_date'] ?>"><?= $e['event_date'] ?></time></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </section>

</section>

<section class="quick-actions" aria-labelledby="actions-heading">
    <h2 id="actions-heading">Actions rapides</h2>
    <nav class="actions-grid">
        <a href="/admin/article/alter" class="panel empty-state action-card">
            <strong>Nouvel article</strong>
            <span>Créer un nouvel article de blog</span>
        </a>

        <a href="/admin/training/alter" class="panel empty-state action-card">
            <strong>Nouvelle formation</strong>
            <span>Ajouter une formation au catalogue</span>
        </a>

        <a href="/admin/event/alter" class="panel empty-state action-card">
            <strong>Nouvel événement</strong>
            <span>Planifier un webinaire ou conférence</span>
        </a>

        <a href="/admin/trainer/alter" class="panel empty-state action-card">
            <strong>Nouveau formateur</strong>
            <span>Ajouter un formateur à l'équipe</span>
        </a>
    </nav>
</section>

<?php

return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
