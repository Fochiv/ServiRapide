<?php
// ============================================================
//  ServiRapide — auth.php
//  Gestion sessions, sécurité, notifications automatiques
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ── Contrôle d'accès ──────────────────────────────────────── */
function requireLogin(string $redirect = 'login.php'): void {
    if (empty($_SESSION['sr_user_id'])) {
        header("Location: $redirect");
        exit;
    }
}

function requireAdmin(string $redirect = 'login.php'): void {
    if (empty($_SESSION['sr_is_admin'])) {
        header("Location: $redirect");
        exit;
    }
}

function isAdmin(): bool {
    return !empty($_SESSION['sr_is_admin']);
}

function currentUserId(): int {
    return (int)($_SESSION['sr_user_id'] ?? 0);
}

function currentUser(PDO $pdo): array|false {
    if (empty($_SESSION['sr_user_id'])) return false;
    $st = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $st->execute([$_SESSION['sr_user_id']]);
    return $st->fetch();
}

/* ── Génération automatique de notifications ──────────────── */
function checkExpiryNotifications(PDO $pdo): void {
    $days = DAYS_BEFORE_EXPIRY;

    // Clients actifs dont l'abo expire bientôt, sans notif récente (< 12h)
    $rows = $pdo->query("
        SELECT u.id, u.full_name, u.subscription_end,
               DATEDIFF(u.subscription_end, CURDATE()) AS days_left
        FROM users u
        WHERE u.status = 'active'
          AND u.subscription_end IS NOT NULL
          AND DATEDIFF(u.subscription_end, CURDATE()) BETWEEN 0 AND $days
          AND NOT EXISTS (
              SELECT 1 FROM notifications n
              WHERE n.user_id = u.id
                AND n.type = 'expiry_warning'
                AND n.created_at >= DATE_SUB(NOW(), INTERVAL 12 HOUR)
          )
    ")->fetchAll();

    foreach ($rows as $u) {
        $d = (int)$u['days_left'];
        $dateStr = date('d/m/Y', strtotime($u['subscription_end']));

        // Notif → utilisateur
        $msgUser = $d === 0
            ? "⚠️ Votre abonnement expire aujourd'hui ($dateStr). Contactez-nous sur WhatsApp pour renouveler."
            : "⏳ Votre abonnement expire dans $d jour(s) — le $dateStr. Pensez à le renouveler.";
        $pdo->prepare("
            INSERT INTO notifications (user_id, type, message, target)
            VALUES (?, 'expiry_warning', ?, 'user')
        ")->execute([$u['id'], $msgUser]);

        // Notif → admin (user_id = 0)
        $msgAdmin = "🔔 L'abonnement de {$u['full_name']} expire dans $d jour(s) (le $dateStr).";
        $pdo->prepare("
            INSERT INTO notifications (user_id, type, message, target)
            VALUES (0, 'expiry_warning', ?, 'admin')
        ")->execute([$msgAdmin]);
    }

    // Mettre inactive les abonnements expirés depuis plus d'1 jour
    $pdo->exec("
        UPDATE users
        SET status = 'inactive'
        WHERE status = 'active'
          AND subscription_end IS NOT NULL
          AND subscription_end < DATE_SUB(CURDATE(), INTERVAL 1 DAY)
    ");
}

/* ── Récupérer le nb de notifs non lues ───────────────────── */
function unreadCount(PDO $pdo): int {
    if (isAdmin()) {
        $st = $pdo->query("SELECT COUNT(*) FROM notifications WHERE target='admin' AND is_read=0");
    } else {
        $uid = currentUserId();
        $st = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id=? AND target='user' AND is_read=0");
        $st->execute([$uid]);
    }
    return (int)$st->fetchColumn();
}

/* ── Helpers sécurité ─────────────────────────────────────── */
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void {
    if (empty($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf_token'] ?? '')) {
        http_response_code(403);
        die('Token CSRF invalide. Rechargez la page.');
    }
}
