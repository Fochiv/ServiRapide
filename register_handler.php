<?php
// ============================================================
//  ServiRapide — register_handler.php
//  Reçoit le formulaire d'inscription de index.php
//  et enregistre le client en base de données (status: pending)
// ============================================================
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php#register');
    exit;
}

$name      = trim($_POST['name']      ?? '');
$address   = trim($_POST['address']   ?? '');
$quarter   = trim($_POST['neighborhood'] ?? '');
$phone     = trim($_POST['phone']     ?? '');
$email     = trim($_POST['email']     ?? '');
$plan      = $_POST['plan']           ?? 'A';
$payment   = $_POST['payment']        ?? 'MTN MoMo';
$day       = $_POST['day']            ?? '';

// Validation basique
$errors = [];
if (strlen($name)  < 2)  $errors[] = 'Nom invalide.';
if (strlen($phone) < 8)  $errors[] = 'Numéro de téléphone invalide.';
if (!in_array($plan, ['A','B','C','D','E','F'], true)) $errors[] = 'Formule invalide.';

if (empty($errors)) {
    // Vérifier si le numéro existe déjà
    $stCheck = $pdo->prepare('SELECT id FROM users WHERE phone = ?');
    $stCheck->execute([$phone]);
    if ($stCheck->fetch()) {
        $errors[] = 'Ce numéro de téléphone est déjà enregistré. Contactez-nous sur WhatsApp.';
    }
}

if (empty($errors)) {
    // Mot de passe initial = 4 derniers chiffres du tel, sinon DEFAULT_PASSWORD
    preg_match_all('/\d/', $phone, $digits);
    $numDigits = implode('', $digits[0]);
    $initPass  = strlen($numDigits) >= 4 ? substr($numDigits, -4) : DEFAULT_PASSWORD;
    $hash      = password_hash($initPass, PASSWORD_BCRYPT);

    $st = $pdo->prepare('
        INSERT INTO users
            (full_name, email, phone, address, neighborhood, plan_category, payment_method, preferred_day, password_hash, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "pending")
    ');
    $st->execute([$name, $email ?: null, $phone, $address, $quarter, $plan, $payment, $day, $hash]);
    $newId = $pdo->lastInsertId();

    // Notification admin
    $pdo->prepare("
        INSERT INTO notifications (user_id, type, message, target)
        VALUES (0, 'info', ?, 'admin')
    ")->execute(["📝 Nouvelle inscription : $name — Tél: $phone — Formule: $plan. En attente de validation paiement."]);

    // Rediriger avec succès
    header('Location: index.php?registered=1#register');
    exit;
}

// En cas d'erreur, revenir au formulaire
$errMsg = implode(' ', $errors);
header("Location: index.php?err=" . urlencode($errMsg) . "#register");
exit;
