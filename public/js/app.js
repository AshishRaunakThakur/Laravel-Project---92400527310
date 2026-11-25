document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.app-notice').forEach((notice) => {
    window.setTimeout(() => {
      if (notice.isConnected && window.bootstrap?.Alert) {
        window.bootstrap.Alert.getOrCreateInstance(notice).close();
      }
    }, 2700);
  });

  const toggle = document.getElementById('menuToggle');
  const sidebar = document.getElementById('sidebar');
  if (!toggle || !sidebar) return;

  const closeMenu = () => {
    sidebar.classList.remove('sidebar-open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Open navigation');
  };

  toggle.addEventListener('click', () => {
    const isOpen = sidebar.classList.toggle('sidebar-open');
    toggle.setAttribute('aria-expanded', String(isOpen));
    toggle.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
  });

  document.addEventListener('click', (event) => {
    if (window.innerWidth <= 800 && sidebar.classList.contains('sidebar-open')
      && !sidebar.contains(event.target) && !toggle.contains(event.target)) closeMenu();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeMenu();
  });
});
