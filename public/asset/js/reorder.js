// reorder-table.js
const ReorderTable = (() => {
  let isReorderMode = false;
  let draggedRow = null;
  // lookup or error
  function find(hook) {
    const el = document.querySelector(hook);
    if (!el) console.error(`ReorderTable: missing ${hook}`);
    return el;
  }

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
    const target = e.target.closest('tr');
    if (target && target !== draggedRow) {
      const { top, height } = target.getBoundingClientRect();
      const midpoint = top + height / 2;
      this.querySelectorAll('.drop-zone').forEach((el) =>
        el.classList.remove('drop-zone')
      );
      if (e.clientY < midpoint) {
        target.classList.add('drop-zone');
      } else if (target.nextElementSibling) {
        target.nextElementSibling.classList.add('drop-zone');
      }
    }
  }

  function handleDrop(e) {
    if (!isReorderMode || !draggedRow) return;
    e.preventDefault();
    const target = e.target.closest('tr');
    if (target && target !== draggedRow) {
      const { top, height } = target.getBoundingClientRect();
      const midpoint = top + height / 2;
      if (e.clientY < midpoint) {
        this.insertBefore(draggedRow, target);
      } else {
        this.insertBefore(draggedRow, target.nextElementSibling);
      }
      updateOrderBadges(this);
    }
  }

  function handleDragEnd(e) {
    if (draggedRow) {
      draggedRow.classList.remove('dragging');
      draggedRow = null;
    }
    this.querySelectorAll('.drop-zone').forEach((el) =>
      el.classList.remove('drop-zone')
    );
  }

  function updateOrderBadges(tbody) {
    tbody.querySelectorAll('tr').forEach((row, i) => {
      const badge = row.querySelector('.order-badge');
      if (badge) badge.textContent = i;
    });
  }

async function saveOrder(table, tbody, msgRow) {
  // 1) Gather the IDs in new order
  const ids = Array.from(tbody.rows).map((r) => r.dataset.id);

  // 2) Pull URL, CSRF name & token from your data-attributes
  const url = table.dataset.reorderUrl;
  const csrfName = table.dataset.csrfName;
  const csrfToken = table.dataset.csrfToken;

  if (!csrfToken || csrfToken === 'missing') {
    console.error('ReorderTable: CSRF token is missing!');
    // Optionally you could display an error message here and abort:
    return;
  }

  // 3) Build the form body
  const form = new URLSearchParams();
  form.append(csrfName, csrfToken);
  ids.forEach((id) => form.append('order[]', id));

  table.classList.add('reorder-saving');
  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: form,
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);

    // show success
    msgRow.hidden = false;
    msgRow.querySelector('[data-message="success"]').hidden = false;
    msgRow.querySelector('[data-message="error"]').hidden = true;
    setTimeout(() => (msgRow.hidden = true), 3000);
  } catch (err) {
    console.error('Reorder save failed:', err);
    // show error
    msgRow.hidden = false;
    msgRow.querySelector('[data-message="success"]').hidden = true;
    msgRow.querySelector('[data-message="error"]').hidden = false;
    setTimeout(() => (msgRow.hidden = true), 5000);
  } finally {
    table.classList.remove('reorder-saving');
  }
}


  function init() {
    const table = find('[data-widget="reorder"]');
    const toggleBtn = find('[data-widget="reorder-toggle"]');
    const tbody = table.querySelector('tbody');
    const msgRow = find('tfoot [data-role="reorder-message-row"]');

    if (!table || !toggleBtn || !tbody || !msgRow) {
      console.error('ReorderTable: init aborted');
      return;
    }

    // wire drag/drop on tbody…
    tbody.addEventListener('dragstart', handleDragStart);
    tbody.addEventListener('dragover', handleDragOver);
    tbody.addEventListener('drop', handleDrop);
    tbody.addEventListener('dragend', handleDragEnd);

    // wire toggle
    toggleBtn.addEventListener('click', () => {
      isReorderMode = !isReorderMode;
      table.classList.toggle('sortable-mode', isReorderMode);
      toggleBtn.classList.toggle('btn-primary', isReorderMode);
      toggleBtn.classList.toggle('secondary', !isReorderMode);

      // swap the text
      const text = isReorderMode
        ? toggleBtn.dataset.textOn
        : toggleBtn.dataset.textOff;
      toggleBtn.querySelector('.reorder-text').textContent = text;

      // show/hide handles & enable dragging
      document
        .querySelectorAll('.drag-handle, .drag-header')
        .forEach(
          (el) => (el.style.display = isReorderMode ? 'table-cell' : 'none')
        );
      tbody
        .querySelectorAll('tr')
        .forEach((r) => (r.draggable = isReorderMode));

      // if we just turned off, persist
      if (!isReorderMode) saveOrder(table, tbody, msgRow);
    });
  }

  return { init };

})();

export default ReorderTable;