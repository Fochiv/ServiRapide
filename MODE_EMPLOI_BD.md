# MODE D'EMPLOI — Base de données ServiRapide
## Guide complet d'installation et d'utilisation

---

## 📁 FICHIERS LIVRÉS

| Fichier | Rôle |
|---|---|
| `database.sql` | Schéma complet de la base de données |
| `config.php` | Configuration BD + paramètres globaux |
| `auth.php` | Gestion sessions, sécurité, notifications auto |
| `login.php` | Page de connexion (clients + admin) |
| `dashboard_user.php` | Espace membre client |
| `dashboard_admin.php` | Panneau d'administration |
| `register_handler.php` | Traitement du formulaire d'inscription |

---

## 🗄️ ÉTAPE 1 — CRÉER LA BASE DE DONNÉES

### Option A : phpMyAdmin (hébergement cPanel / Infomaniak / OVH…)
1. Connectez-vous à phpMyAdmin
2. Cliquez **Nouvelle base de données** → nommez-la `servirapide`
3. Encodage : `utf8mb4_unicode_ci`
4. Cliquez **Importer** → sélectionnez `database.sql` → **Exécuter**

### Option B : Ligne de commande (serveur local WAMP/XAMPP ou VPS)
```bash
mysql -u root -p < database.sql
```

### Option C : Serveur local (XAMPP / WAMP / Laragon)
1. Démarrez Apache + MySQL
2. Ouvrez `http://localhost/phpmyadmin`
3. Suivez l'option A ci-dessus

---

## ⚙️ ÉTAPE 2 — CONFIGURER config.php

Ouvrez `config.php` et modifiez ces lignes :

```php
define('DB_HOST', 'localhost');   // Généralement 'localhost'
define('DB_USER', 'root');        // Votre utilisateur MySQL
define('DB_PASS', '');            // Votre mot de passe MySQL
define('DB_NAME', 'servirapide'); // Nom de la base créée
```

---

## 📂 ÉTAPE 3 — STRUCTURE DES FICHIERS

Placez tous les fichiers dans le même dossier que votre `index.php` :

```
votre_site/
├── index.php           ← Site principal (existant)
├── style.css           ← CSS (existant)
├── script.js           ← JS (existant)
├── logo.png            ← Logo (existant)
├── favicon.png         ← Favicon (existant)
├── config.php          ← NOUVEAU
├── auth.php            ← NOUVEAU
├── login.php           ← NOUVEAU
├── dashboard_user.php  ← NOUVEAU
├── dashboard_admin.php ← NOUVEAU
├── register_handler.php← NOUVEAU
├── services/           ← Photos services (existant)
└── travail/            ← Photos travaux (existant)
```

---

## 🔗 ÉTAPE 4 — CONNECTER LE FORMULAIRE D'INSCRIPTION

Dans votre `index.php`, modifiez la balise `<form>` de la section **#register** :

**Avant :**
```html
<form class="subscribe-form" method="post" action="#register">
```

**Après :**
```html
<form class="subscribe-form" method="post" action="register_handler.php">
```

Ajoutez aussi la gestion du retour de succès/erreur en haut de `index.php`,
juste après `$submitted = ...` :

```php
// Message retour inscription BD
$registered = isset($_GET['registered']);
$regError   = $_GET['err'] ?? '';
```

Et dans le HTML du formulaire, remplacez le bloc `<?php if ($submitted):` par :
```php
<?php if ($registered): ?>
  <div class="success-message">
    <i class="fa-solid fa-circle-check"></i>
    Merci ! Votre demande est enregistrée. Vous serez contacté(e) sur WhatsApp pour valider le paiement.
  </div>
<?php elseif ($regError): ?>
  <div style="background:#fee2e2;border:1.5px solid #fca5a5;border-radius:14px;padding:14px;color:#b91c1c;font-weight:700;font-size:13px">
    <i class="fa-solid fa-circle-exclamation"></i>
    <?= htmlspecialchars($regError) ?>
  </div>
<?php endif; ?>
```

---

## 🔐 ÉTAPE 5 — ACCÈS ADMINISTRATEUR

### Première connexion
- URL : `https://votre-site.com/login.php`
- Onglet : **Administration**
- Identifiant : `admin`
- Mot de passe : `Admin2026!`

### ⚠️ IMPORTANT : Changer le mot de passe admin
Exécutez cette requête SQL dans phpMyAdmin :

```sql
UPDATE admins
SET password_hash = '$2y$12$VOTRE_HASH_ICI'
WHERE username = 'admin';
```

Pour générer un hash bcrypt en PHP :
```php
<?php echo password_hash('VotreNouveauMotDePasse', PASSWORD_BCRYPT); ?>
```

---

## 🔄 FLUX COMPLET D'UN ABONNÉ

```
1. Client remplit le formulaire index.php
        ↓
2. register_handler.php insère dans la table users (status = 'pending')
        ↓
3. L'admin reçoit une notification dans dashboard_admin.php
        ↓
4. Admin contacte le client sur WhatsApp → vérifie le paiement
        ↓
5. Admin clique "Activer" dans dashboard_admin.php
   → status = 'active'
   → subscription_start = aujourd'hui
   → subscription_end = aujourd'hui + 30 jours
   → notification envoyée au client avec mot de passe initial
        ↓
6. Client se connecte sur login.php avec son numéro + mot de passe
        ↓
7. Client accède à dashboard_user.php
        ↓
8. Admin ajoute les services rendus via le bouton "Service"
        ↓
9. 7 jours avant expiration → notifications auto (client + admin)
        ↓
10. Renouvellement via WhatsApp → admin "Activer" de nouveau
```

---

## 💡 MOT DE PASSE CLIENT INITIAL

Lors de l'activation, le mot de passe initial du client est :
- **Les 4 derniers chiffres de son numéro de téléphone**
  - Ex : téléphone `+237 6 72 37 09 50` → mot de passe = `0950`
- Ou `1234` si le numéro contient moins de 4 chiffres

L'admin communique ce mot de passe au client **via WhatsApp** au moment de l'activation.

---

## 📊 STRUCTURE DES TABLES

### `users` — Clients abonnés
| Colonne | Type | Description |
|---|---|---|
| id | INT AUTO_INCREMENT | Identifiant unique |
| full_name | VARCHAR(100) | Nom complet |
| phone | VARCHAR(20) UNIQUE | Numéro tel (login) |
| email | VARCHAR(100) | Email (optionnel) |
| address | VARCHAR(200) | Adresse complète |
| neighborhood | VARCHAR(100) | Quartier |
| plan_category | ENUM A-F | Formule souscrite |
| payment_method | VARCHAR(50) | MTN MoMo / Orange… |
| preferred_day | VARCHAR(20) | Jour préféré |
| password_hash | VARCHAR(255) | Mot de passe bcrypt |
| **status** | ENUM | `pending` / `active` / `inactive` |
| **subscription_start** | DATE | Début abonnement |
| **subscription_end** | DATE | Fin abonnement |
| registration_fee_paid | TINYINT | 1 = frais 10 000 CFA payés |
| notes | TEXT | Notes privées admin |

### `services_rendered` — Historique interventions
| Colonne | Type | Description |
|---|---|---|
| id | INT AUTO_INCREMENT | ID intervention |
| user_id | INT | Référence client |
| service_type | ENUM | Type de service |
| service_date | DATE | Date de l'intervention |
| status | ENUM | `planifie`/`en_cours`/`termine`/`annule` |
| duration_minutes | SMALLINT | Durée effective |
| technician_name | VARCHAR(100) | Nom prestataire |
| notes | TEXT | Observations |

### `notifications` — Alertes système
| Colonne | Type | Description |
|---|---|---|
| user_id | INT | 0 = admin, > 0 = client |
| type | ENUM | `expiry_warning`/`activation`/etc. |
| target | ENUM | `user` ou `admin` |
| is_read | TINYINT | 0 = non lu |

### `plan_config` — Configuration formules
Modifiable directement en SQL sans toucher au code PHP.

---

## 🔔 SYSTÈME DE NOTIFICATIONS AUTOMATIQUES

Les notifications sont générées automatiquement à chaque chargement
de page par la fonction `checkExpiryNotifications()` dans `auth.php`.

**Règle de déclenchement :**
- Abonnement actif expirant dans ≤ 7 jours (configurable via `DAYS_BEFORE_EXPIRY` dans `config.php`)
- Une seule notification générée par tranche de 12 heures (anti-doublon)

**Notifications générées :**
- Pour le client : message d'alerte dans son dashboard
- Pour l'admin : alerte dans le panneau admin

**Expiration automatique :**
- Les comptes dont `subscription_end` est dépassé de > 1 jour passent automatiquement en `inactive`

---

## 🛠️ PERSONNALISATION RAPIDE

### Changer la durée d'abonnement (ex: 31 jours)
```php
// config.php
define('SUB_DURATION_DAYS', 31);
```

### Changer le seuil d'alerte (ex: 5 jours)
```php
define('DAYS_BEFORE_EXPIRY', 5);
```

### Modifier les prix des formules
```sql
UPDATE plan_config SET price_cfa = 4000 WHERE plan_key = 'A';
```

### Ajouter un admin supplémentaire
```sql
INSERT INTO admins (username, password_hash, full_name, email)
VALUES ('marie', '$2y$12$...hash...', 'Marie Dupont', 'marie@servirapide.cm');
```

---

## 🚀 LIENS UTILES APRÈS INSTALLATION

| Page | URL |
|---|---|
| Site principal | `/index.php` |
| Connexion | `/login.php` |
| Espace client | `/dashboard_user.php` |
| Admin | `/dashboard_admin.php` |

---

## ⚡ REQUÊTES SQL UTILES

```sql
-- Voir tous les abonnés actifs
SELECT full_name, phone, plan_category, subscription_end
FROM users WHERE status = 'active';

-- Abonnements expirant dans 7 jours
SELECT * FROM v_expiring_soon;

-- Résumé complet de tous les clients
SELECT * FROM v_client_summary;

-- Réinitialiser le mot de passe d'un client (phone = 237672370950)
UPDATE users
SET password_hash = '$2y$12$...'
WHERE phone = '237672370950';

-- Compter les services par type ce mois
SELECT service_type, COUNT(*) as total
FROM services_rendered
WHERE MONTH(service_date) = MONTH(CURDATE())
GROUP BY service_type;
```

---

*ServiRapide — Douala, Cameroun | BEPANDA L'AN 2000*
*Documentation générée le <?= date('d/m/Y') ?>*
