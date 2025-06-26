<header class="page-header">
    <h1>Programme - <?= htmlspecialchars($training['label']) ?></h1>
    <nav class="page-actions">
        <a href="/admin/training/list" class="btn secondary">Retour aux formations</a>
        <a href="/admin/training/alter/<?= $training['slug'] ?>" class="btn secondary">Modifier formation</a>
        <a href="#add-session" class="btn" onclick="toggleSessionForm()">Nouvelle session</a>
    </nav>
</header>

<section class="program-overview">
    <div class="stats-grid">
        <div class="panel stat-card">
            <strong><?= count($sessions) ?></strong>
            <span>Sessions programmées</span>
        </div>
        <div class="panel stat-card">
            <strong><?= $training['duration_days'] ?></strong>
            <span>Jour<?= $training['duration_days'] > 1 ? 's' : '' ?></span>
        </div>
        <div class="panel stat-card">
            <strong><?= $total_duration ?>h</strong>
            <span>Durée totale</span>
        </div>
        <div class="panel stat-card">
            <strong><?= date('d/m/Y', strtotime($training['start_date'])) ?></strong>
            <span>Date de début</span>
        </div>
    </div>
</section>

<?php if ($edit_session || !empty($_GET['add'])): ?>

<?php endif; ?>

<?php include __DIR__ . '/_program_schedule.php'; ?>

<?php if (empty($sessions)): ?>
    <section class="empty-program">
        <div class="panel empty-state">
            <h3>Programme vide</h3>
            <p>Cette formation n'a pas encore de programme détaillé. Commencez par créer la première session.</p>
            <a href="?add=1&day=1#session-form" class="btn">Créer la première session</a>
        </div>
    </section>
<?php endif; ?>

<script>
    function closeSessionForm() {
        window.location.href = '/admin/training/program/<?= $training_id ?>';
    }

    function duplicateDay(day) {
        const targetDay = prompt(`Dupliquer le Jour ${day} vers quel jour ?`);
        if (targetDay && targetDay >= 1 && targetDay <= <?= $training['duration_days'] ?>) {
            // This would require a more complex implementation to duplicate all sessions
            alert('Fonctionnalité à implémenter');
        }
    }

    // Auto-generate slug from title
    document.getElementById('label')?.addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (slugField && !slugField.value) {
            const day = document.getElementById('day_number').value;
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '') + '-j' + day;
            slugField.value = slug;
        }
    });

    // Time validation
    document.getElementById('time_end')?.addEventListener('change', function() {
        const startTime = document.getElementById('time_start').value;
        const endTime = this.value;

        if (startTime && endTime && endTime <= startTime) {
            alert('L\'heure de fin doit être après l\'heure de début');
            this.focus();
        }
    });
</script>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
