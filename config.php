<?php
// ============================================================
//  ServiRapide — config.php
//  Inclure en PREMIER dans chaque page PHP protégée
// ============================================================

// --- Connexion base de données ---
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // ← Changez selon votre hébergeur
define('DB_PASS', '');           // ← Mot de passe MySQL
define('DB_NAME', 'servirapide');

// --- Paramètres site ---
define('WHATSAPP_NUM',        '237672370950');
define('DAYS_BEFORE_EXPIRY',  7);   // Notifier X jours avant l'expiration
define('DEFAULT_PASSWORD',    '1234'); // Mot de passe initial après activation
define('SUB_DURATION_DAYS',   30);  // Durée abonnement en jours

// --- Infos formules (miroir de plan_config en DB) ---
const PLAN_INFO = [
    'A' => ['name' => 'Catégorie A', 'type' => 'Studio',        'price' => 3000,  'max_svc' => 2],
    'B' => ['name' => 'Catégorie B', 'type' => 'Appt. 1 ch.',   'price' => 5000,  'max_svc' => 2],
    'C' => ['name' => 'Catégorie C', 'type' => 'Appt. 2 ch.',   'price' => 7000,  'max_svc' => 3],
    'D' => ['name' => 'Catégorie D', 'type' => 'Maison 1 ch.',  'price' => 8000,  'max_svc' => 3],
    'E' => ['name' => 'Catégorie E', 'type' => 'Maison 2 ch.',  'price' => 10000, 'max_svc' => 4],
    'F' => ['name' => 'Catégorie F', 'type' => 'Maison 3 ch.',  'price' => 15000, 'max_svc' => 6],
];

// --- Labels services ---
const SERVICE_LABELS = [
    'nettoyage'  => ['label' => 'Nettoyage',             'icon' => 'fa-broom'],
    'lessive'    => ['label' => 'Lessive',                'icon' => 'fa-shirt'],
    'repassage'  => ['label' => 'Repassage',              'icon' => 'fa-wind'],
    'vaisselle'  => ['label' => 'Vaisselle',              'icon' => 'fa-utensils'],
    'cuisine'    => ['label' => 'Cuisine',                'icon' => 'fa-bowl-food'],
    'plomberie'  => ['label' => 'Plomberie simple',       'icon' => 'fa-wrench'],
    'couture'    => ['label' => 'Couture simple',         'icon' => 'fa-scissors'],
    'chaussures' => ['label' => 'Réparation chaussures',  'icon' => 'fa-shoe-prints'],
];

// --- Connexion PDO ---
try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER, DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    die('
      <div style="font-family:sans-serif;padding:40px;color:#c0392b">
        <h2>❌ Erreur de connexion base de données</h2>
        <p>Vérifiez les paramètres dans <code>config.php</code></p>
        <pre style="background:#fdd;padding:12px;border-radius:8px">' . htmlspecialchars($e->getMessage()) . '</pre>
      </div>
    ');
}

// --- Helper HTML encode ---
function e(mixed $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
