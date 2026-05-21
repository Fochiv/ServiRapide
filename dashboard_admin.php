<?php
// ============================================================
//  ServiRapide — dashboard_admin.php  — Panneau Admin
// ============================================================
require_once 'config.php';
require_once 'auth.php';
requireAdmin();
checkExpiryNotifications($pdo);

// ── Actions POST ────────────────────────────────────────────
$msg = $msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Activer un compte client
    if ($action === 'activate' && !empty($_POST['user_id'])) {
        $uid   = (int)$_POST['user_id'];
        $start = date('Y-m-d');
        $end   = date('Y-m-d', strtotime('+'.SUB_DURATION_DAYS.' days'));
        $hash  = password_hash(DEFAULT_PASSWORD, PASSWORD_BCRYPT);

        $pdo->prepare("
            UPDATE users SET status='active', subscription_start=?, subscription_end=?,
            registration_fee_paid=1, password_hash=?
            WHERE id=?
        ")->execute([$start, $end, $hash, $uid]);

        // Notif client
        $stUser = $pdo->prepare('SELECT full_name FROM users WHERE id=?');
        $stUser->execute([$uid]);
        $uName = $stUser->fetchColumn();
        $pdo->prepare("
            INSERT INTO notifications (user_id, type, message, target)
            VALUES (?, 'activation', ?, 'user')
        ")->execute([$uid, "🎉 Votre compte a été activé ! Abonnement valable jusqu'au ".date('d/m/Y', strtotime($end)).". Mot de passe initial : ".DEFAULT_PASSWORD]);

        $msg = "✅ Compte de $uName activé avec succès. Abonnement jusqu'au ".date('d/m/Y', strtotime($end)).".";
        $msgType = 'success';
    }

    // Désactiver un compte
    if ($action === 'deactivate' && !empty($_POST['user_id'])) {
        $uid = (int)$_POST['user_id'];
        $pdo->prepare("UPDATE users SET status='inactive' WHERE id=?")->execute([$uid]);
        $stUser = $pdo->prepare('SELECT full_name FROM users WHERE id=?');
        $stUser->execute([$uid]);
        $uName = $stUser->fetchColumn();
        $msg = "⚠️ Compte de $uName désactivé.";
        $msgType = 'warning';
    }

    // Ajouter un service
    if ($action === 'add_service') {
        $uid  = (int)$_POST['svc_user_id'];
        $type = $_POST['svc_type']  ?? '';
        $date = $_POST['svc_date']  ?? date('Y-m-d');
        $tech = trim($_POST['svc_technician'] ?? '');
        $dur  = (int)($_POST['svc_duration'] ?? 0);
        $stat = $_POST['svc_status'] ?? 'planifie';
        $note = trim($_POST['svc_notes'] ?? '');

        $validTypes = array_keys(SERVICE_LABELS);
        if (in_array($type, $validTypes, true)) {
            $pdo->prepare("
                INSERT INTO services_rendered
                (user_id, service_type, service_date, status, duration_minutes, technician_name, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ")->execute([$uid, $type, $date, $stat, $dur ?: null, $tech ?: null, $note ?: null]);

            // Notif client
            $svcLabel = SERVICE_LABELS[$type]['label'];
            $pdo->prepare("
                INSERT INTO notifications (user_id, type, message, target)
                VALUES (?, 'new_service', ?, 'user')
            ")->execute([$uid, "📅 Un service «$svcLabel» a été enregistré pour le ".date('d/m/Y', strtotime($date))."."]);

            $msg = "✅ Service ajouté avec succès.";
            $msgType = 'success';
        }
    }

    // Mettre à jour les notes admin
    if ($action === 'update_notes') {
        $uid = (int)$_POST['user_id'];
        $notes = trim($_POST['notes'] ?? '');
        $pdo->prepare("UPDATE users SET notes=? WHERE id=?")->execute([$notes, $uid]);
        $msg = '✅ Notes mises à jour.'; $msgType = 'success';
    }
}

// ── Filtres ─────────────────────────────────────────────────
$filter  = $_GET['filter']  ?? 'all';
$search  = trim($_GET['q']  ?? '');
$validF  = ['all','active','pending','inactive','expiring'];
if (!in_array($filter, $validF)) $filter = 'all';

$where  = '1=1';
$params = [];
if ($filter === 'active')   { $where = 'u.status = "active"'; }
if ($filter === 'pending')  { $where = 'u.status = "pending"'; }
if ($filter === 'inactive') { $where = 'u.status = "inactive"'; }
if ($filter === 'expiring') { $where = 'u.status = "active" AND DATEDIFF(u.subscription_end, CURDATE()) BETWEEN 0 AND '.DAYS_BEFORE_EXPIRY; }

if ($search !== '') {
    $where .= ' AND (u.full_name LIKE ? OR u.phone LIKE ? OR u.neighborhood LIKE ?)';
    $s = '%'.$search.'%';
    $params = [$s, $s, $s];
}

$clients = $pdo->prepare("
    SELECT u.*,
           p.max_services, p.price_cfa, p.housing_type,
           DATEDIFF(u.subscription_end, CURDATE()) AS days_remaining,
           COALESCE(svc.cnt, 0) AS services_this_month
    FROM users u
    LEFT JOIN plan_config p ON p.plan_key = u.plan_category
    LEFT JOIN (
        SELECT user_id, COUNT(*) AS cnt
        FROM services_rendered
        WHERE MONTH(service_date)=MONTH(CURDATE()) AND YEAR(service_date)=YEAR(CURDATE())
          AND status != 'annule'
        GROUP BY user_id
    ) svc ON svc.user_id = u.id
    WHERE $where
    ORDER BY FIELD(u.status,'pending','active','inactive'), u.created_at DESC
");
$clients->execute($params);
$clients = $clients->fetchAll();

// ── Stats globales ──────────────────────────────────────────
$stats = $pdo->query("
    SELECT
        COUNT(*) AS total,
        SUM(status='active')   AS active,
        SUM(status='pending')  AS pending,
        SUM(status='inactive') AS inactive,
        SUM(status='active' AND DATEDIFF(subscription_end, CURDATE()) BETWEEN 0 AND ".DAYS_BEFORE_EXPIRY.") AS expiring
    FROM users
")->fetch();

// ── Notifications admin non lues ────────────────────────────
$adminNotifs = $pdo->query("
    SELECT * FROM notifications WHERE target='admin' AND is_read=0
    ORDER BY created_at DESC LIMIT 20
")->fetchAll();
$adminNotifCount = count($adminNotifs);
// Marquer lues
$pdo->exec("UPDATE notifications SET is_read=1 WHERE target='admin'");

// Déconnexion
if (isset($_GET['logout'])) { session_destroy(); header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — ServiRapide</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Sora:wght@600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body { background: #0f172a; }
    .dash-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }

    /* Sidebar admin */
    .sidebar {
      background: var(--dark); color: #fff;
      display: flex; flex-direction: column; padding: 24px 0;
      position: sticky; top: 0; height: 100vh; overflow-y: auto;
    }
    .sb-brand { display: flex; align-items: center; gap: 10px; padding: 0 22px 20px; border-bottom: 1px solid rgba(255,255,255,.1); }
    .sb-brand img { width: 42px; height: 42px; border-radius: 12px; object-fit: cover; }
    .sb-brand strong { font-family: 'Sora', sans-serif; font-size: 14px; }
    .sb-brand small { display: block; color: var(--gold); font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; }
    .sb-nav { flex: 1; padding: 18px 12px; display: flex; flex-direction: column; gap: 4px; }
    .sb-link { display: flex; align-items: center; gap: 12px; padding: 11px 14px; border-radius: 13px; color: rgba(255,255,255,.6); font-weight: 700; font-size: 13px; transition: .2s; text-decoration: none; }
    .sb-link:hover { background: rgba(255,255,255,.08); color: #fff; }
    .sb-link.active { background: rgba(29,158,117,.2); color: var(--green); }
    .sb-link i { width: 18px; text-align: center; }
    .sb-badge { margin-left: auto; background: var(--gold); color: #fff; border-radius: 999px; padding: 2px 8px; font-size: 10px; font-weight: 900; }
    .sb-footer { padding: 14px 12px; border-top: 1px solid rgba(255,255,255,.1); }
    .sb-logout { display: flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 12px; color: rgba(255,255,255,.5); font-size: 13px; font-weight: 700; transition: .2s; text-decoration: none; }
    .sb-logout:hover { color: #ff6b6b; background: rgba(255,107,107,.08); }

    /* Main */
    .main-content { background: #f1f5f9; padding: 32px 28px; }
    .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 12px; }
    .page-header h1 { font-family: 'Sora', sans-serif; font-size: 24px; color: #0f172a; }
    .page-header p { color: #64748b; font-size: 13px; margin-top: 4px; }

    /* KPI Stats */
    .stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 24px; }
    .stat-card {
      background: #fff; border-radius: 18px; padding: 20px 22px;
      box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1.5px solid #e2e8f0;
      display: flex; flex-direction: column; gap: 6px; text-decoration: none;
      transition: transform .2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-icon { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; font-size: 16px; }
    .stat-icon.green  { background: #dcfce7; color: #16a34a; }
    .stat-icon.blue   { background: #dbeafe; color: #2563eb; }
    .stat-icon.yellow { background: #fef9c3; color: #d97706; }
    .stat-icon.red    { background: #fee2e2; color: #dc2626; }
    .stat-icon.orange { background: rgba(245,166,35,.15); color: var(--gold); }
    .stat-value { font-family: 'Sora', sans-serif; font-size: 28px; font-weight: 900; color: #0f172a; line-height: 1; }
    .stat-label { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; }

    /* Alert message */
    .alert { padding: 14px 18px; border-radius: 14px; font-weight: 700; font-size: 14px; margin-bottom: 20px; display: flex; gap: 10px; align-items: center; }
    .alert-success { background: #dcfce7; border: 1.5px solid #4ade80; color: #166534; }
    .alert-warning { background: #fef9c3; border: 1.5px solid #fbbf24; color: #713f12; }

    /* Toolbar */
    .toolbar {
      background: #fff; border-radius: 16px; padding: 16px 20px;
      margin-bottom: 18px; display: flex; align-items: center; gap: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,.05); flex-wrap: wrap;
    }
    .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
    .filter-tab {
      padding: 8px 14px; border-radius: 10px; font-weight: 800; font-size: 12px;
      border: none; cursor: pointer; transition: .2s;
      background: #f1f5f9; color: #64748b; font-family: 'Outfit', sans-serif;
      text-decoration: none; display: inline-block;
    }
    .filter-tab:hover { background: #e2e8f0; }
    .filter-tab.active { background: var(--dark); color: #fff; }
    .filter-tab.active.green  { background: #16a34a; }
    .filter-tab.active.yellow { background: #d97706; }
    .filter-tab.active.red    { background: #dc2626; }
    .filter-tab.active.orange { background: var(--gold); }
    .search-box {
      display: flex; align-items: center; gap: 8px; margin-left: auto;
      background: #f1f5f9; border-radius: 10px; padding: 8px 14px;
      border: 1.5px solid transparent; transition: .2s;
    }
    .search-box:focus-within { border-color: var(--green); background: #fff; }
    .search-box input { border: none; background: transparent; outline: none; font-family: 'Outfit', sans-serif; font-size: 13px; font-weight: 700; min-width: 180px; }
    .search-box i { color: #94a3b8; }

    /* Client table */
    .table-wrap { background: #fff; border-radius: 18px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; margin-bottom: 22px; }
    .client-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .client-table thead { background: #f8fafc; }
    .client-table th { text-align: left; padding: 14px 16px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; border-bottom: 2px solid #e2e8f0; white-space: nowrap; }
    .client-table td { padding: 13px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .client-table tr:last-child td { border: none; }
    .client-table tr:hover td { background: #f8fafc; }

    .client-name strong { display: block; font-size: 14px; color: #0f172a; }
    .client-name small  { color: #94a3b8; font-size: 12px; }
    .plan-badge { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 8px; background: var(--light); color: var(--green); font-family: 'Sora', sans-serif; font-weight: 900; font-size: 12px; }

    .pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 999px; font-size: 11px; font-weight: 900; white-space: nowrap; }
    .pill-active   { background: #dcfce7; color: #166534; }
    .pill-pending  { background: #fef9c3; color: #713f12; }
    .pill-inactive { background: #fee2e2; color: #991b1b; }
    .pill-expiring { background: rgba(245,166,35,.15); color: #92400e; }

    .expiry-cell { white-space: nowrap; }
    .expiry-ok  { color: #16a34a; font-weight: 800; }
    .expiry-warn{ color: #d97706; font-weight: 800; }
    .expiry-crit{ color: #dc2626; font-weight: 800; }

    /* Action buttons */
    .action-row { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
    .btn-xs { padding: 7px 12px; font-size: 11px; font-weight: 900; border: none; border-radius: 9px; cursor: pointer; transition: .2s; font-family: 'Outfit', sans-serif; display: inline-flex; align-items: center; gap: 5px; }
    .btn-activate   { background: #dcfce7; color: #16a34a; }
    .btn-activate:hover { background: #bbf7d0; }
    .btn-deactivate { background: #fee2e2; color: #dc2626; }
    .btn-deactivate:hover { background: #fecaca; }
    .btn-add-svc    { background: #dbeafe; color: #2563eb; }
    .btn-add-svc:hover { background: #bfdbfe; }
    .btn-notes      { background: #f1f5f9; color: #64748b; }
    .btn-notes:hover { background: #e2e8f0; }

    /* Modal */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.55); z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: #fff; border-radius: 24px; padding: 32px; width: 100%; max-width: 480px; box-shadow: 0 40px 80px rgba(0,0,0,.3); }
    .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
    .modal-header h3 { font-family: 'Sora', sans-serif; font-size: 18px; color: #0f172a; }
    .modal-close { background: #f1f5f9; border: none; border-radius: 10px; width: 34px; height: 34px; cursor: pointer; font-size: 16px; color: #64748b; }
    .modal-close:hover { background: #e2e8f0; }
    .form-group { display: grid; gap: 7px; font-weight: 800; font-size: 12px; color: #475569; margin-bottom: 14px; }
    .form-group input, .form-group select, .form-group textarea {
      border: 2px solid #e2e8f0; border-radius: 11px; padding: 11px 13px;
      font-family: 'Outfit', sans-serif; font-size: 14px; outline: none; width: 100%;
      transition: border-color .2s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--green); }
    .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    /* Notifications panel */
    .notif-panel { background: #fff; border-radius: 18px; padding: 22px 24px; box-shadow: 0 2px 12px rgba(0,0,0,.06); margin-bottom: 22px; }
    .notif-panel h3 { font-family: 'Sora', sans-serif; font-size: 15px; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .notif-item { display: flex; gap: 10px; padding: 12px; background: #fefce8; border-radius: 12px; margin-bottom: 8px; }
    .notif-item i { color: var(--gold); margin-top: 2px; flex-shrink: 0; }
    .notif-item p { font-size: 13px; color: #0f172a; margin-bottom: 3px; }
    .notif-item small { color: #94a3b8; font-size: 11px; }
    .empty-state { text-align: center; padding: 24px; color: #94a3b8; font-size: 13px; }
    .empty-state i { font-size: 28px; display: block; margin-bottom: 8px; color: #cbd5e1; }

    /* Responsive */
    @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 900px) {
      .dash-layout { grid-template-columns: 1fr; }
      .sidebar { height: auto; position: static; }
      .sb-nav { flex-direction: row; flex-wrap: wrap; }
      .main-content { padding: 20px 14px; }
      .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) { .stats-grid { grid-template-columns: 1fr 1fr; } }
  </style>
</head>
<body>

<div class="dash-layout">

  <!-- ════════════════════════════════════════ SIDEBAR ══════ -->
  <aside class="sidebar">
    <div class="sb-brand">
      <img src="logo.png" alt="ServiRapide">
      <span>
        <strong>ServiRapide</strong>
        <small>🔐 Admin Panel</small>
      </span>
    </div>
    <nav class="sb-nav">
      <a href="dashboard_admin.php" class="sb-link active"><i class="fa-solid fa-gauge-high"></i> Tableau de bord</a>
      <a href="?filter=pending" class="sb-link"><i class="fa-solid fa-user-clock"></i> En attente <span class="sb-badge"><?= (int)$stats['pending'] ?></span></a>
      <a href="?filter=active"  class="sb-link"><i class="fa-solid fa-user-check"></i> Clients actifs</a>
      <a href="?filter=expiring" class="sb-link"><i class="fa-solid fa-hourglass-end"></i> Expirent bientôt <?php if($stats['expiring'] > 0): ?><span class="sb-badge"><?= (int)$stats['expiring'] ?></span><?php endif; ?></a>
      <a href="?filter=inactive" class="sb-link"><i class="fa-solid fa-user-xmark"></i> Inactifs</a>
      <a href="index.php" class="sb-link" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Voir le site</a>
    </nav>
    <div class="sb-footer">
      <div style="padding:10px 12px;font-size:12px;color:rgba(255,255,255,.5)">
        <i class="fa-solid fa-shield-halved" style="color:var(--green)"></i>
        <?= e($_SESSION['sr_name'] ?? 'Admin') ?>
      </div>
      <a href="?logout=1" class="sb-logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
    </div>
  </aside>

  <!-- ═══════════════════════════════════════ MAIN ═══════════ -->
  <main class="main-content">

    <!-- Header -->
    <div class="page-header">
      <div>
        <h1>Tableau de bord Admin</h1>
        <p>Gestion des abonnés ServiRapide · <?= date('d/m/Y') ?></p>
      </div>
      <a href="https://wa.me/<?= WHATSAPP_NUM ?>" class="btn btn-whatsapp" target="_blank" rel="noopener" style="font-size:13px">
        <i class="fa-brands fa-whatsapp"></i> WhatsApp
      </a>
    </div>

    <!-- Alert message -->
    <?php if ($msg): ?>
    <div class="alert alert-<?= $msgType ?>">
      <i class="fa-solid fa-circle-info"></i> <?= e($msg) ?>
    </div>
    <?php endif; ?>

    <!-- Notifications admin -->
    <?php if ($adminNotifCount > 0): ?>
    <div class="notif-panel">
      <h3><i class="fa-solid fa-bell" style="color:var(--gold)"></i> Nouvelles alertes (<?= $adminNotifCount ?>)</h3>
      <?php foreach ($adminNotifs as $n): ?>
      <div class="notif-item">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
          <p><?= e($n['message']) ?></p>
          <small><?= date('d/m/Y à H:i', strtotime($n['created_at'])) ?></small>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Stats KPI -->
    <div class="stats-grid">
      <a href="?filter=all" class="stat-card" style="text-decoration:none">
        <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
        <div class="stat-value"><?= (int)$stats['total'] ?></div>
        <div class="stat-label">Total clients</div>
      </a>
      <a href="?filter=active" class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-user-check"></i></div>
        <div class="stat-value"><?= (int)$stats['active'] ?></div>
        <div class="stat-label">Abonnés actifs</div>
      </a>
      <a href="?filter=pending" class="stat-card">
        <div class="stat-icon yellow"><i class="fa-solid fa-user-clock"></i></div>
        <div class="stat-value"><?= (int)$stats['pending'] ?></div>
        <div class="stat-label">En attente</div>
      </a>
      <a href="?filter=inactive" class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-user-xmark"></i></div>
        <div class="stat-value"><?= (int)$stats['inactive'] ?></div>
        <div class="stat-label">Inactifs</div>
      </a>
      <a href="?filter=expiring" class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-hourglass-end"></i></div>
        <div class="stat-value"><?= (int)$stats['expiring'] ?></div>
        <div class="stat-label">Expirent bientôt</div>
      </a>
    </div>

    <!-- Toolbar : filtres + recherche -->
    <div class="toolbar">
      <div class="filter-tabs">
        <?php $tabs = ['all'=>'Tous','active'=>'Actifs','pending'=>'En attente','expiring'=>'Expirent bientôt','inactive'=>'Inactifs']; ?>
        <?php $colors = ['active'=>'green','pending'=>'yellow','expiring'=>'orange','inactive'=>'red','all'=>'']; ?>
        <?php foreach ($tabs as $k => $label): ?>
        <a href="?filter=<?= $k ?>&q=<?= urlencode($search) ?>"
           class="filter-tab <?= $filter===$k ? 'active '.$colors[$k] : '' ?>">
          <?= $label ?>
        </a>
        <?php endforeach; ?>
      </div>
      <form method="get" style="margin-left:auto">
        <input type="hidden" name="filter" value="<?= e($filter) ?>">
        <div class="search-box">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" name="q" placeholder="Rechercher…" value="<?= e($search) ?>">
        </div>
      </form>
    </div>

    <!-- Tableau clients -->
    <div class="table-wrap">
      <table class="client-table">
        <thead>
          <tr>
            <th>Client</th>
            <th>Formule</th>
            <th>Statut</th>
            <th>Fin d'abonnement</th>
            <th>Services/mois</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($clients)): ?>
        <tr><td colspan="6" class="empty-state"><i class="fa-regular fa-folder-open"></i> Aucun client trouvé</td></tr>
        <?php endif; ?>
        <?php foreach ($clients as $c):
          $planI  = PLAN_INFO[$c['plan_category']] ?? PLAN_INFO['A'];
          $dr     = (int)$c['days_remaining'];
          $expClass = $dr < 0 ? 'expiry-crit' : ($dr <= DAYS_BEFORE_EXPIRY ? 'expiry-warn' : 'expiry-ok');
          $statusPill = ['active'=>'pill-active','pending'=>'pill-pending','inactive'=>'pill-inactive'][$c['status']] ?? 'pill-inactive';
          $isExpiring = $c['status'] === 'active' && $dr >= 0 && $dr <= DAYS_BEFORE_EXPIRY;
        ?>
        <tr>
          <td>
            <div class="client-name">
              <strong><?= e($c['full_name']) ?></strong>
              <small><i class="fa-solid fa-phone"></i> <?= e($c['phone']) ?>
                <?php if ($c['neighborhood']): ?> · <?= e($c['neighborhood']) ?><?php endif; ?>
              </small>
            </div>
          </td>
          <td>
            <div style="display:flex;align-items:center;gap:8px">
              <div class="plan-badge"><?= e($c['plan_category']) ?></div>
              <div>
                <strong style="font-size:12px;color:#0f172a"><?= e($planI['name']) ?></strong>
                <small style="display:block;color:#94a3b8;font-size:11px"><?= e($planI['type']) ?></small>
              </div>
            </div>
          </td>
          <td>
            <span class="pill <?= $statusPill ?>">
              <?= ['active'=>'✅ Actif','pending'=>'⏳ En attente','inactive'=>'❌ Inactif'][$c['status']] ?>
            </span>
            <?php if ($isExpiring): ?><br><span class="pill pill-expiring" style="margin-top:4px">⚠️ Expire bientôt</span><?php endif; ?>
          </td>
          <td class="expiry-cell">
            <?php if ($c['subscription_end']): ?>
              <span class="<?= $expClass ?>">
                <?= date('d/m/Y', strtotime($c['subscription_end'])) ?>
              </span><br>
              <small style="color:#94a3b8;font-size:11px">
                <?= $dr < 0 ? 'Expiré depuis '.abs($dr).' j' : ($dr === 0 ? 'Expire aujourd\'hui' : "J-$dr") ?>
              </small>
            <?php else: ?>
              <span style="color:#cbd5e1">—</span>
            <?php endif; ?>
          </td>
          <td>
            <strong style="font-size:14px;color:#0f172a"><?= (int)$c['services_this_month'] ?></strong>
            <span style="color:#94a3b8;font-size:11px"> / <?= (int)($c['max_services'] ?? 2) ?></span>
          </td>
          <td>
            <div class="action-row">
              <?php if ($c['status'] === 'pending' || $c['status'] === 'inactive'): ?>
              <form method="post" style="display:inline" onsubmit="return confirm('Activer le compte de <?= e($c['full_name']) ?> et créer un abonnement de <?= SUB_DURATION_DAYS ?> jours ?')">
                <input type="hidden" name="action"  value="activate">
                <input type="hidden" name="user_id" value="<?= $c['id'] ?>">
                <button class="btn-xs btn-activate" type="submit"><i class="fa-solid fa-circle-check"></i> Activer</button>
              </form>
              <?php endif; ?>

              <?php if ($c['status'] === 'active'): ?>
              <form method="post" style="display:inline" onsubmit="return confirm('Désactiver le compte de <?= e($c['full_name']) ?> ?')">
                <input type="hidden" name="action"  value="deactivate">
                <input type="hidden" name="user_id" value="<?= $c['id'] ?>">
                <button class="btn-xs btn-deactivate" type="submit"><i class="fa-solid fa-circle-xmark"></i> Désactiver</button>
              </form>
              <?php endif; ?>

              <button class="btn-xs btn-add-svc" onclick="openSvcModal(<?= $c['id'] ?>, '<?= e(addslashes($c['full_name'])) ?>')">
                <i class="fa-solid fa-plus"></i> Service
              </button>

              <button class="btn-xs btn-notes" onclick="openNotesModal(<?= $c['id'] ?>, '<?= e(addslashes($c['full_name'])) ?>', '<?= e(addslashes($c['notes'] ?? '')) ?>')">
                <i class="fa-regular fa-note-sticky"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </main>
</div>

<!-- ══════════════════════════════ MODAL : Ajouter service ══ -->
<div class="modal-overlay" id="svcModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><i class="fa-solid fa-plus" style="color:var(--green)"></i> Ajouter un service</h3>
      <button class="modal-close" onclick="closeModal('svcModal')">✕</button>
    </div>
    <form method="post">
      <input type="hidden" name="action" value="add_service">
      <input type="hidden" name="svc_user_id" id="svcUserId">
      <div class="form-group">
        <label>Client</label>
        <input type="text" id="svcUserName" disabled style="background:#f8fafc">
      </div>
      <div class="two-col">
        <div class="form-group">
          <label>Type de service</label>
          <select name="svc_type" required>
            <?php foreach (SERVICE_LABELS as $key => $info): ?>
            <option value="<?= $key ?>"><?= e($info['label']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Statut</label>
          <select name="svc_status">
            <option value="planifie">Planifié</option>
            <option value="en_cours">En cours</option>
            <option value="termine" selected>Terminé</option>
            <option value="annule">Annulé</option>
          </select>
        </div>
      </div>
      <div class="two-col">
        <div class="form-group">
          <label>Date d'intervention</label>
          <input type="date" name="svc_date" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
          <label>Durée (minutes)</label>
          <input type="number" name="svc_duration" placeholder="ex: 90" min="0" max="480">
        </div>
      </div>
      <div class="form-group">
        <label>Nom du prestataire</label>
        <input type="text" name="svc_technician" placeholder="ex: Jean Dupont">
      </div>
      <div class="form-group">
        <label>Notes</label>
        <textarea name="svc_notes" rows="2" placeholder="Observations…"></textarea>
      </div>
      <button type="submit" class="btn btn-primary full" style="margin-top:6px">
        <i class="fa-solid fa-floppy-disk"></i> Enregistrer le service
      </button>
    </form>
  </div>
</div>

<!-- ═══════════════════════════════════ MODAL : Notes admin ══ -->
<div class="modal-overlay" id="notesModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3><i class="fa-regular fa-note-sticky" style="color:var(--gold)"></i> Notes internes</h3>
      <button class="modal-close" onclick="closeModal('notesModal')">✕</button>
    </div>
    <form method="post">
      <input type="hidden" name="action" value="update_notes">
      <input type="hidden" name="user_id" id="notesUserId">
      <div class="form-group" style="margin-bottom:6px">
        <label id="notesUserName"></label>
        <textarea name="notes" id="notesContent" rows="5"
                  placeholder="Ex: Paiement MTN MoMo reçu le 21/05/2026 · Numéro de transaction: XXXX …"></textarea>
      </div>
      <button type="submit" class="btn btn-primary full">
        <i class="fa-solid fa-floppy-disk"></i> Sauvegarder les notes
      </button>
    </form>
  </div>
</div>

<script>
function openSvcModal(userId, userName) {
  document.getElementById('svcUserId').value  = userId;
  document.getElementById('svcUserName').value = userName;
  document.getElementById('svcModal').classList.add('open');
}
function openNotesModal(userId, userName, notes) {
  document.getElementById('notesUserId').value  = userId;
  document.getElementById('notesUserName').textContent = 'Notes pour : ' + userName;
  document.getElementById('notesContent').value = notes;
  document.getElementById('notesModal').classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
// Fermer modal en cliquant l'overlay
document.querySelectorAll('.modal-overlay').forEach(o => {
  o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>

</body>
</html>
