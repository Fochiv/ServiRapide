<?php
$lang = $_GET['lang'] ?? 'fr';
$lang = in_array($lang, ['fr', 'en'], true) ? $lang : 'fr';

$text = [
  'fr' => [
    'html_lang'         => 'fr',
    'switch'            => 'EN',
    'title'             => 'ServiRapide — Services à domicile à Douala',
    'brand'             => 'ServiRapide',
    'slogan'            => 'Zéro effort, zéro stress.',
    'location'          => 'Douala, Cameroun',
    'nav_home'          => 'Accueil',
    'nav_services'      => 'Services',
    'nav_pricing'       => 'Tarifs',
    'nav_how'           => 'Comment ça marche',
    'nav_contact'       => 'Contact',
    'nav_login'         => 'Connexion',
    'nav_register'      => 'S\'inscrire',
    'hero_badge'        => 'Douala, Cameroun',
    'hero_title'        => 'Vos tâches ménagères, enfin simplifiées.',
    'hero_subtitle'     => 'Abonnez-vous et profitez de services à domicile professionnels : nettoyage, lessive, repassage, vaisselle et services bonus réservés aux membres.',
    'cta_primary'       => 'S\'abonner maintenant',
    'cta_secondary'     => 'Voir les services',
    'stats_1'           => 'Familles servies',
    'stats_2'           => 'Note moyenne',
    'stats_3'           => 'Satisfaction',
    'about_title'       => 'Qui sommes-nous ?',
    'about_subtitle'    => 'ServiRapide est une entreprise de services à domicile basée à Douala, Cameroun. Nous mettons à votre disposition des prestataires qualifiés, fiables et vérifiés pour prendre en charge vos tâches ménagères quotidiennes — afin que vous puissiez vous concentrer sur l\'essentiel.',
    'about_v1_title'    => 'Notre mission',
    'about_v1_text'     => 'Simplifier la vie des familles doualaïses grâce à des services à domicile accessibles, réguliers et de qualité.',
    'about_v2_title'    => 'Notre engagement',
    'about_v2_text'     => 'Ponctualité, professionnalisme et respect du foyer de chaque client. Chaque intervention est réalisée avec soin.',
    'about_v3_title'    => 'Notre zone',
    'about_v3_text'     => 'Nous intervenons actuellement à Bepanda et ses environs, avec une extension prévue sur l\'ensemble de Douala.',
    'services_title'    => 'Nos services',
    'services_subtitle' => 'Une gamme complète de services pour votre foyer.',
    'std_title'         => 'Services standards',
    'std_desc'          => 'Disponibles pour tous les abonnés.',
    'mbr_title'         => 'Services membres',
    'mbr_desc'          => 'Réservés aux abonnés actifs.',
    'svc_cleaning'      => 'Nettoyage',
    'svc_cleaning_desc' => 'Chambres, salon, cuisine, salle de bain, balayage, serpillière, dépoussiérage.',
    'svc_laundry'       => 'Lessive',
    'svc_laundry_desc'  => 'Lavage, rinçage, séchage et pliage du linge.',
    'svc_ironing'       => 'Repassage',
    'svc_ironing_desc'  => 'Repassage professionnel et rangement des vêtements.',
    'svc_dishes'        => 'Vaisselle',
    'svc_dishes_desc'   => 'Lavage, rangement et nettoyage de la cuisine après service.',
    'svc_cooking'       => 'Cuisine',
    'svc_cooking_desc'  => 'Préparation de repas simples à domicile selon vos préférences.',
    'svc_plumbing'      => 'Plomberie simple',
    'svc_plumbing_desc' => 'Petites réparations et dépannage de plomberie courante.',
    'svc_sewing'        => 'Couture simple',
    'svc_sewing_desc'   => 'Réparations de vêtements, ourlets et retouches légères.',
    'svc_shoes'         => 'Réparation de chaussures',
    'svc_shoes_desc'    => 'Semelles, coutures et petites réparations cordonnerie.',
    'gallery_title'     => 'Notre travail en images',
    'gallery_subtitle'  => 'Aperçu de nos prestations sur le terrain.',
    'pricing_title'     => 'Nos formules',
    'pricing_subtitle'  => 'Des prix transparents adaptés à chaque type de logement.',
    'pricing_note'      => 'Frais d\'inscription unique : 10 000 CFA',
    'plan_a_name'       => 'Catégorie A',
    'plan_a_type'       => 'Studio',
    'plan_a_price'      => '3 000',
    'plan_a_freq'       => '1 à 2 services/mois',
    'plan_a_duration'   => '1 à 1,5 h / intervention',
    'plan_b_name'       => 'Catégorie B',
    'plan_b_type'       => 'Appart. 1 chambre',
    'plan_b_price'      => '5 000',
    'plan_b_freq'       => '2 services/mois',
    'plan_b_duration'   => '1,5 à 2 h / intervention',
    'plan_c_name'       => 'Catégorie C',
    'plan_c_type'       => 'Appart. 2 chambres',
    'plan_c_price'      => '7 000',
    'plan_c_freq'       => '3 services/mois',
    'plan_c_duration'   => '2 à 2,5 h / intervention',
    'plan_d_name'       => 'Catégorie D',
    'plan_d_type'       => 'Maison 1 chambre',
    'plan_d_price'      => '8 000',
    'plan_d_freq'       => '3 services/mois',
    'plan_d_duration'   => '2,5 à 3 h / intervention',
    'plan_e_name'       => 'Catégorie E',
    'plan_e_type'       => 'Maison 2 chambres',
    'plan_e_price'      => '10 000',
    'plan_e_freq'       => '4 services/mois',
    'plan_e_duration'   => '3 à 3,5 h / intervention',
    'plan_f_name'       => 'Catégorie F',
    'plan_f_type'       => 'Maison 3 chambres',
    'plan_f_price'      => '15 000',
    'plan_f_freq'       => '5 à 6 services/mois',
    'plan_f_duration'   => '3,5 à 4,5 h / intervention',
    'month'             => 'CFA/mois',
    'choose'            => 'Choisir cette formule',
    'popular_label'     => 'Populaire',
    'sub_model_title'   => 'Modèles d\'abonnements',
    'sub_model_intro'   => 'ServiRapide utilise désormais une structure basée sur le type de logement. Ce modèle permet de mieux ajuster le prix au niveau de service demandé et au volume de travail requis.',
    'sub_fee_title'     => 'Frais d\'inscription',
    'sub_fee_text'      => 'Inscription unique : 10 000 CFA pour tous les clients.',
    'sub_monthly_title' => 'Catégories mensuelles',
    'how_title'         => 'Comment ça marche ?',
    'step1'             => 'Inscription',
    'step1_desc'        => 'Créez votre compte et payez les frais d\'inscription.',
    'step2'             => 'Choix de la formule',
    'step2_desc'        => 'Sélectionnez la catégorie adaptée à votre logement.',
    'step3'             => 'Planification',
    'step3_desc'        => 'Choisissez vos jours, horaires et services préférés.',
    'step4'             => 'Intervention',
    'step4_desc'        => 'L\'équipe ServiRapide intervient à domicile.',
    'step5'             => 'Suivi',
    'step5_desc'        => 'Suivez vos services depuis votre espace membre.',
    'dashboard_title'   => 'Aperçu espace membre',
    'dashboard_subtitle'=> 'Le client peut voir ses services restants, son prochain rendez-vous et son historique.',
    'hello'             => 'Bonjour, Marie',
    'active_plan'       => 'Catégorie B · Actif',
    'next_service'      => 'Prochain service',
    'monthly_progress'  => 'Progression mensuelle',
    'history'           => 'Historique',
    'done'              => 'Terminé',
    'register_title'    => 'Créer mon abonnement',
    'register_subtitle' => 'Formulaire simple pour commencer avec ServiRapide.',
    'name'              => 'Nom complet',
    'address'           => 'Adresse',
    'neighborhood'      => 'Quartier',
    'phone'             => 'Téléphone',
    'email'             => 'Email',
    'plan_label'        => 'Formule',
    'preferred_day'     => 'Jour préféré',
    'payment'           => 'Mode de paiement',
    'submit'            => 'Envoyer la demande',
    'form_note'         => 'Après validation, vous recevrez les instructions de paiement Mobile Money.',
    'contact_title'     => 'Contactez-nous',
    'contact_subtitle'  => 'Disponibles 7j/7 de 7h à 21h.',
    'whatsapp_cta'      => 'Écrire sur WhatsApp',
    'footer_tagline'    => 'Vos tâches ménagères entre de bonnes mains.',
    'footer_links'      => 'Liens utiles',
    'footer_services'   => 'Nos services',
    'footer_legal'      => 'Mentions légales',
    'footer_privacy'    => 'Politique de confidentialité',
    'footer_terms'      => 'Conditions d\'utilisation',
    'footer_copyright'  => '© 2026 ServiRapide · BEPANDA L\'AN 2000, Douala, Cameroun · Tous droits réservés.',
    'login_title'       => 'Connexion',
    'message_success'   => 'Merci ! Votre demande a bien été reçue. Vous serez contacté(e) prochainement avec les instructions de paiement.',
    'cleaning_nav'      => 'Nettoyage',
    'laundry_nav'       => 'Lessive',
    'ironing_nav'       => 'Repassage',
    'dishes_nav'        => 'Vaisselle',
    'cooking_nav'       => 'Cuisine',
  ],
  'en' => [
    'html_lang'         => 'en',
    'switch'            => 'FR',
    'title'             => 'ServiRapide — Home services in Douala',
    'brand'             => 'ServiRapide',
    'slogan'            => 'Zero effort, zero stress.',
    'location'          => 'Douala, Cameroon',
    'nav_home'          => 'Home',
    'nav_services'      => 'Services',
    'nav_pricing'       => 'Pricing',
    'nav_how'           => 'How it works',
    'nav_contact'       => 'Contact',
    'nav_login'         => 'Login',
    'nav_register'      => 'Sign up',
    'hero_badge'        => 'Douala, Cameroon',
    'hero_title'        => 'Your household tasks, finally simplified.',
    'hero_subtitle'     => 'Subscribe and enjoy professional home services: cleaning, laundry, ironing, dishwashing and bonus services reserved for members.',
    'cta_primary'       => 'Subscribe now',
    'cta_secondary'     => 'View services',
    'stats_1'           => 'Families served',
    'stats_2'           => 'Average rating',
    'stats_3'           => 'Satisfaction',
    'about_title'       => 'Who are we?',
    'about_subtitle'    => 'ServiRapide is a home services company based in Douala, Cameroon. We connect you with qualified, reliable and vetted professionals to handle your daily household tasks — so you can focus on what matters most.',
    'about_v1_title'    => 'Our mission',
    'about_v1_text'     => 'Simplify the lives of families in Douala through accessible, regular and quality home services.',
    'about_v2_title'    => 'Our commitment',
    'about_v2_text'     => 'Punctuality, professionalism and respect for every client\'s home. Each intervention is carried out with care.',
    'about_v3_title'    => 'Our area',
    'about_v3_text'     => 'We currently operate in Bepanda and surroundings, with a planned expansion across all of Douala.',
    'services_title'    => 'Our services',
    'services_subtitle' => 'A full range of services for your home.',
    'std_title'         => 'Standard services',
    'std_desc'          => 'Available to all subscribers.',
    'mbr_title'         => 'Member services',
    'mbr_desc'          => 'Reserved for active subscribers.',
    'svc_cleaning'      => 'Cleaning',
    'svc_cleaning_desc' => 'Bedrooms, living room, kitchen, bathroom, sweeping, mopping, dusting.',
    'svc_laundry'       => 'Laundry',
    'svc_laundry_desc'  => 'Washing, rinsing, drying and folding clothes.',
    'svc_ironing'       => 'Ironing',
    'svc_ironing_desc'  => 'Professional ironing and clothing organization.',
    'svc_dishes'        => 'Dishwashing',
    'svc_dishes_desc'   => 'Dish cleaning, storage and kitchen care after service.',
    'svc_cooking'       => 'Cooking',
    'svc_cooking_desc'  => 'Simple home meal preparation according to your preferences.',
    'svc_plumbing'      => 'Basic plumbing',
    'svc_plumbing_desc' => 'Small plumbing repairs and common troubleshooting.',
    'svc_sewing'        => 'Basic sewing',
    'svc_sewing_desc'   => 'Clothing repairs, hemming and light alterations.',
    'svc_shoes'         => 'Shoe repair',
    'svc_shoes_desc'    => 'Soles, stitching and small cobblery repairs.',
    'gallery_title'     => 'Our work in pictures',
    'gallery_subtitle'  => 'A glimpse of our services in action.',
    'pricing_title'     => 'Our plans',
    'pricing_subtitle'  => 'Transparent pricing adapted to every type of home.',
    'pricing_note'      => 'One-time registration fee: 10,000 CFA',
    'plan_a_name'       => 'Category A',
    'plan_a_type'       => 'Studio',
    'plan_a_price'      => '3,000',
    'plan_a_freq'       => '1 to 2 services/month',
    'plan_a_duration'   => '1 to 1.5 h / visit',
    'plan_b_name'       => 'Category B',
    'plan_b_type'       => '1-bedroom apt.',
    'plan_b_price'      => '5,000',
    'plan_b_freq'       => '2 services/month',
    'plan_b_duration'   => '1.5 to 2 h / visit',
    'plan_c_name'       => 'Category C',
    'plan_c_type'       => '2-bedroom apt.',
    'plan_c_price'      => '7,000',
    'plan_c_freq'       => '3 services/month',
    'plan_c_duration'   => '2 to 2.5 h / visit',
    'plan_d_name'       => 'Category D',
    'plan_d_type'       => '1-bedroom house',
    'plan_d_price'      => '8,000',
    'plan_d_freq'       => '3 services/month',
    'plan_d_duration'   => '2.5 to 3 h / visit',
    'plan_e_name'       => 'Category E',
    'plan_e_type'       => '2-bedroom house',
    'plan_e_price'      => '10,000',
    'plan_e_freq'       => '4 services/month',
    'plan_e_duration'   => '3 to 3.5 h / visit',
    'plan_f_name'       => 'Category F',
    'plan_f_type'       => '3-bedroom house',
    'plan_f_price'      => '15,000',
    'plan_f_freq'       => '5 to 6 services/month',
    'plan_f_duration'   => '3.5 to 4.5 h / visit',
    'month'             => 'CFA/month',
    'choose'            => 'Choose this plan',
    'popular_label'     => 'Popular',
    'sub_model_title'   => 'Subscription models',
    'sub_model_intro'   => 'ServiRapide now uses a structure based on the type of housing. This model allows better price adjustment to the level of service required and the volume of work needed.',
    'sub_fee_title'     => 'Registration fee',
    'sub_fee_text'      => 'One-time registration: 10,000 CFA for all clients.',
    'sub_monthly_title' => 'Monthly categories',
    'how_title'         => 'How it works',
    'step1'             => 'Register',
    'step1_desc'        => 'Create your account and pay the registration fee.',
    'step2'             => 'Choose a plan',
    'step2_desc'        => 'Select the category that matches your home.',
    'step3'             => 'Schedule',
    'step3_desc'        => 'Choose your preferred days, times and services.',
    'step4'             => 'Intervention',
    'step4_desc'        => 'The ServiRapide team comes to your home.',
    'step5'             => 'Track',
    'step5_desc'        => 'Track your services from your member area.',
    'dashboard_title'   => 'Member dashboard preview',
    'dashboard_subtitle'=> 'Clients can see remaining services, upcoming bookings and history.',
    'hello'             => 'Hello, Marie',
    'active_plan'       => 'Category B · Active',
    'next_service'      => 'Upcoming service',
    'monthly_progress'  => 'Monthly progress',
    'history'           => 'History',
    'done'              => 'Completed',
    'register_title'    => 'Create my subscription',
    'register_subtitle' => 'A simple form to get started with ServiRapide.',
    'name'              => 'Full name',
    'address'           => 'Address',
    'neighborhood'      => 'Neighborhood',
    'phone'             => 'Phone',
    'email'             => 'Email',
    'plan_label'        => 'Plan',
    'preferred_day'     => 'Preferred day',
    'payment'           => 'Payment method',
    'submit'            => 'Send request',
    'form_note'         => 'After validation, you will receive Mobile Money payment instructions.',
    'contact_title'     => 'Contact us',
    'contact_subtitle'  => 'Available 7 days/week from 7am to 9pm.',
    'whatsapp_cta'      => 'Chat on WhatsApp',
    'footer_tagline'    => 'Your household tasks in good hands.',
    'footer_links'      => 'Quick links',
    'footer_services'   => 'Our services',
    'footer_legal'      => 'Legal',
    'footer_privacy'    => 'Privacy policy',
    'footer_terms'      => 'Terms of service',
    'footer_copyright'  => '© 2026 ServiRapide · BEPANDA L\'AN 2000, Douala, Cameroon · All rights reserved.',
    'login_title'       => 'Login',
    'message_success'   => 'Thank you! Your request was received. You will be contacted shortly with payment instructions.',
    'cleaning_nav'      => 'Cleaning',
    'laundry_nav'       => 'Laundry',
    'ironing_nav'       => 'Ironing',
    'dishes_nav'        => 'Dishes',
    'cooking_nav'       => 'Cooking',
  ]
];

$t = $text[$lang];
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="<?= e($t['html_lang']) ?>" data-lang="<?= e($lang) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($t['title']) ?></title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="apple-touch-icon" href="favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Sora:wght@600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <script>
    window.SR_LANG = '<?= $lang ?>';
    window.SR_TRANSLATIONS = <?= json_encode($text, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
  </script>
</head>
<body>

  <!-- HEADER -->
  <header class="site-header">
    <div class="container nav-wrap">
      <a href="#home" class="brand">
        <img src="logo.png" alt="ServiRapide logo" class="brand-logo">
        <span>
          <strong data-i18n="brand"><?= e($t['brand']) ?></strong>
          <small data-i18n="slogan"><?= e($t['slogan']) ?></small>
        </span>
      </a>
      <nav class="desktop-nav" aria-label="Main navigation">
        <a href="#home"        data-i18n="nav_home"><?= e($t['nav_home']) ?></a>
        <a href="#services"    data-i18n="nav_services"><?= e($t['nav_services']) ?></a>
        <a href="#pricing"     data-i18n="nav_pricing"><?= e($t['nav_pricing']) ?></a>
        <a href="#how"         data-i18n="nav_how"><?= e($t['nav_how']) ?></a>
        <a href="#contact"     data-i18n="nav_contact"><?= e($t['nav_contact']) ?></a>
      </nav>
      <div class="header-actions">
        <a href="#register" class="btn btn-outline-nav" data-i18n="nav_login">
          <i class="fa-regular fa-user"></i> <?= e($t['nav_login']) ?>
        </a>
        <a href="#register" class="btn btn-primary btn-sm" data-i18n="nav_register">
          <?= e($t['nav_register']) ?>
        </a>
        <button class="lang-btn" id="langToggle" aria-label="Switch language" data-i18n="switch"><?= e($t['switch']) ?></button>
      </div>
    </div>
  </header>

  <main>

    <!-- HERO -->
    <section id="home" class="hero section-dark">
      <div class="hero-bg"></div>
      <div class="container hero-inner">
        <div class="hero-copy">
          <span class="badge">
            <i class="fa-solid fa-location-dot"></i>
            <span data-i18n="hero_badge"><?= e($t['hero_badge']) ?></span>
          </span>
          <h1 data-i18n="hero_title"><?= e($t['hero_title']) ?></h1>
          <p data-i18n="hero_subtitle"><?= e($t['hero_subtitle']) ?></p>
          <div class="hero-actions">
            <a class="btn btn-primary" href="#register" data-i18n="cta_primary"><?= e($t['cta_primary']) ?></a>
            <a class="btn btn-outline-light" href="#services" data-i18n="cta_secondary"><?= e($t['cta_secondary']) ?></a>
          </div>
        </div>
        <div class="hero-card" aria-label="ServiRapide illustration">
          <div class="hero-logo-wrap">
            <img src="logo.png" alt="ServiRapide" class="hero-logo">
          </div>
          <h3 data-i18n="brand"><?= e($t['brand']) ?></h3>
          <p data-i18n="slogan"><?= e($t['slogan']) ?></p>
        </div>
      </div>
    </section>

    <!-- STATS -->
    <section class="stats-bar">
      <div class="container stats-grid">
        <div>
          <i class="fa-solid fa-users stats-icon"></i>
          <strong><span class="counter" data-target="500">0</span>+</strong>
          <span data-i18n="stats_1"><?= e($t['stats_1']) ?></span>
        </div>
        <div>
          <i class="fa-solid fa-star stats-icon"></i>
          <strong>4.9</strong>
          <span data-i18n="stats_2"><?= e($t['stats_2']) ?></span>
        </div>
        <div>
          <i class="fa-solid fa-heart stats-icon"></i>
          <strong><span class="counter" data-target="100">0</span>%</strong>
          <span data-i18n="stats_3"><?= e($t['stats_3']) ?></span>
        </div>
      </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="section about-section">
      <div class="container">
        <div class="section-title">
          <h2 data-i18n="about_title"><?= e($t['about_title']) ?></h2>
          <p data-i18n="about_subtitle"><?= e($t['about_subtitle']) ?></p>
        </div>
        <div class="about-grid">
          <div class="about-card">
            <div class="about-icon"><i class="fa-solid fa-bullseye"></i></div>
            <h3 data-i18n="about_v1_title"><?= e($t['about_v1_title']) ?></h3>
            <p data-i18n="about_v1_text"><?= e($t['about_v1_text']) ?></p>
          </div>
          <div class="about-card">
            <div class="about-icon"><i class="fa-solid fa-handshake"></i></div>
            <h3 data-i18n="about_v2_title"><?= e($t['about_v2_title']) ?></h3>
            <p data-i18n="about_v2_text"><?= e($t['about_v2_text']) ?></p>
          </div>
          <div class="about-card">
            <div class="about-icon"><i class="fa-solid fa-map-location-dot"></i></div>
            <h3 data-i18n="about_v3_title"><?= e($t['about_v3_title']) ?></h3>
            <p data-i18n="about_v3_text"><?= e($t['about_v3_text']) ?></p>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICES -->
    <section id="services" class="section services-section">
      <div class="container">
        <div class="section-title">
          <h2 data-i18n="services_title"><?= e($t['services_title']) ?></h2>
          <p data-i18n="services_subtitle"><?= e($t['services_subtitle']) ?></p>
        </div>

        <!-- Standard services -->
        <div class="services-category-label">
          <i class="fa-solid fa-circle-check"></i>
          <span>
            <strong data-i18n="std_title"><?= e($t['std_title']) ?></strong>
            <small data-i18n="std_desc"><?= e($t['std_desc']) ?></small>
          </span>
        </div>
        <div class="service-grid">
          <article class="service-card">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/cleaning237/400/260" alt="Nettoyage" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon"><i class="fa-solid fa-broom"></i></div>
              <h3 data-i18n="svc_cleaning"><?= e($t['svc_cleaning']) ?></h3>
              <p data-i18n="svc_cleaning_desc"><?= e($t['svc_cleaning_desc']) ?></p>
            </div>
          </article>
          <article class="service-card">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/laundry237/400/260" alt="Lessive" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon"><i class="fa-solid fa-shirt"></i></div>
              <h3 data-i18n="svc_laundry"><?= e($t['svc_laundry']) ?></h3>
              <p data-i18n="svc_laundry_desc"><?= e($t['svc_laundry_desc']) ?></p>
            </div>
          </article>
          <article class="service-card">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/ironing237/400/260" alt="Repassage" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon"><i class="fa-solid fa-wind"></i></div>
              <h3 data-i18n="svc_ironing"><?= e($t['svc_ironing']) ?></h3>
              <p data-i18n="svc_ironing_desc"><?= e($t['svc_ironing_desc']) ?></p>
            </div>
          </article>
          <article class="service-card">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/dishes237/400/260" alt="Vaisselle" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon"><i class="fa-solid fa-utensils"></i></div>
              <h3 data-i18n="svc_dishes"><?= e($t['svc_dishes']) ?></h3>
              <p data-i18n="svc_dishes_desc"><?= e($t['svc_dishes_desc']) ?></p>
            </div>
          </article>
          <article class="service-card service-card--wide">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/cooking237/400/260" alt="Cuisine" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon"><i class="fa-solid fa-bowl-food"></i></div>
              <h3 data-i18n="svc_cooking"><?= e($t['svc_cooking']) ?></h3>
              <p data-i18n="svc_cooking_desc"><?= e($t['svc_cooking_desc']) ?></p>
            </div>
          </article>
        </div>

        <!-- Member services -->
        <div class="services-category-label member-label">
          <i class="fa-solid fa-crown"></i>
          <span>
            <strong data-i18n="mbr_title"><?= e($t['mbr_title']) ?></strong>
            <small data-i18n="mbr_desc"><?= e($t['mbr_desc']) ?></small>
          </span>
        </div>
        <div class="service-grid service-grid--3">
          <article class="service-card service-card--member">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/plumbing237/400/260" alt="Plomberie" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon service-icon--gold"><i class="fa-solid fa-wrench"></i></div>
              <h3 data-i18n="svc_plumbing"><?= e($t['svc_plumbing']) ?></h3>
              <p data-i18n="svc_plumbing_desc"><?= e($t['svc_plumbing_desc']) ?></p>
            </div>
          </article>
          <article class="service-card service-card--member">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/sewing237/400/260" alt="Couture" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon service-icon--gold"><i class="fa-solid fa-scissors"></i></div>
              <h3 data-i18n="svc_sewing"><?= e($t['svc_sewing']) ?></h3>
              <p data-i18n="svc_sewing_desc"><?= e($t['svc_sewing_desc']) ?></p>
            </div>
          </article>
          <article class="service-card service-card--member">
            <!-- ADMIN: Replace src with real service photo -->
            <div class="service-img-wrap">
              <img src="https://picsum.photos/seed/shoes237/400/260" alt="Cordonnerie" class="service-img" loading="lazy">
            </div>
            <div class="service-body">
              <div class="service-icon service-icon--gold"><i class="fa-solid fa-shoe-prints"></i></div>
              <h3 data-i18n="svc_shoes"><?= e($t['svc_shoes']) ?></h3>
              <p data-i18n="svc_shoes_desc"><?= e($t['svc_shoes_desc']) ?></p>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- GALLERY -->
    <section class="section gallery-section">
      <div class="container">
        <div class="section-title">
          <h2 data-i18n="gallery_title"><?= e($t['gallery_title']) ?></h2>
          <p data-i18n="gallery_subtitle"><?= e($t['gallery_subtitle']) ?></p>
        </div>
        <div class="gallery-grid">
          <!-- ADMIN: Replace these images with real photos -->
          <div class="photo-box">
            <img src="https://picsum.photos/seed/clean_g1/700/480" alt="Service nettoyage" loading="lazy">
            <div class="photo-label"><i class="fa-solid fa-broom"></i> <span data-i18n="svc_cleaning"><?= e($t['svc_cleaning']) ?></span></div>
          </div>
          <div class="photo-box">
            <img src="https://picsum.photos/seed/laundry_g2/700/480" alt="Service lessive" loading="lazy">
            <div class="photo-label"><i class="fa-solid fa-shirt"></i> <span data-i18n="svc_laundry"><?= e($t['svc_laundry']) ?></span></div>
          </div>
          <div class="photo-box">
            <img src="https://picsum.photos/seed/iron_g3/700/480" alt="Service repassage" loading="lazy">
            <div class="photo-label"><i class="fa-solid fa-wind"></i> <span data-i18n="svc_ironing"><?= e($t['svc_ironing']) ?></span></div>
          </div>
          <div class="photo-box">
            <img src="https://picsum.photos/seed/dishes_g4/700/480" alt="Service vaisselle" loading="lazy">
            <div class="photo-label"><i class="fa-solid fa-utensils"></i> <span data-i18n="svc_dishes"><?= e($t['svc_dishes']) ?></span></div>
          </div>
          <div class="photo-box">
            <img src="https://picsum.photos/seed/cooking_g5/700/480" alt="Service cuisine" loading="lazy">
            <div class="photo-label"><i class="fa-solid fa-bowl-food"></i> <span data-i18n="svc_cooking"><?= e($t['svc_cooking']) ?></span></div>
          </div>
          <div class="photo-box">
            <img src="https://picsum.photos/seed/home_g6/700/480" alt="À domicile" loading="lazy">
            <div class="photo-label"><i class="fa-solid fa-house"></i> <span data-i18n="brand"><?= e($t['brand']) ?></span></div>
          </div>
        </div>
      </div>
    </section>

    <!-- PRICING -->
    <section id="pricing" class="section pricing-section">
      <div class="container">
        <div class="section-title">
          <h2 data-i18n="pricing_title"><?= e($t['pricing_title']) ?></h2>
          <p data-i18n="pricing_subtitle"><?= e($t['pricing_subtitle']) ?></p>
          <div class="pricing-fee-badge">
            <i class="fa-solid fa-circle-info"></i>
            <span data-i18n="pricing_note"><?= e($t['pricing_note']) ?></span>
          </div>
        </div>
        <div class="pricing-grid">

          <article class="price-card" data-plan-key="A">
            <span class="plan-tag">A</span>
            <div class="plan-type" data-i18n="plan_a_type"><?= e($t['plan_a_type']) ?></div>
            <h3 data-i18n="plan_a_name"><?= e($t['plan_a_name']) ?></h3>
            <div class="price"><span data-i18n="plan_a_price"><?= e($t['plan_a_price']) ?></span> <small data-i18n="month"><?= e($t['month']) ?></small></div>
            <ul>
              <li><i class="fa-solid fa-check"></i> <span data-i18n="plan_a_freq"><?= e($t['plan_a_freq']) ?></span></li>
              <li><i class="fa-regular fa-clock"></i> <span data-i18n="plan_a_duration"><?= e($t['plan_a_duration']) ?></span></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="A" data-i18n="choose"><?= e($t['choose']) ?></a>
          </article>

          <article class="price-card" data-plan-key="B">
            <span class="plan-tag">B</span>
            <div class="plan-type" data-i18n="plan_b_type"><?= e($t['plan_b_type']) ?></div>
            <h3 data-i18n="plan_b_name"><?= e($t['plan_b_name']) ?></h3>
            <div class="price"><span data-i18n="plan_b_price"><?= e($t['plan_b_price']) ?></span> <small data-i18n="month"><?= e($t['month']) ?></small></div>
            <ul>
              <li><i class="fa-solid fa-check"></i> <span data-i18n="plan_b_freq"><?= e($t['plan_b_freq']) ?></span></li>
              <li><i class="fa-regular fa-clock"></i> <span data-i18n="plan_b_duration"><?= e($t['plan_b_duration']) ?></span></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="B" data-i18n="choose"><?= e($t['choose']) ?></a>
          </article>

          <article class="price-card featured" data-plan-key="C">
            <span class="popular" data-i18n="popular_label"><?= e($t['popular_label']) ?></span>
            <span class="plan-tag">C</span>
            <div class="plan-type" data-i18n="plan_c_type"><?= e($t['plan_c_type']) ?></div>
            <h3 data-i18n="plan_c_name"><?= e($t['plan_c_name']) ?></h3>
            <div class="price"><span data-i18n="plan_c_price"><?= e($t['plan_c_price']) ?></span> <small data-i18n="month"><?= e($t['month']) ?></small></div>
            <ul>
              <li><i class="fa-solid fa-check"></i> <span data-i18n="plan_c_freq"><?= e($t['plan_c_freq']) ?></span></li>
              <li><i class="fa-regular fa-clock"></i> <span data-i18n="plan_c_duration"><?= e($t['plan_c_duration']) ?></span></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="C" data-i18n="choose"><?= e($t['choose']) ?></a>
          </article>

          <article class="price-card" data-plan-key="D">
            <span class="plan-tag">D</span>
            <div class="plan-type" data-i18n="plan_d_type"><?= e($t['plan_d_type']) ?></div>
            <h3 data-i18n="plan_d_name"><?= e($t['plan_d_name']) ?></h3>
            <div class="price"><span data-i18n="plan_d_price"><?= e($t['plan_d_price']) ?></span> <small data-i18n="month"><?= e($t['month']) ?></small></div>
            <ul>
              <li><i class="fa-solid fa-check"></i> <span data-i18n="plan_d_freq"><?= e($t['plan_d_freq']) ?></span></li>
              <li><i class="fa-regular fa-clock"></i> <span data-i18n="plan_d_duration"><?= e($t['plan_d_duration']) ?></span></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="D" data-i18n="choose"><?= e($t['choose']) ?></a>
          </article>

          <article class="price-card" data-plan-key="E">
            <span class="plan-tag">E</span>
            <div class="plan-type" data-i18n="plan_e_type"><?= e($t['plan_e_type']) ?></div>
            <h3 data-i18n="plan_e_name"><?= e($t['plan_e_name']) ?></h3>
            <div class="price"><span data-i18n="plan_e_price"><?= e($t['plan_e_price']) ?></span> <small data-i18n="month"><?= e($t['month']) ?></small></div>
            <ul>
              <li><i class="fa-solid fa-check"></i> <span data-i18n="plan_e_freq"><?= e($t['plan_e_freq']) ?></span></li>
              <li><i class="fa-regular fa-clock"></i> <span data-i18n="plan_e_duration"><?= e($t['plan_e_duration']) ?></span></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="E" data-i18n="choose"><?= e($t['choose']) ?></a>
          </article>

          <article class="price-card price-card--premium" data-plan-key="F">
            <span class="plan-tag plan-tag--premium">F</span>
            <div class="plan-type" data-i18n="plan_f_type"><?= e($t['plan_f_type']) ?></div>
            <h3 data-i18n="plan_f_name"><?= e($t['plan_f_name']) ?></h3>
            <div class="price"><span data-i18n="plan_f_price"><?= e($t['plan_f_price']) ?></span> <small data-i18n="month"><?= e($t['month']) ?></small></div>
            <ul>
              <li><i class="fa-solid fa-check"></i> <span data-i18n="plan_f_freq"><?= e($t['plan_f_freq']) ?></span></li>
              <li><i class="fa-regular fa-clock"></i> <span data-i18n="plan_f_duration"><?= e($t['plan_f_duration']) ?></span></li>
            </ul>
            <a class="btn btn-primary full" href="#register" data-plan="F" data-i18n="choose"><?= e($t['choose']) ?></a>
          </article>

        </div>
      </div>
    </section>

    <!-- SUBSCRIPTION MODEL -->
    <section id="subscriptions" class="section section-dark sub-model-section">
      <div class="container">
        <div class="section-title light">
          <h2 data-i18n="sub_model_title"><?= e($t['sub_model_title']) ?></h2>
          <p data-i18n="sub_model_intro"><?= e($t['sub_model_intro']) ?></p>
        </div>
        <div class="sub-model-grid">
          <div class="sub-fee-card">
            <div class="sub-fee-icon"><i class="fa-solid fa-id-card"></i></div>
            <h3 data-i18n="sub_fee_title"><?= e($t['sub_fee_title']) ?></h3>
            <p data-i18n="sub_fee_text"><?= e($t['sub_fee_text']) ?></p>
            <div class="sub-fee-amount">10 000 <small>CFA</small></div>
          </div>
          <div class="sub-table-wrap">
            <h3 data-i18n="sub_monthly_title"><?= e($t['sub_monthly_title']) ?></h3>
            <table class="sub-table">
              <tbody>
                <?php
                $plans = ['a','b','c','d','e','f'];
                $prices = ['3 000','5 000','7 000','8 000','10 000','15 000'];
                foreach ($plans as $i => $p):
                ?>
                <tr>
                  <td class="sub-cat-badge"><?= strtoupper($p) ?></td>
                  <td data-i18n="plan_<?= $p ?>_name"><?= e($t['plan_'.$p.'_name']) ?></td>
                  <td data-i18n="plan_<?= $p ?>_type"><?= e($t['plan_'.$p.'_type']) ?></td>
                  <td class="sub-price"><?= $prices[$i] ?> <small>CFA/mois</small></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how" class="section section-dark steps-section">
      <div class="container">
        <div class="section-title light">
          <h2 data-i18n="how_title"><?= e($t['how_title']) ?></h2>
        </div>
        <div class="steps-grid">
          <article class="step-card">
            <div class="step-num">01</div>
            <div class="step-icon"><i class="fa-solid fa-user-plus"></i></div>
            <h3 data-i18n="step1"><?= e($t['step1']) ?></h3>
            <p data-i18n="step1_desc"><?= e($t['step1_desc']) ?></p>
          </article>
          <article class="step-card">
            <div class="step-num">02</div>
            <div class="step-icon"><i class="fa-solid fa-list-check"></i></div>
            <h3 data-i18n="step2"><?= e($t['step2']) ?></h3>
            <p data-i18n="step2_desc"><?= e($t['step2_desc']) ?></p>
          </article>
          <article class="step-card">
            <div class="step-num">03</div>
            <div class="step-icon"><i class="fa-solid fa-calendar-days"></i></div>
            <h3 data-i18n="step3"><?= e($t['step3']) ?></h3>
            <p data-i18n="step3_desc"><?= e($t['step3_desc']) ?></p>
          </article>
          <article class="step-card">
            <div class="step-num">04</div>
            <div class="step-icon"><i class="fa-solid fa-house-chimney"></i></div>
            <h3 data-i18n="step4"><?= e($t['step4']) ?></h3>
            <p data-i18n="step4_desc"><?= e($t['step4_desc']) ?></p>
          </article>
          <article class="step-card">
            <div class="step-num">05</div>
            <div class="step-icon"><i class="fa-solid fa-chart-line"></i></div>
            <h3 data-i18n="step5"><?= e($t['step5']) ?></h3>
            <p data-i18n="step5_desc"><?= e($t['step5_desc']) ?></p>
          </article>
        </div>
      </div>
    </section>

    <!-- DASHBOARD PREVIEW -->
    <section id="dashboard" class="section dashboard-section">
      <div class="container dashboard-layout">
        <div>
          <div class="section-title left">
            <h2 data-i18n="dashboard_title"><?= e($t['dashboard_title']) ?></h2>
            <p data-i18n="dashboard_subtitle"><?= e($t['dashboard_subtitle']) ?></p>
          </div>
          <a class="btn btn-primary" href="#register" data-i18n="cta_primary"><?= e($t['cta_primary']) ?></a>
        </div>
        <div class="dashboard-card">
          <div class="dash-head">
            <div>
              <h3><i class="fa-regular fa-hand-wave"></i> <span data-i18n="hello"><?= e($t['hello']) ?></span></h3>
              <span data-i18n="active_plan"><?= e($t['active_plan']) ?></span>
            </div>
            <span class="status-dot"></span>
          </div>
          <div class="next-box">
            <small><i class="fa-regular fa-calendar"></i> <span data-i18n="next_service"><?= e($t['next_service']) ?></span></small>
            <strong><?= $lang === 'fr' ? 'Nettoyage · Lun 27 Jan · 9h00' : 'Cleaning · Mon Jan 27 · 9:00' ?></strong>
          </div>
          <h4 data-i18n="monthly_progress"><?= e($t['monthly_progress']) ?></h4>
          <div class="progress-row"><span data-i18n="svc_cleaning"><?= e($t['svc_cleaning']) ?></span><em>2/3</em></div>
          <div class="progress"><span style="width:66%"></span></div>
          <div class="progress-row"><span data-i18n="svc_laundry"><?= e($t['svc_laundry']) ?></span><em>1/2</em></div>
          <div class="progress"><span style="width:50%"></span></div>
          <div class="progress-row"><span data-i18n="svc_ironing"><?= e($t['svc_ironing']) ?></span><em>1/2</em></div>
          <div class="progress"><span style="width:50%"></span></div>
          <div class="history-box">
            <strong><i class="fa-solid fa-clock-rotate-left"></i> <span data-i18n="history"><?= e($t['history']) ?></span></strong>
            <p>20 Jan · <span data-i18n="svc_cleaning"><?= e($t['svc_cleaning']) ?></span> · <span data-i18n="done"><?= e($t['done']) ?></span></p>
          </div>
        </div>
      </div>
    </section>

    <!-- REGISTER FORM -->
    <section id="register" class="section register-section">
      <div class="container form-layout">
        <div class="section-title left">
          <h2 data-i18n="register_title"><?= e($t['register_title']) ?></h2>
          <p data-i18n="register_subtitle"><?= e($t['register_subtitle']) ?></p>
          <p class="form-note"><i class="fa-solid fa-circle-info"></i> <span data-i18n="form_note"><?= e($t['form_note']) ?></span></p>
        </div>
        <form class="subscribe-form" method="post" action="#register">
          <?php if ($submitted): ?>
            <div class="success-message">
              <i class="fa-solid fa-circle-check"></i>
              <span data-i18n="message_success"><?= e($t['message_success']) ?></span>
            </div>
          <?php endif; ?>
          <label data-i18n="name"><?= e($t['name']) ?><input type="text" name="name" required></label>
          <label data-i18n="address"><?= e($t['address']) ?><input type="text" name="address" required></label>
          <label data-i18n="neighborhood"><?= e($t['neighborhood']) ?><input type="text" name="neighborhood" required></label>
          <div class="two-cols">
            <label data-i18n="phone"><?= e($t['phone']) ?><input type="tel" name="phone" required></label>
            <label data-i18n="email"><?= e($t['email']) ?><input type="email" name="email"></label>
          </div>
          <div class="two-cols">
            <label data-i18n="plan_label"><?= e($t['plan_label']) ?>
              <select name="plan" id="planSelect">
                <option value="A">Cat. A — Studio — 3 000 CFA</option>
                <option value="B">Cat. B — Appt. 1ch — 5 000 CFA</option>
                <option value="C">Cat. C — Appt. 2ch — 7 000 CFA</option>
                <option value="D">Cat. D — Maison 1ch — 8 000 CFA</option>
                <option value="E">Cat. E — Maison 2ch — 10 000 CFA</option>
                <option value="F">Cat. F — Maison 3ch — 15 000 CFA</option>
              </select>
            </label>
            <label data-i18n="payment"><?= e($t['payment']) ?>
              <select name="payment">
                <option>MTN MoMo</option>
                <option>Orange Money</option>
                <option>Virement bancaire</option>
              </select>
            </label>
          </div>
          <label data-i18n="preferred_day"><?= e($t['preferred_day']) ?>
            <select name="day">
              <option>Lundi</option><option>Mardi</option><option>Mercredi</option>
              <option>Jeudi</option><option>Vendredi</option><option>Samedi</option>
            </select>
          </label>
          <button class="btn btn-primary full" type="submit" data-i18n="submit"><?= e($t['submit']) ?></button>
        </form>
      </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="section contact-section section-dark">
      <div class="container contact-grid">
        <div>
          <h2 data-i18n="contact_title"><?= e($t['contact_title']) ?></h2>
          <p data-i18n="contact_subtitle"><?= e($t['contact_subtitle']) ?></p>
        </div>
        <div class="contact-card">
          <div class="contact-item">
            <i class="fa-brands fa-whatsapp"></i>
            <div>
              <span>WhatsApp</span>
              <strong>+237 6 72 37 09 50</strong>
            </div>
          </div>
          <div class="contact-item">
            <i class="fa-solid fa-phone"></i>
            <div>
              <span>Téléphone</span>
              <strong>+237 6 72 37 09 50</strong>
            </div>
          </div>
          <div class="contact-item">
            <i class="fa-solid fa-location-dot"></i>
            <div>
              <span>Localisation</span>
              <strong>BEPANDA L'AN 2000, Douala, Cameroun</strong>
            </div>
          </div>
          <a class="btn btn-whatsapp full" href="https://wa.me/237672370950" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp"></i>
            <span data-i18n="whatsapp_cta"><?= e($t['whatsapp_cta']) ?></span>
          </a>
        </div>
      </div>
    </section>

  </main>

  <!-- MOBILE TABBAR -->
  <nav class="mobile-tabbar" aria-label="Mobile navigation">
    <a href="#home"><i class="fa-solid fa-house"></i><span data-i18n="nav_home"><?= e($t['nav_home']) ?></span></a>
    <a href="#services"><i class="fa-solid fa-broom"></i><span data-i18n="nav_services"><?= e($t['nav_services']) ?></span></a>
    <a href="#pricing"><i class="fa-solid fa-tags"></i><span data-i18n="nav_pricing"><?= e($t['nav_pricing']) ?></span></a>
    <a href="#contact"><i class="fa-solid fa-phone"></i><span data-i18n="nav_contact"><?= e($t['nav_contact']) ?></span></a>
  </nav>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="container footer-top">
      <div class="footer-brand">
        <img src="logo.png" alt="ServiRapide" class="footer-logo">
        <strong data-i18n="brand"><?= e($t['brand']) ?></strong>
        <p data-i18n="footer_tagline"><?= e($t['footer_tagline']) ?></p>
        <div class="footer-social">
          <a href="https://wa.me/237672370950" target="_blank" rel="noopener" aria-label="WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
          </a>
          <a href="tel:+237672370950" aria-label="Appeler">
            <i class="fa-solid fa-phone"></i>
          </a>
          <a href="#contact" aria-label="Localisation">
            <i class="fa-solid fa-location-dot"></i>
          </a>
        </div>
      </div>
      <div class="footer-col">
        <h4 data-i18n="footer_links"><?= e($t['footer_links']) ?></h4>
        <ul>
          <li><a href="#home" data-i18n="nav_home"><?= e($t['nav_home']) ?></a></li>
          <li><a href="#about" data-i18n="about_title"><?= e($t['about_title']) ?></a></li>
          <li><a href="#services" data-i18n="nav_services"><?= e($t['nav_services']) ?></a></li>
          <li><a href="#pricing" data-i18n="nav_pricing"><?= e($t['nav_pricing']) ?></a></li>
          <li><a href="#how" data-i18n="nav_how"><?= e($t['nav_how']) ?></a></li>
          <li><a href="#contact" data-i18n="nav_contact"><?= e($t['nav_contact']) ?></a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4 data-i18n="footer_services"><?= e($t['footer_services']) ?></h4>
        <ul>
          <li><a href="#services" data-i18n="svc_cleaning"><?= e($t['svc_cleaning']) ?></a></li>
          <li><a href="#services" data-i18n="svc_laundry"><?= e($t['svc_laundry']) ?></a></li>
          <li><a href="#services" data-i18n="svc_ironing"><?= e($t['svc_ironing']) ?></a></li>
          <li><a href="#services" data-i18n="svc_dishes"><?= e($t['svc_dishes']) ?></a></li>
          <li><a href="#services" data-i18n="svc_cooking"><?= e($t['svc_cooking']) ?></a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4 data-i18n="footer_legal"><?= e($t['footer_legal']) ?></h4>
        <ul>
          <li><a href="#" data-i18n="footer_privacy"><?= e($t['footer_privacy']) ?></a></li>
          <li><a href="#" data-i18n="footer_terms"><?= e($t['footer_terms']) ?></a></li>
        </ul>
        <div class="footer-contact-mini">
          <p><i class="fa-solid fa-location-dot"></i> BEPANDA L'AN 2000, Douala</p>
          <p><i class="fa-brands fa-whatsapp"></i> +237 6 72 37 09 50</p>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p data-i18n="footer_copyright"><?= e($t['footer_copyright']) ?></p>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
