<?php
$is_edit = !empty($training['id']);
?>

<header class="page-header">
    <h1><?= $is_edit ? 'Modifier la formation' : 'Nouvelle formation' ?></h1>
    <?php if ($is_edit): ?>
        <nav class="page-actions">
            <a href="/admin/training/list" class="btn secondary">Retour à la liste</a>
            <?php if ($training['enabled_at']): ?>
                <a href="/training/<?= $training['slug'] ?>" class="btn secondary" target="_blank">Voir sur le site</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</header>

<form method="post" class="alter-form" enctype="multipart/form-data">
    <?= csrf_field(3600) ?>
    <input type="hidden" name="id" value="<?= $training['id'] ?? null ?>">

    <section class="form-main">
        <fieldset class="form-group">
            <label for="label">Titre de la formation *</label>
            <input type="text" name="label" id="label"
                value="<?= htmlspecialchars($training['label'] ?? '') ?>"
                required maxlength="200">

            <label for="slug">Slug *</label>
            <input type="text" name="slug" id="slug"
                value="<?= htmlspecialchars($training['slug'] ?? '') ?>"
                required maxlength="200">
        </fieldset>

        <script type="module">
            import slugify from '/asset/js/slug.js';
            document.addEventListener('DOMContentLoaded', () => {
                const labelInput = document.querySelector('#label');
                const slugInput = document.querySelector('#slug');
                labelInput.addEventListener('input', () => {
                    slugInput.value = slugify(labelInput.value);
                });
            });
        </script>

        <fieldset class="form-group">
            <label for="description">Description *</label>
            <textarea name="description" id="description" rows="6" required><?= htmlspecialchars($training['description'] ?? '') ?></textarea>
        </fieldset>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="start_date">Date de début *</label>
                <input type="date" name="start_date" id="start_date"
                    value="<?= $training['start_date'] ? date('Y-m-d', strtotime($training['start_date'])) : '' ?>"
                    required>
            </fieldset>

            <fieldset class="form-group">
                <label for="duration_days">Durée (jours) *</label>
                <input type="number" name="duration_days" id="duration_days"
                    value="<?= $training['duration_days'] ?? 1 ?>"
                    min="1" max="30" required>
            </fieldset>

            <fieldset class="form-group">
                <label for="duration_hours">Heures par jour *</label>
                <input type="number" name="duration_hours" id="duration_hours"
                    value="<?= $training['duration_hours'] ?? 7 ?>"
                    min="1" max="12" required>
            </fieldset>
        </div>

        <div class="form-row">
            <fieldset class="form-group">
                <label for="price_ht">Prix HT (€) *</label>
                <input type="number" name="price_ht" id="price_ht"
                    value="<?= $training['price_ht'] ?? '' ?>"
                    min="0" step="0.01" required>
            </fieldset>

            <fieldset class="form-group">
                <label for="places_max">Places maximum</label>
                <input type="number" name="places_max" id="places_max"
                    value="<?= $training['places_max'] ?? '' ?>"
                    min="1">
                <small>Laissez vide pour illimité</small>
            </fieldset>
        </div>

        <fieldset class="form-group">
            <label for="objectives">Objectifs pédagogiques</label>
            <textarea name="objectives" id="objectives" rows="4"><?= htmlspecialchars($training['objectives'] ?? '') ?></textarea>
            <small>Décrivez ce que les participants apprendront</small>
        </fieldset>

        <fieldset class="form-group">
            <label for="prerequisites">Prérequis</label>
            <textarea name="prerequisites" id="prerequisites" rows="3"><?= htmlspecialchars($training['prerequisites'] ?? '') ?></textarea>
            <small>Connaissances ou expérience nécessaires</small>
        </fieldset>
    </section>

    <aside class="form-sidebar">
        <section class="publish-box">
            <header>
                <h2>Publication</h2>
            </header>

            <fieldset class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="published" value="1"
                        <?= !empty($training['enabled_at']) ? 'checked' : '' ?>>
                    <span>Publier la formation</span>
                </label>
                <?php if ($training['enabled_at']): ?>
                    <small>
                        Publié le
                        <time datetime="<?= $training['enabled_at'] ?>">
                            <?= date('d/m/Y à H:i', strtotime($training['enabled_at'])) ?>
                        </time>
                    </small>
                <?php endif; ?>
            </fieldset>

            <fieldset class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="certification" value="1"
                        <?= !empty($training['certification']) ? 'checked' : '' ?>>
                    <span>Formation certifiante</span>
                </label>
                <small>Délivre une certification à l'issue</small>
            </fieldset>
        </section>

        <section class="meta-box">
            <header>
                <h2>Métadonnées</h2>
            </header>

            <fieldset class="form-group">
                <label for="level_slug">Niveau *</label>
                <select name="level_slug" id="level_slug" required>
                    <option value="">Sélectionner un niveau...</option>
                    <?php foreach ($levels as $slug => $label): ?>
                        <option value="<?= $slug ?>"
                            <?= ($training['level_slug'] ?? '') == $slug ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset class="form-group">
                <label for="trainer_id">Formateur</label>
                <select name="trainer_id" id="trainer_id">
                    <option value="">Aucun formateur assigné</option>
                    <?php foreach ($trainers as $trainer): ?>
                        <option value="<?= $trainer['id'] ?>"
                            <?= ($training['trainer_id'] ?? '') == $trainer['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($trainer['label']) ?>
                            <?php if ($trainer['email']): ?>
                                (<?= htmlspecialchars($trainer['email']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small><a href="/admin/trainer/alter" target="_blank">Créer un nouveau formateur</a></small>
            </fieldset>
        </section>

        <section class="media-box">
            <header>
                <h2>Image de la formation</h2>
            </header>

            <?php if (!empty($training['avatar'])): ?>
                <figure class="current-image">
                    <img src="<?= htmlspecialchars($training['avatar']) ?>"
                        alt="Image actuelle" loading="lazy">
                    <figcaption>Image actuelle</figcaption>
                </figure>
            <?php endif; ?>

            <fieldset class="form-group">
                <label for="avatar">
                    <?= !empty($training['avatar']) ? 'Changer l\'image' : 'Ajouter une image' ?>
                </label>
                <input type="file" name="avatar" id="avatar"
                    accept="image/jpeg,image/png,image/webp">
                <small>JPEG, PNG ou WebP. Max 2MB.</small>
            </fieldset>
        </section>

        <?php if ($is_edit): ?>
            <section class="stats-box">
                <header>
                    <h2>Statistiques</h2>
                </header>

                <dl class="stats-list">
                    <dt>Créé le</dt>
                    <dd>
                        <time datetime="<?= $training['created_at'] ?>">
                            <?= date('d/m/Y', strtotime($training['created_at'])) ?>
                        </time>
                    </dd>

                    <?php if ($training['updated_at']): ?>
                        <dt>Modifié le</dt>
                        <dd>
                            <time datetime="<?= $training['updated_at'] ?>">
                                <?= date('d/m/Y à H:i', strtotime($training['updated_at'])) ?>
                            </time>
                        </dd>
                    <?php endif; ?>

                    <dt>Durée totale</dt>
                    <dd>
                        <?= $training['duration_days'] * $training['duration_hours'] ?> heures
                        (<?= $training['duration_days'] ?> jour<?= $training['duration_days'] > 1 ? 's' : '' ?>)
                    </dd>

                    <?php
                    $bookings = dbq(db(), "
                        SELECT COUNT(*) as count 
                        FROM booking 
                        WHERE training_id = ? AND revoked_at IS NULL
                    ", [$training['id']])->fetch();
                    ?>
                    <dt>Inscriptions</dt>
                    <dd>
                        <?= $bookings['count'] ?? 0 ?>
                        <?php if ($training['places_max']): ?>
                            / <?= $training['places_max'] ?>
                        <?php endif; ?>
                    </dd>
                </dl>
            </section>
        <?php endif; ?>
    </aside>

    <footer class="form-actions">
        <button type="submit" class="btn">
            <?= $is_edit ? 'Mettre à jour' : 'Créer la formation' ?>
        </button>
        <a href="/admin/training/list" class="btn secondary">Annuler</a>

        <?php if ($is_edit): ?>
            <button type="submit" name="action" value="delete" class="btn danger"
                data-confirm="Supprimer cette formation et toutes ses inscriptions ?">
                Supprimer
            </button>
        <?php endif; ?>
    </footer>
</form>

<style>
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .training-form .form-main {
        max-width: none;
    }

    .stats-list {
        display: grid;
        gap: 0.5rem;
    }

    .stats-list dt {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }

    .stats-list dd {
        color: #6b7280;
        margin: 0 0 1rem 0;
        font-size: 0.875rem;
    }

    .current-image {
        margin-bottom: 1rem;
    }

    .current-image img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
    }

    .current-image figcaption {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
</style>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/morph/admin_layout.php', ($args ?? []) + ['main' => $this_html, 'css' => '/asset/css/alter.css'])[1];
};
