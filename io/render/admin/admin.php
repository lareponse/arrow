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
            <ol class="activity-list">
                <?php foreach ($recent['contacts'] as $contact): ?>
                    <li class="dashboard-item">
                        <article>
                            <header class="dashboard-header">
                                <h3>
                                    <a href="/admin/contact/view/<?= $contact['id'] ?>">
                                        <?= htmlspecialchars($contact['label']) ?>
                                    </a>
                                </h3>
                                <span class="status <?= $contact['status'] ?>">
                                    <?= ucfirst($contact['status']) ?>
                                </span>
                            </header>
                            <p class="activity-subject">
                                Sujet: <?= htmlspecialchars($contact['subject']) ?>
                            </p>
                            <footer class="activity-meta">
                                <time datetime="<?= $contact['created_at'] ?>">
                                    <?= date('%d/%m à %H:%M', strtotime($contact['created_at'])) ?>
                                </time>
                                <address><?= htmlspecialchars($contact['email']) ?></address>
                            </footer>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ol>
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
            <ol class="panel events-list">
                <?php foreach ($recent['events'] as $event): ?>
                    <li class="dashboard-item">
                        <article>
                            <header class="dashboard-header">
                                <h3>
                                    <a href="/admin/event/alter/<?= $event['id'] ?>">
                                        <?= htmlspecialchars($event['label']) ?>
                                    </a>
                                </h3>
                                <span class="event-category"><?= ucfirst($event['category']) ?></span>
                            </header>
                            <dl class="event-details">
                                <dt class="sr-only">Date</dt>
                                <dd>
                                    <time datetime="<?= $event['event_date'] ?>">
                                        <?= $event['event_date'] ?>
                                    </time>
                                </dd>
                                <?php if ($event['places_max']): ?>
                                    <dt class="sr-only">Places disponibles</dt>
                                    <dd>
                                        <data value="<?= $event['bookings_count'] ?>">
                                            <?= $event['bookings_count'] ?>
                                        </data>
                                        /
                                        <data value="<?= $event['places_max'] ?>">
                                            <?= $event['places_max'] ?>
                                        </data>
                                        places
                                    </dd>
                                <?php endif; ?>
                            </dl>
                        </article>
                    </li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </section>
</section>

<section class="quick-actions" aria-labelledby="actions-heading">
    <h2 id="actions-heading">Actions rapides</h2>
    <nav class="actions-grid">
        <a href="/admin/article/alter"class="panel empty-state action-card">
            <strong>Nouvel article</strong>
            <span>Créer un nouvel article de blog</span>
        </a>

        <a href="/admin/training/alter"class="panel empty-state action-card">
            <strong>Nouvelle formation</strong>
            <span>Ajouter une formation au catalogue</span>
        </a>

        <a href="/admin/event/alter"class="panel empty-state action-card">
            <strong>Nouvel événement</strong>
            <span>Planifier un webinaire ou conférence</span>
        </a>

        <a href="/admin/trainer/alter"class="panel empty-state action-card">
            <strong>Nouveau formateur</strong>
            <span>Ajouter un formateur à l'équipe</span>
        </a>
    </nav>
</section>

<?php

return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
