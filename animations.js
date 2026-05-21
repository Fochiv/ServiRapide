/* ============================================================
   ServiRapide — animations.js
   Colle ce fichier dans C:\xampp\htdocs\servi\
   Puis ajoute dans index.php, login.php, dashboard_user.php,
   dashboard_admin.php AVANT </body> :
   <script src="animations.js"></script>
   ============================================================ */

(function () {

  /* ── RIPPLE sur tous les boutons ───────────────────────────── */
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
      const circle = document.createElement('span');
      const rect   = btn.getBoundingClientRect();
      const size   = Math.max(rect.width, rect.height);
      circle.style.cssText = `
        position:absolute;
        width:${size}px;height:${size}px;
        left:${e.clientX - rect.left - size/2}px;
        top:${e.clientY - rect.top  - size/2}px;
        background:rgba(255,255,255,.3);
        border-radius:50%;
        transform:scale(0);
        animation:rippleAnim .6s linear;
        pointer-events:none;
      `;
      if (!document.getElementById('ripple-style')) {
        const st = document.createElement('style');
        st.id = 'ripple-style';
        st.textContent = `
          @keyframes rippleAnim {
            to { transform:scale(4); opacity:0; }
          }
        `;
        document.head.appendChild(st);
      }
      btn.appendChild(circle);
      setTimeout(() => circle.remove(), 700);
    });
  });

  /* ── TYPEWRITER sur le titre hero ──────────────────────────── */
  const heroTitle = document.querySelector('.hero h1');
  if (heroTitle) {
    const text = heroTitle.textContent;
    heroTitle.textContent = '';
    heroTitle.style.borderRight = '3px solid var(--green)';
    let i = 0;
    const type = () => {
      if (i < text.length) {
        heroTitle.textContent += text[i++];
        setTimeout(type, 38);
      } else {
        setTimeout(() => { heroTitle.style.borderRight = 'none'; }, 800);
      }
    };
    setTimeout(type, 600);
  }

  /* ── COMPTEURS animés ───────────────────────────────────────── */
  function animateCounter(el) {
    const target   = parseInt(el.dataset.target, 10);
    const duration = 1800;
    const steps    = 60;
    const inc      = target / steps;
    let current    = 0;
    const timer = setInterval(() => {
      current += inc;
      if (current >= target) { current = target; clearInterval(timer); }
      el.textContent = Math.floor(current).toLocaleString('fr-FR');
    }, duration / steps);
  }

  const counters = document.querySelectorAll('.counter');
  if (counters.length && 'IntersectionObserver' in window) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) { animateCounter(e.target); obs.unobserve(e.target); }
      });
    }, { threshold: 0.5 });
    counters.forEach(c => obs.observe(c));
  }

  /* ── SCROLL FADE entrée progressive ────────────────────────── */
  const fadeEls = document.querySelectorAll(
    '.service-card, .about-card, .price-card, .step-card, .photo-box, .kpi-card, .stat-card, .notif-item'
  );
  if (fadeEls.length && 'IntersectionObserver' in window) {
    fadeEls.forEach((el, i) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(30px)';
      el.style.transition = `opacity .55s ease ${i * 0.07}s, transform .55s cubic-bezier(.34,1.56,.64,1) ${i * 0.07}s`;
    });
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.opacity = '1';
          e.target.style.transform = 'translateY(0)';
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.1 });
    fadeEls.forEach(el => obs.observe(el));
  }

  /* ── HEADER scroll effect ───────────────────────────────────── */
  const header = document.querySelector('.site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 50) {
        header.style.boxShadow = '0 4px 30px rgba(0,0,0,.3)';
        header.style.backdropFilter = 'blur(10px)';
      } else {
        header.style.boxShadow = '0 2px 20px rgba(0,0,0,.2)';
        header.style.backdropFilter = 'none';
      }
    }, { passive: true });
  }

  /* ── BOUTON WhatsApp — pulsation ────────────────────────────── */
  const waBtns = document.querySelectorAll('.btn-whatsapp');
  waBtns.forEach(btn => {
    setInterval(() => {
      btn.style.transform = 'translateY(-2px) scale(1.04)';
      setTimeout(() => { btn.style.transform = ''; }, 400);
    }, 3000);
  });

  /* ── CARDS 3D tilt au survol ────────────────────────────────── */
  document.querySelectorAll('.service-card, .price-card, .about-card').forEach(card => {
    card.addEventListener('mousemove', function (e) {
      const rect   = card.getBoundingClientRect();
      const x      = e.clientX - rect.left;
      const y      = e.clientY - rect.top;
      const cx     = rect.width  / 2;
      const cy     = rect.height / 2;
      const rotX   = ((y - cy) / cy) * -6;
      const rotY   = ((x - cx) / cx) *  6;
      card.style.transform    = `perspective(800px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-8px)`;
      card.style.transition   = 'transform .1s';
    });
    card.addEventListener('mouseleave', function () {
      card.style.transform  = '';
      card.style.transition = 'transform .4s cubic-bezier(.34,1.56,.64,1)';
    });
  });

  /* ── TOAST de bienvenue ─────────────────────────────────────── */
  const isHome = window.location.pathname.includes('index');
  if (isHome && !sessionStorage.getItem('sr_welcomed')) {
    sessionStorage.setItem('sr_welcomed', '1');
    setTimeout(() => {
      const toast = document.createElement('div');
      toast.style.cssText = `
        position:fixed; bottom:90px; right:20px; z-index:9999;
        background:var(--dark); color:#fff;
        padding:14px 20px; border-radius:16px;
        box-shadow:0 8px 32px rgba(0,0,0,.3);
        font-family:'Outfit',sans-serif; font-size:14px; font-weight:700;
        display:flex; align-items:center; gap:10px;
        animation:toastIn .5s cubic-bezier(.34,1.56,.64,1) both;
        max-width:280px;
      `;
      toast.innerHTML = `
        <span style="font-size:20px">👋</span>
        <span>Bienvenue sur <span style="color:var(--green)">ServiRapide</span> !</span>
      `;
      if (!document.getElementById('toast-style')) {
        const st = document.createElement('style');
        st.id = 'toast-style';
        st.textContent = `
          @keyframes toastIn  { from { opacity:0; transform:translateY(20px) scale(.9); } }
          @keyframes toastOut { to   { opacity:0; transform:translateY(20px) scale(.9); } }
        `;
        document.head.appendChild(st);
      }
      document.body.appendChild(toast);
      setTimeout(() => {
        toast.style.animation = 'toastOut .4s ease forwards';
        setTimeout(() => toast.remove(), 400);
      }, 3500);
    }, 1200);
  }

  /* ── SMOOTH scroll pour les liens d'ancrage ─────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ── ACTIVE NAV LINK ────────────────────────────────────────── */
  const links    = document.querySelectorAll('.desktop-nav a, .mobile-tabbar a');
  const sections = [...document.querySelectorAll('main section[id]')];
  function setActiveLink() {
    let current = sections[0]?.id || 'home';
    sections.forEach(s => {
      if (window.scrollY >= s.offsetTop - 130) current = s.id;
    });
    links.forEach(l => {
      const href = l.getAttribute('href') || '';
      l.classList.toggle('active', href === '#' + current);
    });
  }
  window.addEventListener('scroll', setActiveLink, { passive: true });
  setActiveLink();

  /* ── PLAN SELECT depuis les cartes tarifs ───────────────────── */
  document.querySelectorAll('[data-plan]').forEach(btn => {
    btn.addEventListener('click', () => {
      const sel = document.getElementById('planSelect');
      if (sel) sel.value = btn.dataset.plan;
    });
  });

})();
