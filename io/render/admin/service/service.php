<?php
// io/render/admin/service/service.php
?>
<header class="page-header">
    <h1>Services - Page d'accueil</h1>
    <nav class="page-actions">
        <a href="/admin/service/alter" class="btn">Nouveau service</a>
    </nav>
</header>

<?php if (empty($services)): ?>
    <div class="panel empty-state">
        <p>Aucun service configuré.</p>
        <a href="/admin/service/alter" class="btn">Créer le premier service</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Service</th>
                    <th>Aperçu</th>
                    <th>Image</th>
                    <th>Lien</th>
                    <th>Créé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td>
                            <span class="badge badge--primary"><?= $service['sort_order'] ?></span>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($service['label']) ?></strong>
                        </td>
                        <td>
                            <small><?= htmlspecialchars($service['preview']) ?><?= strlen($service['preview']) >= 100 ? '...' : '' ?></small>
                        </td>
                        <td>
                            <?php if ($service['image_src']): ?>
                                <img src="<?= htmlspecialchars($service['image_src']) ?>"
                                    alt="<?= htmlspecialchars($service['label']) ?>"
                                    style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <span class="text-muted">Aucune</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($service['link']): ?>
                                <a href="<?= htmlspecialchars($service['link']) ?>" target="_blank" class="btn small secondary">
                                    <?= htmlspecialchars($service['link_text'] ?: 'Voir') ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Aucun</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <time datetime="<?= $service['created_at'] ?>">
                                <?= date('d/m/Y', strtotime($service['created_at'])) ?>
                            </time>
                        </td>
                        <td class="actions">
                            <a href="/admin/service/alter/<?= $service['id'] ?>" class="btn small">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="panel">
    <p><strong>Astuce :</strong> Les services sont affichés sur la page d'accueil par ordre croissant du champ "Ordre".</p>
</div>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};
