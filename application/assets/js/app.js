document.addEventListener('DOMContentLoaded', () => {
  // Confirm deletes
  document.querySelectorAll('[data-confirm]')?.forEach(el => {
    el.addEventListener('click', (e) => {
      if (!confirm(el.getAttribute('data-confirm') || 'Are you sure?')) {
        e.preventDefault();
      }
    });
  });
});