<?php
// ============================================================
//  ServiRapide — dashboard_user.php  — Espace Client
// ============================================================
require_once 'config.php';
require_once 'auth.php';
requireLogin();
checkExpiryNotifications($pdo);

$userId = currentUserId();

// --- Données client ---
$stUser = $pdo->prepare('SELECT u.*, p.max_services, p.price_cfa, p.housing_type
    FROM users u
    LEFT JOIN plan_config p ON p.plan_key = u.plan_category
    WHERE u.id = ?');
$stUser->execute([$userId]);
$user = $stUser->fetch();
if (!$user) { session_destroy(); header('Location: login.php'); exit; }

// --- Services du mois en cours ---
$stMth = $pdo->prepare('
    SELECT * FROM services_rendered
    WHERE user_id = ?
      AND MONTH(service_date) = MONTH(CURDATE())
      AND YEAR(service_date)  = YEAR(CURDATE())
    ORDER BY service_date DESC
');
$stMth->execute([$userId]);
$monthServices = $stMth->fetchAll();
$doneThisMonth = count(array_filter($monthServices, fn($s) => $s['status'] === 'termine'));
$maxSvc = (int)($user['max_services'] ?? 2);

// --- Historique complet (30 derniers) ---
$stHist = $pdo->prepare('
    SELECT * FROM services_rendered
    WHERE user_id = ?
    ORDER BY service_date DESC LIMIT 30
');
$stHist->execute([$userId]);
$history = $stHist->fetchAll();
$totalServices = count($history);

// --- Notifications non lues ---
$stNotif = $pdo->prepare("
    SELECT * FROM notifications
    WHERE user_id = ? AND target = 'user' AND is_read = 0
    ORDER BY created_at DESC
");
$stNotif->execute([$userId]);
$notifications = $stNotif->fetchAll();
$unreadCount = count($notifications);

// Marquer comme lues
$pdo->prepare("UPDATE notifications SET is_read=1 WHERE user_id=? AND target='user'")->execute([$userId]);

// --- Calculs abonnement ---
$subEnd   = $user['subscription_end'] ? new DateTime($user['subscription_end']) : null;
$today    = new DateTime();
$daysLeft = $subEnd ? (int)$today->diff($subEnd)->format('%r%a') : null;
$isExpired = $daysLeft !== null && $daysLeft < 0;

// --- Déconnexion ---
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Status badge
$statusMap = [
    'active'   => ['label' => '✅ Actif',             'class' => 'status-active'],
    'pending'  => ['label' => '⏳ En attente',         'class' => 'status-pending'],
    'inactive' => ['label' => '❌ Inactif / Expiré',   'class' => 'status-inactive'],
];
$statusInfo = $statusMap[$user['status']] ?? $statusMap['inactive'];
$planInfo   = PLAN_INFO[$user['plan_category']] ?? PLAN_INFO['A'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mon Espace — ServiRapide</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Sora:wght@600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    /* Layout dashboard */
    body { background: var(--bg); }
    .dash-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }

    /* Sidebar */
    .sidebar {
      background: var(--dark); color: #fff;
      display: flex; flex-direction: column; padding: 24px 0;
      position: sticky; top: 0; height: 100vh; overflow-y: auto;
    }
    .sb-brand { display: flex; align-items: center; gap: 10px; padding: 0 22px 28px; border-bottom: 1px solid rgba(255,255,255,.1); }
    .sb-brand img { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; }
    .sb-brand strong { font-family: 'Sora', sans-serif; font-size: 15px; }
    .sb-brand small { display: block; color: var(--green); font-size: 11px; }

    .sb-nav { flex: 1; padding: 22px 12px; display: flex; flex-direction: column; gap: 4px; }
    .sb-link {
      display: flex; align-items: center; gap: 12px;
      padding: 12px 14px; border-radius: 14px; color: rgba(255,255,255,.65);
      font-weight: 700; font-size: 14px; transition: .2s; cursor: pointer; text-decoration: none;
    }
    .sb-link:hover, .sb-link.active { background: rgba(255,255,255,.1); color: #fff; }
    .sb-link.active { color: var(--green); }
    .sb-link i { width: 20px; text-align: center; }
    .sb-badge {
      margin-left: auto; background: var(--gold); color: #fff;
      border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 900;
    }

    .sb-footer { padding: 16px 12px; border-top: 1px solid rgba(255,255,255,.1); }
    .sb-user { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 14px; }
    .sb-avatar {
      width: 38px; height: 38px; border-radius: 50%;
      background: var(--green); display: grid; place-items: center;
      font-weight: 900; font-size: 16px; color: #fff; flex-shrink: 0;
    }
    .sb-user-info strong { display: block; font-size: 13px; color: #fff; }
    .sb-user-info small { color: rgba(255,255,255,.5); font-size: 11px; }
    .sb-logout { display: flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 12px; color: rgba(255,255,255,.5); font-size: 13px; font-weight: 700; transition: .2s; }
    .sb-logout:hover { color: #ff6b6b; background: rgba(255,107,107,.08); }

    /* Main content */
    .main-content { padding: 36px 32px; }
    .page-header { margin-bottom: 32px; }
    .page-header h1 { font-family: 'Sora', sans-serif; font-size: 26px; color: var(--dark); margin-bottom: 6px; }
    .page-header p { color: var(--muted); font-size: 14px; }

    /* KPI Cards */
    .kpi-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 28px; }
    .kpi-card {
      background: #fff; border-radius: 20px; padding: 22px 24px;
      box-shadow: 0 4px 20px rgba(4,52,44,.07); border: 1.5px solid var(--border);
      display: flex; flex-direction: column; gap: 8px;
    }
    .kpi-icon { width: 44px; height: 44px; border-radius: 14px; background: var(--light); display: grid; place-items: center; font-size: 18px; color: var(--green); }
    .kpi-value { font-family: 'Sora', sans-serif; font-size: 30px; font-weight: 900; color: var(--dark); line-height: 1; }
    .kpi-label { color: var(--muted); font-size: 12px; font-weight: 700; }

    /* Status Banner */
    .status-banner {
      border-radius: 20px; padding: 20px 24px;
      display: flex; align-items: center; justify-content: space-between; gap: 16px;
      margin-bottom: 24px; flex-wrap: wrap;
    }
    .status-active   { background: linear-gradient(135deg, #dcfce7, #bbf7d0); border: 1.5px solid #4ade80; }
    .status-pending  { background: linear-gradient(135deg, #fef9c3, #fef08a); border: 1.5px solid #fbbf24; }
    .status-inactive { background: linear-gradient(135deg, #fee2e2, #fecaca); border: 1.5px solid #f87171; }
    .status-banner h3 { font-size: 16px; color: var(--dark); margin-bottom: 4px; }
    .status-banner p  { font-size: 13px; color: var(--muted); }
    .status-chip {
      padding: 8px 18px; border-radius: 999px; font-weight: 900; font-size: 13px;
      background: var(--dark); color: #fff; white-space: nowrap;
    }
    .expiry-countdown {
      display: flex; align-items: center; gap: 8px;
      background: rgba(4,52,44,.08); border-radius: 14px; padding: 12px 18px;
      font-weight: 800; font-size: 14px; color: var(--dark);
    }
    .expiry-countdown.warning { background: rgba(245,166,35,.12); color: #92400e; }
    .expiry-countdown.danger  { background: rgba(239,68,68,.1); color: #991b1b; }

    /* Section cards */
    .section-card {
      background: #fff; border-radius: 20px; padding: 26px 28px;
      box-shadow: 0 4px 20px rgba(4,52,44,.07); border: 1.5px solid var(--border);
      margin-bottom: 22px;
    }
    .section-card h3 { font-family: 'Sora', sans-serif; font-size: 16px; color: var(--dark); margin-bottom: 18px; display: flex; align-items: center; gap: 10px; }
    .section-card h3 i { color: var(--green); }

    /* Progress bars */
    .svc-progress { margin-bottom: 14px; }
    .svc-progress-row { display: flex; justify-content: space-between; font-weight: 800; font-size: 13px; color: var(--dark); margin-bottom: 6px; }
    .svc-progress-row em { background: var(--bg); border-radius: 999px; padding: 2px 10px; font-style: normal; color: var(--muted); }
    .progress-bar { height: 10px; background: var(--light); border-radius: 999px; overflow: hidden; }
    .progress-bar span { display: block; height: 100%; background: linear-gradient(90deg, var(--green), var(--mid)); border-radius: 999px; transition: width .6s; }

    /* Table services */
    .svc-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .svc-table th { text-align: left; padding: 10px 12px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); border-bottom: 2px solid var(--border); }
    .svc-table td { padding: 12px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    .svc-table tr:last-child td { border: none; }
    .svc-icon { width: 34px; height: 34px; border-radius: 10px; background: var(--light); display: grid; place-items: center; font-size: 14px; color: var(--green); }
    .pill {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 12px; border-radius: 999px; font-size: 11px; font-weight: 900; white-space: nowrap;
    }
    .pill-done    { background: #dcfce7; color: #166534; }
    .pill-plan    { background: #e0f2fe; color: #0c4a6e; }
    .pill-enc     { background: #fef9c3; color: #713f12; }
    .pill-cancel  { background: #fee2e2; color: #991b1b; }

    /* Notif list */
    .notif-list { display: flex; flex-direction: column; gap: 10px; }
    .notif-item { display: flex; gap: 12px; align-items: flex-start; padding: 14px; background: var(--bg); border-radius: 14px; }
    .notif-item i { color: var(--gold); margin-top: 2px; flex-shrink: 0; }
    .notif-item p { font-size: 13px; color: var(--dark); margin-bottom: 3px; }
    .notif-item small { color: var(--muted); font-size: 11px; }
    .empty-state { text-align: center; padding: 30px; color: var(--muted); font-size: 14px; }
    .empty-state i { font-size: 32px; margin-bottom: 10px; display: block; color: var(--border); }

    /* WhatsApp cta */
    .wa-cta { background: linear-gradient(135deg, var(--dark), var(--mid)); border-radius: 20px; padding: 24px 28px; color: #fff; margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; }
    .wa-cta h3 { font-size: 16px; margin-bottom: 5px; }
    .wa-cta p  { color: rgba(255,255,255,.7); font-size: 13px; }

    /* Responsive */
    @media (max-width: 900px) {
      .dash-layout { grid-template-columns: 1fr; }
      .sidebar { height: auto; position: static; padding-bottom: 10px; }
      .sb-nav { flex-direction: row; flex-wrap: wrap; }
      .main-content { padding: 24px 16px; }
      .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
      .kpi-grid { grid-template-columns: 1fr; }
      .status-banner { flex-direction: column; align-items: flex-start; }
    }
  </style>
</head>
<body>

<div class="dash-layout">

  <!-- ═══════════════════════════════════════ SIDEBAR ═══ -->
  <aside class="sidebar">
    <div class="sb-brand">
      <img src="logo.png" alt="ServiRapide">
      <span>
        <strong>ServiRapide</strong>
        <small>Espace client</small>
      </span>
    </div>

    <nav class="sb-nav">
      <a href="#overview"      class="sb-link active"><i class="fa-solid fa-gauge-high"></i> Tableau de bord</a>
      <a href="#services-mois" class="sb-link"><i class="fa-solid fa-calendar-check"></i> Mes services</a>
      <a href="#historique"    class="sb-link"><i class="fa-solid fa-clock-rotate-left"></i> Historique</a>
      <a href="#notifications" class="sb-link">
        <i class="fa-solid fa-bell"></i> Notifications
        <?php if ($unreadCount > 0): ?>
          <span class="sb-badge"><?= $unreadCount ?></span>
        <?php endif; ?>
      </a>
      <a href="index.php" class="sb-link"><i class="fa-solid fa-house"></i> Accueil site</a>
    </nav>

    <div class="sb-footer">
      <div class="sb-user">
        <div class="sb-avatar"><?= strtoupper(mb_substr($user['full_name'], 0, 1)) ?></div>
        <div class="sb-user-info">
          <strong><?= e($user['full_name']) ?></strong>
          <small><?= e($user['phone']) ?></small>
        </div>
      </div>
      <a href="?logout=1" class="sb-logout">
        <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
      </a>
    </div>
  </aside>

  <!-- ════════════════════════════════════ MAIN CONTENT ═══ -->
  <main class="main-content">

    <!-- Page header -->
    <div class="page-header" id="overview">
      <h1>Bonjour, <?= e(explode(' ', $user['full_name'])[0]) ?> 👋</h1>
      <p>
        <?= e($planInfo['name']) ?> — <?= e($planInfo['type']) ?> &nbsp;·&nbsp;
        <?= date('d/m/Y') ?>
      </p>
    </div>

    <!-- Statut de l'abonnement -->
    <div class="status-banner <?= $statusInfo['class'] ?>">
      <div>
        <h3>Statut de votre abonnement</h3>
        <p>
          <?php if ($user['status'] === 'pending'): ?>
            Votre compte est en attente de validation du paiement. Contactez-nous sur WhatsApp.
          <?php elseif ($user['status'] === 'active'): ?>
            Votre abonnement est actif. Profitez de vos services à domicile !
          <?php else: ?>
            Votre abonnement est expiré ou inactif. Renouvelez sur WhatsApp.
          <?php endif; ?>
        </p>
      </div>
      <span class="status-chip"><?= $statusInfo['label'] ?></span>
    </div>

    <!-- Date de fin + compte à rebours -->
    <?php if ($user['subscription_end'] && $user['status'] === 'active'): ?>
    <div class="expiry-countdown <?= $daysLeft <= 3 ? 'danger' : ($daysLeft <= 7 ? 'warning' : '') ?>" style="margin-bottom:24px">
      <i class="fa-regular fa-calendar-xmark"></i>
      <span>
        <strong>Date de fin d'abonnement :</strong>
        <?= date('d/m/Y', strtotime($user['subscription_end'])) ?>
        <?php if ($daysLeft === 0): ?>
          — <span style="color:#dc2626;font-weight:900">expire aujourd'hui !</span>
        <?php elseif ($daysLeft > 0): ?>
          — <strong><?= $daysLeft ?> jour(s) restant(s)</strong>
        <?php else: ?>
          — <span style="color:#dc2626">expiré depuis <?= abs($daysLeft) ?> jour(s)</span>
        <?php endif; ?>
      </span>
    </div>
    <?php elseif ($user['status'] === 'pending'): ?>
    <div class="expiry-countdown warning" style="margin-bottom:24px">
      <i class="fa-solid fa-hourglass-half"></i>
      En attente d'activation — date de fin non définie
    </div>
    <?php endif; ?>

    <!-- KPIs -->
    <div class="kpi-grid">
      <div class="kpi-card">
        <div class="kpi-icon"><i class="fa-solid fa-broom"></i></div>
        <div class="kpi-value"><?= $doneThisMonth ?>/<?= $maxSvc ?></div>
        <div class="kpi-label">Services ce mois</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-icon"><i class="fa-solid fa-history"></i></div>
        <div class="kpi-value"><?= $totalServices ?></div>
        <div class="kpi-label">Total interventions</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-icon"><i class="fa-solid fa-calendar-days"></i></div>
        <div class="kpi-value"><?= $user['preferred_day'] ? e($user['preferred_day']) : '—' ?></div>
        <div class="kpi-label">Jour préféré</div>
      </div>
    </div>

    <!-- Services du mois en cours -->
    <div class="section-card" id="services-mois">
      <h3><i class="fa-solid fa-chart-bar"></i> Services du mois de <?= date('F Y') ?></h3>

      <?php
      // Compter par type
      $byType = [];
      foreach ($monthServices as $s) {
        $byType[$s['service_type']] = ($byType[$s['service_type']] ?? 0) + 1;
      }
      if (empty($byType)):
      ?>
      <div class="empty-state">
        <i class="fa-regular fa-calendar-xmark"></i>
        Aucun service planifié ce mois
      </div>
      <?php else: ?>
        <?php foreach ($byType as $type => $count):
          $info = SERVICE_LABELS[$type] ?? ['label' => $type, 'icon' => 'fa-star'];
          $pct  = min(100, round($count / max(1, $maxSvc) * 100));
        ?>
        <div class="svc-progress">
          <div class="svc-progress-row">
            <span><i class="fa-solid <?= $info['icon'] ?>"></i> <?= $info['label'] ?></span>
            <em><?= $count ?> / <?= $maxSvc ?></em>
          </div>
          <div class="progress-bar"><span style="width:<?= $pct ?>%"></span></div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Historique des services -->
    <div class="section-card" id="historique">
      <h3><i class="fa-solid fa-clock-rotate-left"></i> Historique des interventions</h3>
      <?php if (empty($history)): ?>
      <div class="empty-state">
        <i class="fa-regular fa-folder-open"></i>
        Aucun service enregistré pour le moment
      </div>
      <?php else: ?>
      <div style="overflow-x:auto">
        <table class="svc-table">
          <thead>
            <tr>
              <th>Service</th>
              <th>Date</th>
              <th>Durée</th>
              <th>Prestataire</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($history as $svc):
            $info    = SERVICE_LABELS[$svc['service_type']] ?? ['label' => $svc['service_type'], 'icon' => 'fa-star'];
            $pillMap = [
              'termine'  => ['class' => 'pill-done',   'label' => 'Terminé'],
              'planifie' => ['class' => 'pill-plan',   'label' => 'Planifié'],
              'en_cours' => ['class' => 'pill-enc',    'label' => 'En cours'],
              'annule'   => ['class' => 'pill-cancel', 'label' => 'Annulé'],
            ];
            $pill = $pillMap[$svc['status']] ?? $pillMap['planifie'];
          ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:10px">
                <div class="svc-icon"><i class="fa-solid <?= $info['icon'] ?>"></i></div>
                <strong><?= $info['label'] ?></strong>
              </div>
            </td>
            <td><?= date('d/m/Y', strtotime($svc['service_date'])) ?></td>
            <td><?= $svc['duration_minutes'] ? $svc['duration_minutes'].' min' : '—' ?></td>
            <td><?= $svc['technician_name'] ? e($svc['technician_name']) : '—' ?></td>
            <td><span class="pill <?= $pill['class'] ?>"><?= $pill['label'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>

    <!-- Notifications -->
    <div class="section-card" id="notifications">
      <h3><i class="fa-solid fa-bell"></i> Notifications
        <?php if ($unreadCount > 0): ?>
          <span class="sb-badge" style="margin-left:4px"><?= $unreadCount ?> nouvelles</span>
        <?php endif; ?>
      </h3>

      <?php
      // Recharger toutes les notifs (pas seulement non lues)
      $stAllNotif = $pdo->prepare("SELECT * FROM notifications WHERE user_id=? AND target='user' ORDER BY created_at DESC LIMIT 10");
      $stAllNotif->execute([$userId]);
      $allNotifs = $stAllNotif->fetchAll();
      if (empty($allNotifs)):
      ?>
      <div class="empty-state">
        <i class="fa-regular fa-bell-slash"></i>
        Aucune notification
      </div>
      <?php else: ?>
      <div class="notif-list">
        <?php foreach ($allNotifs as $n): ?>
        <div class="notif-item">
          <i class="fa-solid <?= $n['type'] === 'expiry_warning' ? 'fa-triangle-exclamation' : 'fa-circle-info' ?>"></i>
          <div>
            <p><?= e($n['message']) ?></p>
            <small><?= date('d/m/Y à H:i', strtotime($n['created_at'])) ?></small>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- CTA WhatsApp renouvellement -->
    <div class="wa-cta">
      <div>
        <h3>🔄 Renouveler ou modifier votre abonnement</h3>
        <p>Contactez-nous directement sur WhatsApp — disponibles 7j/7 de 7h à 21h.</p>
      </div>
      <a href="https://wa.me/<?= WHATSAPP_NUM ?>?text=Bonjour%20ServiRapide%2C%20je%20souhaite%20renouveler%20mon%20abonnement.%20Mon%20nom%20%3A%20<?= urlencode($user['full_name']) ?>"
         class="btn btn-whatsapp" target="_blank" rel="noopener">
        <i class="fa-brands fa-whatsapp"></i> Écrire sur WhatsApp
      </a>
    </div>

  </main>
</div>

</body>
</html>
