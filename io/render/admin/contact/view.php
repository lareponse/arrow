<header class="page-header">
    <h1>Demande de contact #<?= $contact['id'] ?></h1>
    <a href="/admin/contact/list" class="btn secondary">← Retour à la liste</a>
</header>

<article class="contact-detail">
    <section class="contact-info">
        <h2>Informations</h2>
        <dl>
            <dt>Nom</dt>
            <dd><?= htmlspecialchars($contact['label']) ?></dd>

            <dt>Email</dt>
            <dd><a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a></dd>

            <?php if ($contact['company']): ?>
                <dt>Société</dt>
                <dd><?= htmlspecialchars($contact['company']) ?></dd>
            <?php endif; ?>

            <dt>Sujet</dt>
            <dd><?= htmlspecialchars($contact['subject_label'] ?? 'Non classé') ?></dd>

            <dt>Statut</dt>
            <dd><span class="status <?= strtolower($contact['status_label'] ?? 'unknown') ?>"><?= htmlspecialchars($contact['status_label'] ?? 'Inconnu') ?></span></dd>

            <dt>Date</dt>
            <dd><time datetime="<?= $contact['created_at'] ?>"><?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></time></dd>
        </dl>
    </section>

    <?php if ($contact['message']): ?>
        <section class="contact-message">
            <h2>Message</h2>
            <p><?= nl2br(htmlspecialchars($contact['message'])) ?></p>
        </section>
    <?php endif; ?>
</article>