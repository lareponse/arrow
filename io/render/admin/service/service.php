<?php
// io/render/admin/service/service.php - CSRF protected version
?>
<header class="page-header">
    <h1>Services - Page d'accueil</h1>
    <nav class="page-actions">
        <a href="/admin/service/alter" class="btn">Nouveau service</a>
        <?php if (count($services) > 1): ?>
            <button type="button" id="toggle-reorder" class="btn secondary">
                <span class="reorder-text">Réorganiser</span>
            </button>
        <?php endif; ?>
    </nav>
</header>

<!-- Hidden CSRF token for AJAX -->
<?php if (count($services) > 1): ?>
    <div id="csrf-data" 
         data-token="<?= csrf_token() ?>" 
         data-field="<?= htmlspecialchars(csrf_field(3600)) ?>" 
         style="display:none;"></div>
<?php endif; ?>

<?php if (empty($services)): ?>
    <div class="panel empty-state">
        <p>Aucun service configuré.</p>
        <a href="/admin/service/alter" class="btn">Créer le premier service</a>
    </div>
<?php else: ?>
    <div class="data-table">
        <table id="services-table">
            <thead>
                <tr>
                    <th class="drag-header" style="display:none;">
                        <span class="sr-only">Glisser</span>
                    </th>
                    <th>Ordre</th>
                    <th>Service</th>
                    <th>Aperçu</th>
                    <th>Image</th>
                    <th>Lien</th>
                    <th>Créé</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sortable-services">
                <?php foreach ($services as $service): ?>
                    <tr data-id="<?= $service['id'] ?>">
                        <td class="drag-handle" style="display:none;">
                            <span class="drag-icon">⋮⋮</span>
                        </td>
                        <td>
                            <span class="badge badge--primary order-badge"><?= $service['sort_order'] ?></span>
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

    <div id="reorder-status" class="panel info" style="display:none;">
        <p>✋ <strong>Mode réorganisation activé</strong> - Glissez les lignes pour changer l'ordre, puis cliquez sur "Terminer"</p>
    </div>
<?php endif; ?>

<div class="panel">
    <p><strong>Astuce :</strong> Les services sont affichés sur la page d'accueil par ordre croissant du champ "Ordre".</p>
</div>

<style>
.drag-handle {
    cursor: grab;
    width: 40px;
    text-align: center;
    background: #f9fafb;
    user-select: none;
}

.drag-handle:active {
    cursor: grabbing;
}

.drag-icon {
    color: #9ca3af;
    font-weight: bold;
    line-height: 1;
}

.sortable-mode tbody tr {
    transition: background-color 0.2s;
}

.sortable-mode tbody tr:hover {
    background-color: #f9fafb;
}

.sortable-mode tbody tr.dragging {
    opacity: 0.5;
    background-color: #e5e7eb;
}

.sortable-mode .actions {
    pointer-events: none;
    opacity: 0.5;
}

.drop-zone {
    border-top: 2px solid #3b82f6;
    background-color: #dbeafe;
}

.reorder-saving {
    opacity: 0.7;
    pointer-events: none;
}
</style>

<script type="module">
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-reorder');
    const table = document.getElementById('services-table');
    const tbody = document.getElementById('sortable-services');
    const status = document.getElementById('reorder-status');
    const csrfData = document.getElementById('csrf-data');
    
    if (!toggleBtn || !table || !tbody || !csrfData) return;
    
    let isReorderMode = false;
    let draggedRow = null;
    
    // Extract CSRF data
    const csrfField = csrfData.dataset.field;
    const csrfMatch = csrfField.match(/name="([^"]+)"\s+value="([^"]+)"/);
    const csrfName = csrfMatch ? csrfMatch[1] : '';
    const csrfToken = csrfMatch ? csrfMatch[2] : '';
    
    function toggleReorderMode() {
        isReorderMode = !isReorderMode;
        
        if (isReorderMode) {
            enableSortMode();
        } else {
            disableSortMode();
        }
    }
    
    function enableSortMode() {
        table.classList.add('sortable-mode');
        status.style.display = 'block';
        toggleBtn.innerHTML = '<span class="reorder-text">Terminer</span>';
        toggleBtn.classList.add('btn-primary');
        toggleBtn.classList.remove('secondary');
        
        // Show drag handles
        document.querySelectorAll('.drag-handle, .drag-header').forEach(el => {
            el.style.display = 'table-cell';
        });
        
        // Enable drag events
        tbody.querySelectorAll('tr').forEach(row => {
            row.draggable = true;
            row.addEventListener('dragstart', handleDragStart);
            row.addEventListener('dragover', handleDragOver);
            row.addEventListener('drop', handleDrop);
            row.addEventListener('dragend', handleDragEnd);
        });
    }
    
    function disableSortMode() {
        table.classList.remove('sortable-mode');
        status.style.display = 'none';
        toggleBtn.innerHTML = '<span class="reorder-text">Réorganiser</span>';
        toggleBtn.classList.remove('btn-primary');
        toggleBtn.classList.add('secondary');
        
        // Hide drag handles
        document.querySelectorAll('.drag-handle, .drag-header').forEach(el => {
            el.style.display = 'none';
        });
        
        // Disable drag events
        tbody.querySelectorAll('tr').forEach(row => {
            row.draggable = false;
            row.removeEventListener('dragstart', handleDragStart);
            row.removeEventListener('dragover', handleDragOver);
            row.removeEventListener('drop', handleDrop);
            row.removeEventListener('dragend', handleDragEnd);
        });
        
        saveOrder();
    }
    
    function handleDragStart(e) {
        draggedRow = e.target.closest('tr');
        draggedRow.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
    }
    
    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        
        const targetRow = e.target.closest('tr');
        if (targetRow && targetRow !== draggedRow) {
            const rect = targetRow.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            
            // Remove existing drop zones
            tbody.querySelectorAll('.drop-zone').forEach(el => {
                el.classList.remove('drop-zone');
            });
            
            if (e.clientY < midpoint) {
                targetRow.classList.add('drop-zone');
            } else {
                const nextRow = targetRow.nextElementSibling;
                if (nextRow) {
                    nextRow.classList.add('drop-zone');
                }
            }
        }
    }
    
    function handleDrop(e) {
        e.preventDefault();
        
        const targetRow = e.target.closest('tr');
        if (targetRow && targetRow !== draggedRow) {
            const rect = targetRow.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            
            if (e.clientY < midpoint) {
                tbody.insertBefore(draggedRow, targetRow);
            } else {
                tbody.insertBefore(draggedRow, targetRow.nextElementSibling);
            }
            
            updateOrderBadges();
        }
    }
    
    function handleDragEnd(e) {
        if (draggedRow) {
            draggedRow.classList.remove('dragging');
            draggedRow = null;
        }
        
        // Remove all drop zones
        tbody.querySelectorAll('.drop-zone').forEach(el => {
            el.classList.remove('drop-zone');
        });
    }
    
    function updateOrderBadges() {
        tbody.querySelectorAll('tr').forEach((row, index) => {
            const badge = row.querySelector('.order-badge');
            if (badge) {
                badge.textContent = index;
            }
        });
    }
    
    async function saveOrder() {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const order = rows.map(row => row.dataset.id);
        
        if (order.length === 0 || !csrfName || !csrfToken) return;
        
        // Visual feedback
        table.classList.add('reorder-saving');
        
        try {
            const formData = new URLSearchParams();
            formData.append(csrfName, csrfToken);
            order.forEach(id => formData.append('order[]', id));
            
            const response = await fetch('/admin/service/reorder', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });
            
            if (!response.ok) {
                throw new Error('Erreur de sauvegarde');
            }
            
            // Success feedback
            const successMsg = document.createElement('div');
            successMsg.className = 'panel success';
            successMsg.innerHTML = '<p>✅ Ordre sauvegardé avec succès</p>';
            successMsg.style.margin = '1rem 0';
            
            table.parentNode.insertBefore(successMsg, table);
            
            setTimeout(() => {
                successMsg.remove();
            }, 3000);
            
        } catch (error) {
            // Error feedback
            const errorMsg = document.createElement('div');
            errorMsg.className = 'panel error';
            errorMsg.innerHTML = '<p>❌ Erreur lors de la sauvegarde</p>';
            errorMsg.style.margin = '1rem 0';
            
            table.parentNode.insertBefore(errorMsg, table);
            
            setTimeout(() => {
                errorMsg.remove();
            }, 5000);
        } finally {
            table.classList.remove('reorder-saving');
        }
    }
    
    toggleBtn.addEventListener('click', toggleReorderMode);
});
</script>

<?php
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/admin/layout.php', ($args ?? []) + ['main' => $this_html])[1];
};