document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.getElementById('menuToggle');
  const overlay = document.getElementById('menuOverlay');

  if (toggle && overlay) {
    toggle.addEventListener('click', () => {
      overlay.classList.toggle('active');
    });

    document.querySelectorAll('.overlay-nav a').forEach(link => {
      link.addEventListener('click', () => {
        overlay.classList.remove('active');
      });
    });
  }
});
