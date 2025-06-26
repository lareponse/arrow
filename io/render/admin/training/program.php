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
    <section class="session-form-overlay" id="session-form">
        <div class="form-container">
            <header class="form-header">
                <h2><?= $edit_session ? 'Modifier la session' : 'Nouvelle session' ?></h2>
                <button type="button" onclick="closeSessionForm()" class="btn-close">×</button>
            </header>

            <form method="post" class="session-form">
                <?= csrf_field(3600) ?>
                <?php if ($edit_session): ?>
                    <input type="hidden" name="id" value="<?= $edit_session['id'] ?>">
                <?php endif; ?>

                <div class="form-row">
                    <fieldset class="form-group">
                        <label for="day_number">Jour *</label>
                        <select name="day_number" id="day_number" required>
                            <?php for ($day = 1; $day <= $training['duration_days']; $day++): ?>
                                <option value="<?= $day ?>" <?= ($edit_session['day_number'] ?? ($_GET['day'] ?? 1)) == $day ? 'selected' : '' ?>>
                                    Jour <?= $day ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </fieldset>

                    <fieldset class="form-group">
                        <label for="time_start">Heure de début *</label>
                        <input type="time" name="time_start" id="time_start"
                            value="<?= $edit_session['time_start'] ?? '' ?>" required>
                    </fieldset>

                    <fieldset class="form-group">
                        <label for="time_end">Heure de fin *</label>
                        <input type="time" name="time_end" id="time_end"
                            value="<?= $edit_session['time_end'] ?? '' ?>" required>
                    </fieldset>
                </div>

                <fieldset class="form-group">
                    <label for="label">Titre de la session *</label>
                    <input type="text" name="label" id="label"
                        value="<?= htmlspecialchars($edit_session['label'] ?? '') ?>"
                        required maxlength="150">
                </fieldset>

                <fieldset class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug"
                        value="<?= htmlspecialchars($edit_session['slug'] ?? '') ?>"
                        maxlength="155">
                    <small>Généré automatiquement si vide</small>
                </fieldset>

                <fieldset class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4"><?= htmlspecialchars($edit_session['description'] ?? '') ?></textarea>
                </fieldset>

                <fieldset class="form-group">
                    <label for="objectives">Objectifs</label>
                    <textarea name="objectives" id="objectives" rows="3"><?= htmlspecialchars($edit_session['objectives'] ?? '') ?></textarea>
                </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn">
                        <?= $edit_session ? 'Modifier' : 'Créer' ?> la session
                    </button>
                    <button type="button" onclick="closeSessionForm()" class="btn secondary">Annuler</button>
                </div>
            </form>
        </div>
    </section>
<?php endif; ?>

<section class="program-schedule">
    <?php foreach ($program_by_day as $day => $day_sessions): ?>
        <div class="program-day" id="day-<?= $day ?>">
            <header class="day-header">
                <h2>Jour <?= $day ?></h2>
                <div class="day-actions">
                    <?php if (!empty($day_sessions)): ?>
                        <span class="session-count"><?= count($day_sessions) ?> session<?= count($day_sessions) > 1 ? 's' : '' ?></span>
                        <button type="button" onclick="duplicateDay(<?= $day ?>)" class="btn small tertiary">Dupliquer le jour</button>
                    <?php endif; ?>
                    <a href="?add=1&day=<?= $day ?>#session-form" class="btn small">Ajouter session</a>
                </div>
            </header>

            <?php if (empty($day_sessions)): ?>
                <div class="empty-day">
                    <p>Aucune session programmée pour ce jour</p>
                    <a href="?add=1&day=<?= $day ?>#session-form" class="btn secondary">Créer la première session</a>
                </div>
            <?php else: ?>
                <div class="sessions-timeline">
                    <?php foreach ($day_sessions as $session): ?>
                        <article class="session-card">
                            <div class="session-time">
                                <time><?= date('H:i', strtotime($session['time_start'])) ?></time>
                                <span class="separator">-</span>
                                <time><?= date('H:i', strtotime($session['time_end'])) ?></time>
                                <small class="duration">
                                    <?php
                                    $duration = (strtotime($session['time_end']) - strtotime($session['time_start'])) / 60;
                                    echo round($duration) . 'min';
                                    ?>
                                </small>
                            </div>

                            <div class="session-content">
                                <h3><?= htmlspecialchars($session['label']) ?></h3>

                                <?php if ($session['description']): ?>
                                    <p class="session-description"><?= nl2br(htmlspecialchars($session['description'])) ?></p>
                                <?php endif; ?>

                                <?php if ($session['objectives']): ?>
                                    <div class="session-objectives">
                                        <strong>Objectifs :</strong>
                                        <p><?= nl2br(htmlspecialchars($session['objectives'])) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="session-actions">
                                <a href="?edit=<?= $session['id'] ?>#session-form" class="btn small">Modifier</a>
                                <div class="dropdown">
                                    <button type="button" class="btn small secondary dropdown-toggle">•••</button>
                                    <div class="dropdown-menu">
                                        <?php for ($target_day = 1; $target_day <= $training['duration_days']; $target_day++): ?>
                                            <?php if ($target_day != $day): ?>
                                                <a href="?duplicate=<?= $session['id'] ?>&to_day=<?= $target_day ?>" class="dropdown-item">
                                                    Dupliquer vers Jour <?= $target_day ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <hr>
                                        <a href="?delete=<?= $session['id'] ?>"
                                            onclick="return confirm('Supprimer cette session ?')"
                                            class="dropdown-item danger">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>

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
    function toggleSessionForm() {
        const form = document.getElementById('session-form');
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        } else {
            window.location.href = '?add=1#session-form';
        }
    }

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
