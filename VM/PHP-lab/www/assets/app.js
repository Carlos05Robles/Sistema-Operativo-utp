/* Nimbus — interacciones de demostración. */
(() => {
  'use strict';

  const body = document.body;
  const toast = document.getElementById('toast');
  let toastTimer = null;

  function showToast(message) {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('show'), 2800);
  }

  // Botones y zonas con mensaje de demostración.
  document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-toast]');
    if (trigger) {
      event.preventDefault();
      showToast(trigger.getAttribute('data-toast') || 'Acción de demostración');
    }
  });

  // Menú lateral en móvil.
  document.querySelectorAll('[data-sidebar-open]').forEach((el) => {
    el.addEventListener('click', () => body.classList.add('sidebar-open'));
  });

  document.querySelectorAll('[data-sidebar-close]').forEach((el) => {
    el.addEventListener('click', () => body.classList.remove('sidebar-open'));
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') body.classList.remove('sidebar-open');
  });

  // Filtro de tablas.
  document.querySelectorAll('[data-filter-table]').forEach((input) => {
    const table = document.getElementById(input.getAttribute('data-filter-table'));
    if (!table) return;

    input.addEventListener('input', () => {
      const needle = input.value.trim().toLowerCase();
      table.querySelectorAll('tbody tr').forEach((row) => {
        const haystack = (row.getAttribute('data-search') || row.textContent || '').toLowerCase();
        row.hidden = needle !== '' && !haystack.includes(needle);
      });
    });
  });

  // Formularios de demostración: no envían datos.
  document.querySelectorAll('[data-demo-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      showToast(form.getAttribute('data-demo-form') || 'Formulario de demostración');
    });
  });

  // Atajo de búsqueda.
  document.addEventListener('keydown', (event) => {
    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
      const search = document.querySelector('.search input, [data-filter-table]');
      if (search) {
        event.preventDefault();
        search.focus();
      }
    }
  });
})();
