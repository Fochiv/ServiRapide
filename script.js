// ServiRapide — script.js
(function () {

  /* ── ACTIVE NAV LINK ─────────────────────────────────── */
  const links    = document.querySelectorAll('.desktop-nav a, .mobile-tabbar a');
  const sections = [...document.querySelectorAll('main section[id]')];

  function setActiveLink() {
    let current = sections[0]?.id || 'home';
    const offset = 130;
    sections.forEach(s => {
      if (window.scrollY >= s.offsetTop - offset) current = s.id;
    });
    links.forEach(l => {
      const href = l.getAttribute('href') || '';
      l.classList.toggle('active', href === '#' + current);
    });
  }

  window.addEventListener('scroll', setActiveLink, { passive: true });
  setActiveLink();

  /* ── PLAN SELECT FROM PRICING CARDS ─────────────────── */
  document.querySelectorAll('[data-plan]').forEach(btn => {
    btn.addEventListener('click', () => {
      const sel = document.getElementById('planSelect');
      if (sel) sel.value = btn.dataset.plan;
    });
  });

  /* ── FORM SUBMIT STATE ───────────────────────────────── */
  const form = document.querySelector('.subscribe-form');
  if (form) {
    form.addEventListener('submit', () => {
      const btn = form.querySelector('button[type="submit"]');
      if (btn) { btn.textContent = 'Envoi…'; btn.disabled = true; }
    });
  }

  /* ── COUNTER ANIMATION ───────────────────────────────── */
  function animateCounter(el) {
    const target   = parseInt(el.dataset.target, 10);
    const duration = 1600;
    const step     = 16;
    const steps    = duration / step;
    let current    = 0;
    const inc      = target / steps;

    const timer = setInterval(() => {
      current += inc;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      el.textContent = Math.floor(current).toLocaleString('fr-FR');
    }, step);
  }

  const counters = document.querySelectorAll('.counter');
  if (counters.length) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(c => obs.observe(c));
  }

  /* ── LANGUAGE SWITCH (no reload) ────────────────────── */
  const translations = window.SR_TRANSLATIONS || {};
  let currentLang    = localStorage.getItem('sr_lang') || window.SR_LANG || 'fr';

  function applyLang(lang) {
    if (!translations[lang]) return;
    currentLang = lang;
    localStorage.setItem('sr_lang', lang);

    const t = translations[lang];

    // Update all data-i18n elements
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.dataset.i18n;
      if (t[key] !== undefined) {
        // For labels (form labels), update the text node but not child inputs/selects
        if (el.tagName === 'LABEL') {
          const firstTextNode = [...el.childNodes].find(n => n.nodeType === 3);
          if (firstTextNode) firstTextNode.textContent = t[key];
        } else {
          el.textContent = t[key];
        }
      }
    });

    // Update <html lang>
    document.documentElement.lang = t.html_lang || lang;
    document.documentElement.setAttribute('data-lang', lang);

    // Update <title>
    document.title = t.title || document.title;

    // Update lang button
    const langBtn = document.getElementById('langToggle');
    if (langBtn) langBtn.textContent = t.switch || (lang === 'fr' ? 'EN' : 'FR');
  }

  // Apply stored preference if different from PHP render
  if (currentLang !== window.SR_LANG) {
    applyLang(currentLang);
  }

  // Toggle on button click
  const langToggle = document.getElementById('langToggle');
  if (langToggle) {
    langToggle.addEventListener('click', () => {
      const next = currentLang === 'fr' ? 'en' : 'fr';
      applyLang(next);
    });
  }

  /* ── SCROLL-IN ANIMATION ─────────────────────────────── */
  const fadeEls = document.querySelectorAll(
    '.service-card, .about-card, .price-card, .step-card, .photo-box'
  );
  if (fadeEls.length && 'IntersectionObserver' in window) {
    fadeEls.forEach(el => el.classList.add('fade-init'));
    const fadeObs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('fade-in');
          fadeObs.unobserve(e.target);
        }
      });
    }, { threshold: 0.12 });
    fadeEls.forEach(el => fadeObs.observe(el));
  }

})();
