// ServiRapide front-end interactions
(function () {
  const links = document.querySelectorAll('.desktop-nav a, .mobile-tabbar a');
  const sections = [...document.querySelectorAll('main section[id]')];

  function setActiveLink() {
    let current = 'home';
    const offset = 120;
    sections.forEach(section => {
      if (window.scrollY >= section.offsetTop - offset) {
        current = section.id;
      }
    });

    links.forEach(link => {
      const href = link.getAttribute('href') || '';
      link.classList.toggle('active', href === '#' + current);
    });
  }

  window.addEventListener('scroll', setActiveLink);
  setActiveLink();

  // When user clicks a pricing plan, select the plan in the form.
  document.querySelectorAll('[data-plan]').forEach(button => {
    button.addEventListener('click', () => {
      const select = document.getElementById('planSelect');
      if (select) select.value = button.dataset.plan;
    });
  });

  // Small form helper message.
  const form = document.querySelector('.subscribe-form');
  if (form) {
    form.addEventListener('submit', () => {
      const button = form.querySelector('button[type="submit"]');
      if (button) {
        button.textContent = 'Envoi...';
        button.disabled = true;
      }
    });
  }
})();
