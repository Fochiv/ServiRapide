<?php
// ============================================================
//  ServiRapide — login.php  (unified login)
// ============================================================
require_once 'config.php';
require_once 'auth.php';

// Already logged in → redirect
if (!empty($_SESSION['sr_user_id'])) {
    header('Location: ' . (isAdmin() ? 'dashboard_admin.php' : 'dashboard_user.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $pass       = $_POST['password'] ?? '';

    // 1️⃣ Check admin table first
    $st = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
    $st->execute([$identifier]);
    $admin = $st->fetch();

    if ($admin && password_verify($pass, $admin['password_hash'])) {
        $_SESSION['sr_user_id']  = 'admin_' . $admin['id'];
        $_SESSION['sr_is_admin'] = true;
        $_SESSION['sr_name']     = $admin['full_name'];
        header('Location: dashboard_admin.php');
        exit;
    }

    // 2️⃣ Check users table by phone
    $st = $pdo->prepare('SELECT * FROM users WHERE phone = ? AND status = "active"');
    $st->execute([$identifier]);
    $user = $st->fetch();

    if ($user && password_verify($pass, $user['password_hash'])) {
        $_SESSION['sr_user_id'] = $user['id'];
        $_SESSION['sr_name']    = $user['full_name'];
        header('Location: dashboard_user.php');
        exit;
    }

    // 3️⃣ Generic error (don't reveal which field is wrong)
    $error = 'Identifiant ou mot de passe incorrect.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — ServiRapide</title>
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Sora:wght@600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <style>
    body { min-height: 100vh; display: flex; flex-direction: column; background: linear-gradient(135deg, var(--dark), var(--mid)); }
    .login-wrap {
      flex: 1; display: flex; align-items: center; justify-content: center;
      padding: 40px 16px;
    }
    .login-card {
      background: #fff; border-radius: 32px; padding: 44px 40px;
      width: 100%; max-width: 440px;
      box-shadow: 0 40px 100px rgba(0,0,0,.28);
    }
    .login-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 32px; }
    .login-logo img { width: 50px; height: 50px; border-radius: 14px; object-fit: cover; }
    .login-logo strong { font-family: 'Sora', sans-serif; font-size: 22px; color: var(--dark); }
    .login-logo small { display: block; color: var(--green); font-size: 12px; }
    .login-card h2 { font-family: 'Sora', sans-serif; font-size: 24px; color: var(--dark); margin-bottom: 6px; }
    .login-card p { color: var(--muted); font-size: 14px; margin-bottom: 24px; }
    .form-field { display: grid; gap: 7px; font-weight: 800; font-size: 13px; color: var(--dark); margin-bottom: 16px; }
    .field-wrap { position: relative; }
    .field-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 15px; }
    .form-field input {
      width: 100%; border: 2px solid var(--border); border-radius: 13px;
      padding: 13px 14px 13px 42px; font-family: 'Outfit', sans-serif; font-size: 15px;
      outline: none; background: #fff; transition: border-color .2s;
    }
    .form-field input:focus { border-color: var(--green); }
    .error-msg {
      background: #fff5f5; border: 1.5px solid #fca5a5; border-radius: 12px;
      padding: 12px 16px; color: #b91c1c; font-weight: 700; font-size: 13px;
      display: flex; gap: 10px; align-items: center; margin-bottom: 18px;
    }
    .hint-box {
      background: var(--light); border-radius: 12px; padding: 12px 16px;
      margin-bottom: 20px; font-size: 12px; color: var(--dark);
      border-left: 4px solid var(--green);
    }
    .hint-box strong { color: var(--green); }
    .login-footer { text-align: center; margin-top: 20px; color: var(--muted); font-size: 13px; }
    .login-footer a { color: var(--green); font-weight: 800; }
    @media (max-width: 480px) { .login-card { padding: 32px 22px; } }
  </style>
</head>
<body>

<div class="login-wrap">
  <div class="login-card">

    <div class="login-logo">
      <img src="logo.png" alt="ServiRapide">
      <span>
        <strong>ServiRapide</strong>
        <small>Vous faciliter la vie !</small>
      </span>
    </div>

    <h2>Bon retour 👋</h2>
    <p>Connectez-vous pour accéder à votre espace.</p>

    <?php if ($error): ?>
    <div class="error-msg">
      <i class="fa-solid fa-circle-exclamation"></i>
      <?= e($error) ?>
    </div>
    <?php endif; ?>

    <div class="hint-box">
      <strong>Premier accès :</strong> votre mot de passe initial vous a été communiqué par WhatsApp lors de l'activation de votre compte.
    </div>

    <form method="post">
      <div class="form-field">
        <label for="identifier">Numéro de téléphone / Identifiant</label>
        <div class="field-wrap">
          <i class="fa-solid fa-user"></i>
          <input type="text" id="identifier" name="identifier"
                 placeholder="Ex: +237 6 72 37 09 50"
                 value="<?= e($_POST['identifier'] ?? '') ?>"
                 required autocomplete="username">
        </div>
      </div>

      <div class="form-field">
        <label for="password">Mot de passe</label>
        <div class="field-wrap">
          <i class="fa-solid fa-key"></i>
          <input type="password" id="password" name="password"
                 placeholder="••••••••" required autocomplete="current-password">
        </div>
      </div>

      <button type="submit" class="btn btn-primary full" style="margin-top:8px;font-size:16px;">
        <i class="fa-solid fa-arrow-right-to-bracket"></i> Se connecter
      </button>
    </form>

    <div class="login-footer">
      Pas encore abonné ?
      <a href="index.php#register">S'inscrire maintenant</a>
      &nbsp;·&nbsp;
      <a href="index.php">Accueil</a>
    </div>

  </div>
</div>

</body>
</html>