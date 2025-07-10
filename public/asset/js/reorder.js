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

  // Delegate all drag events on tbody
  tbody.addEventListener('dragstart', handleDragStart);
  tbody.addEventListener('dragover', handleDragOver);
  tbody.addEventListener('drop', handleDrop);
  tbody.addEventListener('dragend', handleDragEnd);

  toggleBtn.addEventListener('click', () => {
    isReorderMode = !isReorderMode;
    table.classList.toggle('sortable-mode', isReorderMode);
    status.style.display = isReorderMode ? 'block' : 'none';
    toggleBtn.innerHTML = `<span class="reorder-text">${
      isReorderMode ? 'Terminer' : 'Réorganiser'
    }</span>`;
    toggleBtn.classList.toggle('btn-primary', isReorderMode);
    toggleBtn.classList.toggle('secondary', !isReorderMode);

    // show/hide handles
    document
      .querySelectorAll('.drag-handle, .drag-header')
      .forEach(
        (el) => (el.style.display = isReorderMode ? 'table-cell' : 'none')
      );

    // toggle draggable on rows
    tbody.querySelectorAll('tr').forEach((row) => {
      row.draggable = isReorderMode;
    });

    // when turning off, save
    if (!isReorderMode) saveOrder();
  });

  function handleDragStart(e) {
    if (!isReorderMode) return;
    const tr = e.target.closest('tr');
    if (!tr) return;
    draggedRow = tr;
    tr.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
  }

  function handleDragOver(e) {
    if (!isReorderMode || !draggedRow) return;
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';

    const target = e.target.closest('tr');
    if (target && target !== draggedRow) {
      const rect = target.getBoundingClientRect();
      const midpoint = rect.top + rect.height / 2;

      tbody
        .querySelectorAll('.drop-zone')
        .forEach((el) => el.classList.remove('drop-zone'));
      if (e.clientY < midpoint) {
        target.classList.add('drop-zone');
      } else {
        const next = target.nextElementSibling;
        if (next) next.classList.add('drop-zone');
      }
    }
  }

  function handleDrop(e) {
    if (!isReorderMode || !draggedRow) return;
    e.preventDefault();

    const target = e.target.closest('tr');
    if (target && target !== draggedRow) {
      const rect = target.getBoundingClientRect();
      const midpoint = rect.top + rect.height / 2;

      if (e.clientY < midpoint) {
        tbody.insertBefore(draggedRow, target);
      } else {
        tbody.insertBefore(draggedRow, target.nextElementSibling);
      }
      updateOrderBadges();
    }
  }

  function handleDragEnd() {
    if (draggedRow) {
      draggedRow.classList.remove('dragging');
      draggedRow = null;
    }
    tbody
      .querySelectorAll('.drop-zone')
      .forEach((el) => el.classList.remove('drop-zone'));
  }

  function updateOrderBadges() {
    tbody.querySelectorAll('tr').forEach((row, i) => {
      const badge = row.querySelector('.order-badge');
      if (badge) badge.textContent = i;
    });
  }

  async function saveOrder() {
    const ids = Array.from(tbody.querySelectorAll('tr')).map(
      (r) => r.dataset.id
    );
    if (!ids.length || !csrfName) return;

    table.classList.add('reorder-saving');
    const form = new URLSearchParams();
    form.append(csrfName, csrfToken);
    ids.forEach((id) => form.append('order[]', id));

    try {
      const res = await fetch('/admin/service/reorder', {
        method: 'GET',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: form,
      });
      if (!res.ok) throw new Error('Erreur de sauvegarde');

      const msg = document.createElement('div');
      msg.className = 'panel success';
      msg.innerHTML = '<p>✅ Ordre sauvegardé avec succès</p>';
      msg.style.margin = '1rem 0';
      table.parentNode.insertBefore(msg, table);
      setTimeout(() => msg.remove(), 3000);
    } catch {
      const err = document.createElement('div');
      err.className = 'panel error';
      err.innerHTML = '<p>❌ Erreur lors de la sauvegarde</p>';
      err.style.margin = '1rem 0';
      table.parentNode.insertBefore(err, table);
      setTimeout(() => err.remove(), 5000);
    } finally {
      table.classList.remove('reorder-saving');
    }
  }
});
