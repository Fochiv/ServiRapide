<?php
// ============================================================
//  ServiRapide — login.php
// ============================================================
require_once 'config.php';
require_once 'auth.php';

// Déjà connecté → rediriger
if (!empty($_SESSION['sr_user_id'])) {
    header('Location: ' . (isAdmin() ? 'dashboard_admin.php' : 'dashboard_user.php'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $type  = $_POST['login_type'] ?? 'user'; // 'user' ou 'admin'

    if ($type === 'admin') {
        $st = $pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $st->execute([$phone]); // le champ "phone" contient ici le username admin
        $admin = $st->fetch();
        if ($admin && password_verify($pass, $admin['password_hash'])) {
            $_SESSION['sr_user_id']  = 'admin_' . $admin['id'];
            $_SESSION['sr_is_admin'] = true;
            $_SESSION['sr_name']     = $admin['full_name'];
            header('Location: dashboard_admin.php');
            exit;
        }
        $error = 'Identifiant ou mot de passe administrateur invalide.';

    } else {
        // Connexion client par numéro de téléphone
        $st = $pdo->prepare('SELECT * FROM users WHERE phone = ?');
        $st->execute([$phone]);
        $user = $st->fetch();
        if ($user && password_verify($pass, $user['password_hash'])) {
            $_SESSION['sr_user_id'] = $user['id'];
            $_SESSION['sr_name']    = $user['full_name'];
            header('Location: dashboard_user.php');
            exit;
        }
        $error = 'Numéro de téléphone ou mot de passe incorrect.';
    }
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
    .login-tabs { display: flex; gap: 6px; margin-bottom: 28px; background: var(--bg); border-radius: 14px; padding: 5px; }
    .login-tab {
      flex: 1; border: none; background: transparent; border-radius: 11px;
      padding: 10px; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 13px;
      color: var(--muted); cursor: pointer; transition: .2s;
    }
    .login-tab.active { background: var(--dark); color: #fff; }
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
    .login-footer { text-align: center; margin-top: 20px; color: var(--muted); font-size: 13px; }
    .login-footer a { color: var(--green); font-weight: 800; }
    .hint-box {
      background: var(--light); border-radius: 12px; padding: 12px 16px;
      margin-bottom: 20px; font-size: 12px; color: var(--dark);
      border-left: 4px solid var(--green);
    }
    .hint-box strong { color: var(--green); }
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

    <!-- Onglets Utilisateur / Admin -->
    <div class="login-tabs">
      <button class="login-tab active" id="tabUser" onclick="switchTab('user')">
        <i class="fa-regular fa-user"></i> Espace Client
      </button>
      <button class="login-tab" id="tabAdmin" onclick="switchTab('admin')">
        <i class="fa-solid fa-lock"></i> Administration
      </button>
    </div>

    <!-- Titre dynamique -->
    <div id="titleUser">
      <h2>Bon retour 👋</h2>
      <p>Connectez-vous avec votre numéro de téléphone.</p>
    </div>
    <div id="titleAdmin" style="display:none">
      <h2>Panneau Admin 🔐</h2>
      <p>Accès réservé à l'équipe ServiRapide.</p>
    </div>

    <?php if ($error): ?>
    <div class="error-msg">
      <i class="fa-solid fa-circle-exclamation"></i>
      <?= e($error) ?>
    </div>
    <?php endif; ?>

    <!-- Indice mot de passe initial -->
    <div class="hint-box" id="hintUser">
      <strong>Premier accès :</strong> votre mot de passe initial vous a été communiqué par WhatsApp lors de l'activation de votre compte.
    </div>

    <form method="post">
      <input type="hidden" name="login_type" id="loginType" value="user">

      <div class="form-field">
        <label id="labelPhone" for="phone">Numéro de téléphone</label>
        <div class="field-wrap">
          <i class="fa-solid fa-phone" id="iconPhone"></i>
          <input type="text" id="phone" name="phone"
                 placeholder="Ex: +237 6 72 37 09 50"
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

<script>
function switchTab(type) {
  const isAdmin = type === 'admin';
  document.getElementById('loginType').value = type;

  document.getElementById('tabUser').classList.toggle('active', !isAdmin);
  document.getElementById('tabAdmin').classList.toggle('active', isAdmin);

  document.getElementById('titleUser').style.display  = isAdmin ? 'none' : '';
  document.getElementById('titleAdmin').style.display = isAdmin ? '' : 'none';
  document.getElementById('hintUser').style.display   = isAdmin ? 'none' : '';

  const labelPhone = document.getElementById('labelPhone');
  const iconPhone  = document.getElementById('iconPhone');
  const phoneInput = document.getElementById('phone');

  if (isAdmin) {
    labelPhone.textContent = "Nom d'utilisateur";
    iconPhone.className    = 'fa-solid fa-user-shield';
    phoneInput.placeholder = 'admin';
    phoneInput.type        = 'text';
  } else {
    labelPhone.textContent = 'Numéro de téléphone';
    iconPhone.className    = 'fa-solid fa-phone';
    phoneInput.placeholder = 'Ex: +237 6 72 37 09 50';
    phoneInput.type        = 'tel';
  }

  phoneInput.value = '';
  document.getElementById('password').value = '';
}

<?php if ($_POST['login_type'] ?? '' === 'admin'): ?>
switchTab('admin');
<?php endif; ?>
</script>

</body>
</html>
