<?php
/*
  ServiRapide - single PHP page website
  Files needed in the same folder:
  - index.php
  - style.css
  - script.js
*/
$lang = $_GET['lang'] ?? 'fr';
$lang = in_array($lang, ['fr', 'en'], true) ? $lang : 'fr';

$text = [
  'fr' => [
    'html_lang' => 'fr',
    'switch' => 'EN',
    'title' => 'ServiRapide — Services à domicile à Douala',
    'brand' => 'ServiRapide',
    'slogan' => 'Zéro effort, zéro stress.',
    'location' => 'Douala, Cameroun',
    'home' => 'Accueil',
    'services' => 'Services',
    'pricing' => 'Tarifs',
    'register' => "S’abonner",
    'dashboard' => 'Mon compte',
    'contact' => 'Contact',
    'hero_badge' => '📍 Douala, Cameroun',
    'hero_title' => 'Vos tâches ménagères, enfin simplifiées.',
    'hero_subtitle' => 'Abonnez-vous et profitez de services à domicile professionnels : nettoyage, lessive, repassage, vaisselle et services bonus.',
    'cta_primary' => 'S’abonner maintenant',
    'cta_secondary' => 'Voir les services',
    'stats_1' => 'Familles servies',
    'stats_2' => 'Note moyenne',
    'stats_3' => 'Satisfaction',
    'services_title' => 'Nos services',
    'services_subtitle' => 'Tout ce dont votre foyer a besoin, livré à votre porte.',
    'cleaning' => 'Nettoyage',
    'cleaning_desc' => 'Chambres, salon, cuisine, salle de bain et surfaces.',
    'laundry' => 'Lessive',
    'laundry_desc' => 'Lavage, rinçage, séchage et pliage du linge.',
    'ironing' => 'Repassage',
    'ironing_desc' => 'Repassage professionnel et classement des vêtements.',
    'dishes' => 'Vaisselle',
    'dishes_desc' => 'Lavage, rangement et nettoyage de la cuisine.',
    'bonus_title' => 'Services bonus offerts aux membres',
    'bonus_subtitle' => 'Plomberie simple, couture et réparation de chaussures inclus pour les abonnés actifs.',
    'pricing_title' => 'Nos formules',
    'pricing_subtitle' => 'Des prix simples et accessibles pour chaque foyer.',
    'plan_a' => 'Catégorie A',
    'plan_a_people' => '1 à 3 personnes',
    'plan_b' => 'Catégorie B',
    'plan_b_people' => '4 personnes et plus',
    'month' => '/mois',
    'signup_fee' => 'Frais d’inscription : 10 000 CFA une seule fois',
    'first_free' => 'Premier service offert',
    'priority' => 'Interventions prioritaires',
    'bonus_included' => 'Services bonus inclus',
    'choose' => 'Choisir cette formule',
    'how_title' => 'Comment ça marche ?',
    'step1' => 'Inscription',
    'step1_desc' => 'Créez votre compte et payez les frais d’inscription.',
    'step2' => 'Choix de la formule',
    'step2_desc' => 'Sélectionnez Catégorie A ou Catégorie B.',
    'step3' => 'Planification',
    'step3_desc' => 'Choisissez vos jours, horaires et services.',
    'step4' => 'Intervention',
    'step4_desc' => 'L’équipe ServiRapide intervient à domicile.',
    'step5' => 'Suivi',
    'step5_desc' => 'Suivez vos services depuis votre espace membre.',
    'gallery_title' => 'Notre travail en images',
    'gallery_subtitle' => 'Remplacez ces cadres par de vraies photos des prestations.',
    'dashboard_title' => 'Aperçu espace membre',
    'dashboard_subtitle' => 'Le client peut voir ses services restants, son prochain rendez-vous et son historique.',
    'hello' => 'Bonjour, Marie 👋',
    'active_plan' => 'Formule A · Actif',
    'next_service' => 'Prochain service',
    'monthly_progress' => 'Progression mensuelle',
    'history' => 'Historique',
    'done' => 'Terminé',
    'register_title' => 'Créer mon abonnement',
    'register_subtitle' => 'Formulaire simple pour commencer avec ServiRapide.',
    'name' => 'Nom complet',
    'address' => 'Adresse',
    'neighborhood' => 'Quartier',
    'phone' => 'Téléphone',
    'email' => 'Email',
    'plan' => 'Formule',
    'preferred_day' => 'Jour préféré',
    'payment' => 'Mode de paiement',
    'submit' => 'Envoyer la demande',
    'form_note' => 'Après validation, le client reçoit les instructions de paiement Mobile Money.',
    'contact_title' => 'Contactez-nous',
    'contact_subtitle' => 'Disponibles 7j/7 de 7h à 21h.',
    'whatsapp_cta' => 'Écrire sur WhatsApp',
    'footer_text' => '© 2025 ServiRapide · Douala, Cameroun · Tous droits réservés.',
    'message_success' => 'Merci ! Votre demande a été préparée. Dans une vraie version, elle sera enregistrée dans la base de données.'
  ],
  'en' => [
    'html_lang' => 'en',
    'switch' => 'FR',
    'title' => 'ServiRapide — Home services in Douala',
    'brand' => 'ServiRapide',
    'slogan' => 'Zero effort, zero stress.',
    'location' => 'Douala, Cameroon',
    'home' => 'Home',
    'services' => 'Services',
    'pricing' => 'Pricing',
    'register' => 'Subscribe',
    'dashboard' => 'My account',
    'contact' => 'Contact',
    'hero_badge' => '📍 Douala, Cameroon',
    'hero_title' => 'Your household tasks, finally simplified.',
    'hero_subtitle' => 'Subscribe and enjoy professional home services: cleaning, laundry, ironing, dishwashing and bonus services.',
    'cta_primary' => 'Subscribe now',
    'cta_secondary' => 'View services',
    'stats_1' => 'Families served',
    'stats_2' => 'Average rating',
    'stats_3' => 'Satisfaction',
    'services_title' => 'Our services',
    'services_subtitle' => 'Everything your home needs, delivered to your door.',
    'cleaning' => 'Cleaning',
    'cleaning_desc' => 'Bedrooms, living room, kitchen, bathroom and surfaces.',
    'laundry' => 'Laundry',
    'laundry_desc' => 'Washing, rinsing, drying and folding clothes.',
    'ironing' => 'Ironing',
    'ironing_desc' => 'Professional ironing and clothing organization.',
    'dishes' => 'Dishwashing',
    'dishes_desc' => 'Dish cleaning, storage and kitchen care.',
    'bonus_title' => 'Bonus services for members',
    'bonus_subtitle' => 'Simple plumbing, sewing and shoe repair included for active subscribers.',
    'pricing_title' => 'Our plans',
    'pricing_subtitle' => 'Simple and affordable prices for every household.',
    'plan_a' => 'Category A',
    'plan_a_people' => '1 to 3 people',
    'plan_b' => 'Category B',
    'plan_b_people' => '4 people and more',
    'month' => '/month',
    'signup_fee' => 'Registration fee: 10,000 CFA one time only',
    'first_free' => 'First service free',
    'priority' => 'Priority interventions',
    'bonus_included' => 'Bonus services included',
    'choose' => 'Choose this plan',
    'how_title' => 'How it works',
    'step1' => 'Register',
    'step1_desc' => 'Create your account and pay the registration fee.',
    'step2' => 'Choose a plan',
    'step2_desc' => 'Select Category A or Category B.',
    'step3' => 'Schedule',
    'step3_desc' => 'Choose your days, time slots and services.',
    'step4' => 'Intervention',
    'step4_desc' => 'The ServiRapide team comes to your home.',
    'step5' => 'Track',
    'step5_desc' => 'Track your services from your member area.',
    'gallery_title' => 'Our work in pictures',
    'gallery_subtitle' => 'Replace these blocks with real service photos.',
    'dashboard_title' => 'Member dashboard preview',
    'dashboard_subtitle' => 'Clients can see remaining services, upcoming bookings and history.',
    'hello' => 'Hello, Marie 👋',
    'active_plan' => 'Plan A · Active',
    'next_service' => 'Upcoming service',
    'monthly_progress' => 'Monthly progress',
    'history' => 'History',
    'done' => 'Completed',
    'register_title' => 'Create my subscription',
    'register_subtitle' => 'A simple form to get started with ServiRapide.',
    'name' => 'Full name',
    'address' => 'Address',
    'neighborhood' => 'Neighborhood',
    'phone' => 'Phone',
    'email' => 'Email',
    'plan' => 'Plan',
    'preferred_day' => 'Preferred day',
    'payment' => 'Payment method',
    'submit' => 'Send request',
    'form_note' => 'After validation, the client receives Mobile Money payment instructions.',
    'contact_title' => 'Contact us',
    'contact_subtitle' => 'Available 7 days/week from 7am to 9pm.',
    'whatsapp_cta' => 'Chat on WhatsApp',
    'footer_text' => '© 2025 ServiRapide · Douala, Cameroon · All rights reserved.',
    'message_success' => 'Thank you! Your request was prepared. In the real version, it will be stored in the database.'
  ]
];
$t = $text[$lang];
$otherLang = $lang === 'fr' ? 'en' : 'fr';
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="<?= e($t['html_lang']) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($t['title']) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Sora:wght@600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <div class="container nav-wrap">
      <a href="#home" class="brand">
        <span class="brand-icon">🏠</span>
        <span>
          <strong><?= e($t['brand']) ?></strong>
          <small><?= e($t['slogan']) ?></small>
        </span>
      </a>
      <nav class="desktop-nav" aria-label="Main navigation">
        <a href="#home"><?= e($t['home']) ?></a>
        <a href="#services"><?= e($t['services']) ?></a>
        <a href="#pricing"><?= e($t['pricing']) ?></a>
        <a href="#register"><?= e($t['register']) ?></a>
        <a href="#dashboard"><?= e($t['dashboard']) ?></a>
        <a href="#contact"><?= e($t['contact']) ?></a>
      </nav>
      <a class="lang-btn" href="?lang=<?= e($otherLang) ?>"><?= e($t['switch']) ?></a>
    </div>
  </header>

  <main>
    <section id="home" class="hero section-dark">
      <div class="hero-bg"></div>
      <div class="container hero-inner">
        <div class="hero-copy">
          <span class="badge"><?= e($t['hero_badge']) ?></span>
          <h1><?= e($t['hero_title']) ?></h1>
          <p><?= e($t['hero_subtitle']) ?></p>
          <div class="hero-actions">
            <a class="btn btn-primary" href="#register"><?= e($t['cta_primary']) ?></a>
            <a class="btn btn-outline-light" href="#services"><?= e($t['cta_secondary']) ?></a>
          </div>
        </div>
        <div class="hero-card" aria-label="ServiRapide illustration">
          <div class="house-logo">
            <span>🏠</span>
            <span>🧹</span>
          </div>
          <h3><?= e($t['brand']) ?></h3>
          <p><?= e($t['slogan']) ?></p>
        </div>
      </div>
    </section>

    <section class="stats-bar">
      <div class="container stats-grid">
        <div><strong>500+</strong><span><?= e($t['stats_1']) ?></span></div>
        <div><strong>4.9 ⭐</strong><span><?= e($t['stats_2']) ?></span></div>
        <div><strong>100%</strong><span><?= e($t['stats_3']) ?></span></div>
      </div>
    </section>

    <section id="services" class="section">
      <div class="container">
        <div class="section-title">
          <h2><?= e($t['services_title']) ?></h2>
          <p><?= e($t['services_subtitle']) ?></p>
        </div>
        <div class="service-grid">
          <article class="service-card"><div class="service-icon">🧹</div><h3><?= e($t['cleaning']) ?></h3><p><?= e($t['cleaning_desc']) ?></p></article>
          <article class="service-card"><div class="service-icon">👕</div><h3><?= e($t['laundry']) ?></h3><p><?= e($t['laundry_desc']) ?></p></article>
          <article class="service-card"><div class="service-icon">👔</div><h3><?= e($t['ironing']) ?></h3><p><?= e($t['ironing_desc']) ?></p></article>
          <article class="service-card"><div class="service-icon">🍽️</div><h3><?= e($t['dishes']) ?></h3><p><?= e($t['dishes_desc']) ?></p></article>
        </div>
      </div>
    </section>

    <section class="section bonus-section">
      <div class="container bonus-card">
        <span class="badge green">✨ <?= e($t['bonus_title']) ?></span>
        <p><?= e($t['bonus_subtitle']) ?></p>
        <div class="bonus-list">
          <span>🔧 Plomberie</span>
          <span>🧵 Couture</span>
          <span>👟 Cordonnerie</span>
        </div>
      </div>
    </section>

    <section id="pricing" class="section">
      <div class="container">
        <div class="section-title">
          <h2><?= e($t['pricing_title']) ?></h2>
          <p><?= e($t['pricing_subtitle']) ?></p>
        </div>
        <div class="pricing-grid">
          <article class="price-card">
            <span class="plan-label"><?= e($t['plan_a_people']) ?></span>
            <h3><?= e($t['plan_a']) ?></h3>
            <div class="price">5 000 <small>CFA<?= e($t['month']) ?></small></div>
            <p class="muted"><?= e($t['signup_fee']) ?></p>
            <ul>
              <li>✓ <?= e($t['first_free']) ?></li>
              <li>✓ <?= e($t['priority']) ?></li>
              <li>✓ <?= e($t['bonus_included']) ?></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="A"><?= e($t['choose']) ?></a>
          </article>
          <article class="price-card featured">
            <span class="popular">Famille</span>
            <span class="plan-label"><?= e($t['plan_b_people']) ?></span>
            <h3><?= e($t['plan_b']) ?></h3>
            <div class="price">8 000 <small>CFA<?= e($t['month']) ?></small></div>
            <p class="muted"><?= e($t['signup_fee']) ?></p>
            <ul>
              <li>✓ <?= e($t['first_free']) ?></li>
              <li>✓ <?= e($t['priority']) ?></li>
              <li>✓ <?= e($t['bonus_included']) ?></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="B"><?= e($t['choose']) ?></a>
          </article>
        </div>
      </div>
    </section>

    <section class="section section-dark steps-section">
      <div class="container">
        <div class="section-title light">
          <h2><?= e($t['how_title']) ?></h2>
        </div>
        <div class="steps-grid">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <article class="step-card">
              <span><?= str_pad((string)$i, 2, '0', STR_PAD_LEFT) ?></span>
              <h3><?= e($t['step'.$i]) ?></h3>
              <p><?= e($t['step'.$i.'_desc']) ?></p>
            </article>
          <?php endfor; ?>
        </div>
      </div>
    </section>

    <section class="section gallery-section">
      <div class="container">
        <div class="section-title">
          <h2><?= e($t['gallery_title']) ?></h2>
          <p><?= e($t['gallery_subtitle']) ?></p>
        </div>
        <div class="gallery-grid">
          <div class="photo-box">📷</div>
          <div class="photo-box">📷</div>
          <div class="photo-box">📷</div>
        </div>
      </div>
    </section>

    <section id="dashboard" class="section dashboard-section">
      <div class="container dashboard-layout">
        <div>
          <div class="section-title left">
            <h2><?= e($t['dashboard_title']) ?></h2>
            <p><?= e($t['dashboard_subtitle']) ?></p>
          </div>
          <a class="btn btn-primary" href="#register"><?= e($t['cta_primary']) ?></a>
        </div>
        <div class="dashboard-card">
          <div class="dash-head">
            <div><h3><?= e($t['hello']) ?></h3><span><?= e($t['active_plan']) ?></span></div>
            <span class="status-dot"></span>
          </div>
          <div class="next-box"><small><?= e($t['next_service']) ?></small><strong>Nettoyage · Lun 27 Jan · 9h00</strong></div>
          <h4><?= e($t['monthly_progress']) ?></h4>
          <div class="progress-row"><span><?= e($t['cleaning']) ?></span><em>2/3</em></div><div class="progress"><span style="width:66%"></span></div>
          <div class="progress-row"><span><?= e($t['laundry']) ?></span><em>1/2</em></div><div class="progress"><span style="width:50%"></span></div>
          <div class="progress-row"><span><?= e($t['ironing']) ?></span><em>1/2</em></div><div class="progress"><span style="width:50%"></span></div>
          <div class="history-box"><strong><?= e($t['history']) ?></strong><p>20 Jan · <?= e($t['cleaning']) ?> · <?= e($t['done']) ?></p></div>
        </div>
      </div>
    </section>

    <section id="register" class="section register-section">
      <div class="container form-layout">
        <div class="section-title left">
          <h2><?= e($t['register_title']) ?></h2>
          <p><?= e($t['register_subtitle']) ?></p>
          <p class="form-note"><?= e($t['form_note']) ?></p>
        </div>
        <form class="subscribe-form" method="post" action="#register">
          <?php if ($submitted): ?>
            <div class="success-message"><?= e($t['message_success']) ?></div>
          <?php endif; ?>
          <label><?= e($t['name']) ?><input type="text" name="name" required></label>
          <label><?= e($t['address']) ?><input type="text" name="address" required></label>
          <label><?= e($t['neighborhood']) ?><input type="text" name="neighborhood" required></label>
          <div class="two-cols">
            <label><?= e($t['phone']) ?><input type="tel" name="phone" required></label>
            <label><?= e($t['email']) ?><input type="email" name="email"></label>
          </div>
          <div class="two-cols">
            <label><?= e($t['plan']) ?><select name="plan" id="planSelect"><option value="A"><?= e($t['plan_a']) ?> - 5 000 CFA</option><option value="B"><?= e($t['plan_b']) ?> - 8 000 CFA</option></select></label>
            <label><?= e($t['payment']) ?><select name="payment"><option>MTN MoMo</option><option>Orange Money</option><option>Virement bancaire</option></select></label>
          </div>
          <label><?= e($t['preferred_day']) ?><select name="day"><option>Lundi</option><option>Mardi</option><option>Mercredi</option><option>Jeudi</option><option>Vendredi</option><option>Samedi</option></select></label>
          <button class="btn btn-primary full" type="submit"><?= e($t['submit']) ?></button>
        </form>
      </div>
    </section>

    <section id="contact" class="section contact-section section-dark">
      <div class="container contact-grid">
        <div>
          <h2><?= e($t['contact_title']) ?></h2>
          <p><?= e($t['contact_subtitle']) ?></p>
        </div>
        <div class="contact-card">
          <p>💬 WhatsApp: <strong>+237 6 72 37 09 50</strong></p>
          <p>📧 Email: <strong>servi.rapide237@gmail.com</strong></p>
          <p>📍 <?= e($t['location']) ?></p>
          <a class="btn btn-primary full" href="https://wa.me/237672370950" target="_blank" rel="noopener"><?= e($t['whatsapp_cta']) ?></a>
        </div>
      </div>
    </section>
  </main>

  <nav class="mobile-tabbar" aria-label="Mobile navigation">
    <a href="#home">🏠<span><?= e($t['home']) ?></span></a>
    <a href="#services">✅<span><?= e($t['services']) ?></span></a>
    <a href="#register">📅<span>Plan</span></a>
    <a href="#dashboard">👤<span><?= e($t['dashboard']) ?></span></a>
  </nav>

  <footer class="footer">
    <div class="container footer-inner">
      <p><strong><?= e($t['brand']) ?></strong> — <?= e($t['slogan']) ?></p>
      <p><?= e($t['footer_text']) ?></p>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
