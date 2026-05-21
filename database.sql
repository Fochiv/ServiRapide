-- ============================================================
--  ServiRapide — Base de données complète
--  MySQL 5.7+ / MariaDB 10.3+
--  Encodage : utf8mb4 (supporte emojis & caractères FR)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `servirapide`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `servirapide`;

-- ------------------------------------------------------------
-- 1. TABLE : admins
--    Comptes administrateurs du panneau de gestion
-- ------------------------------------------------------------
CREATE TABLE `admins` (
  `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(50)     NOT NULL,
  `password_hash` VARCHAR(255)    NOT NULL,
  `full_name`     VARCHAR(100)    NULL,
  `email`         VARCHAR(100)    NULL,
  `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Compte admin par défaut — mot de passe : Admin2026!
-- (hash bcrypt généré par PHP password_hash)
INSERT INTO `admins` (`username`, `password_hash`, `full_name`, `email`) VALUES
(
  'admin',
  '$2y$12$l/q4VSg94fxMc8qVRjNL6uw7BsyAwCWacnKFCRk3gndwzlO5slCc2',
  'Administrateur ServiRapide',
  'admin@servirapide.cm'
);
-- ⚠️  Changez ce mot de passe dès la première connexion !
--     Via : Admin Dashboard → Paramètres → Changer mot de passe

-- ------------------------------------------------------------
-- 2. TABLE : users
--    Clients abonnés (inscrits via le formulaire ou ajoutés manuellement)
-- ------------------------------------------------------------
CREATE TABLE `users` (
  `id`                    INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `full_name`             VARCHAR(100)    NOT NULL,
  `email`                 VARCHAR(100)    NULL,
  `phone`                 VARCHAR(20)     NOT NULL,
  `address`               VARCHAR(200)    NULL,
  `neighborhood`          VARCHAR(100)    NULL,
  `plan_category`         ENUM('A','B','C','D','E','F') NOT NULL DEFAULT 'A',
  `payment_method`        VARCHAR(50)     NULL DEFAULT 'MTN MoMo',
  `preferred_day`         VARCHAR(20)     NULL,
  `password_hash`         VARCHAR(255)    NOT NULL,
  -- Status : pending (en attente de validation), active (abonnement en cours),
  --          inactive (abonnement expiré/suspendu)
  `status`                ENUM('pending','active','inactive') NOT NULL DEFAULT 'pending',
  `subscription_start`    DATE            NULL COMMENT 'Date de début d abonnement (fixée par l admin)',
  `subscription_end`      DATE            NULL COMMENT 'Date de fin d abonnement (start + 30j par défaut)',
  `registration_fee_paid` TINYINT(1)      NOT NULL DEFAULT 0 COMMENT '1 = frais inscription 10000 CFA réglés',
  `notes`                 TEXT            NULL COMMENT 'Notes privées admin (ex: paiement WhatsApp validé)',
  `created_at`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_phone` (`phone`),
  KEY `idx_status` (`status`),
  KEY `idx_subscription_end` (`subscription_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. TABLE : services_rendered
--    Historique de toutes les interventions à domicile
-- ------------------------------------------------------------
CREATE TABLE `services_rendered` (
  `id`               INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `user_id`          INT UNSIGNED    NOT NULL,
  `service_type`     ENUM(
                       'nettoyage','lessive','repassage','vaisselle',
                       'cuisine','plomberie','couture','chaussures'
                     ) NOT NULL,
  `service_date`     DATE            NOT NULL,
  `status`           ENUM('planifie','en_cours','termine','annule') NOT NULL DEFAULT 'planifie',
  `duration_minutes` SMALLINT UNSIGNED NULL COMMENT 'Durée effective de l intervention',
  `technician_name`  VARCHAR(100)    NULL COMMENT 'Nom du prestataire ayant effectué le service',
  `notes`            TEXT            NULL,
  `created_at`       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_date` (`user_id`, `service_date`),
  CONSTRAINT `fk_srv_user` FOREIGN KEY (`user_id`)
    REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. TABLE : notifications
--    Notifications pour utilisateurs ET admins
--    user_id = 0  → notification destinée à l'admin
--    user_id > 0  → notification destinée au client
-- ------------------------------------------------------------
CREATE TABLE `notifications` (
  `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED  NOT NULL DEFAULT 0,
  `type`       ENUM('expiry_warning','activation','new_service','renewal','info') NOT NULL,
  `message`    TEXT          NOT NULL,
  `target`     ENUM('user','admin') NOT NULL DEFAULT 'user',
  `is_read`    TINYINT(1)    NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_unread`  (`user_id`, `is_read`),
  KEY `idx_admin_unread` (`target`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. TABLE : plan_config  (lecture seule, paramétrable)
--    Paramètres des abonnements — modifiables sans toucher au code
-- ------------------------------------------------------------
CREATE TABLE `plan_config` (
  `plan_key`        CHAR(1)       NOT NULL,
  `plan_name`       VARCHAR(50)   NOT NULL,
  `housing_type`    VARCHAR(80)   NOT NULL,
  `price_cfa`       INT UNSIGNED  NOT NULL COMMENT 'Prix mensuel en CFA',
  `max_services`    TINYINT       NOT NULL COMMENT 'Nb max services autorisés / mois',
  `duration_months` TINYINT       NOT NULL DEFAULT 1 COMMENT 'Durée d abonnement en mois',
  `is_active`       TINYINT(1)    NOT NULL DEFAULT 1,
  PRIMARY KEY (`plan_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `plan_config` VALUES
  ('A', 'Catégorie A', 'Studio',          3000,  2, 1, 1),
  ('B', 'Catégorie B', 'Appart. 1 ch.',   5000,  2, 1, 1),
  ('C', 'Catégorie C', 'Appart. 2 ch.',   7000,  3, 1, 1),
  ('D', 'Catégorie D', 'Maison 1 ch.',    8000,  3, 1, 1),
  ('E', 'Catégorie E', 'Maison 2 ch.',    10000, 4, 1, 1),
  ('F', 'Catégorie F', 'Maison 3 ch.',    15000, 6, 1, 1);

-- ============================================================
-- VUES UTILES
-- ============================================================

-- Vue : liste des abonnements expirant dans les 7 prochains jours
CREATE OR REPLACE VIEW `v_expiring_soon` AS
  SELECT
    u.id, u.full_name, u.phone, u.plan_category,
    u.subscription_end,
    DATEDIFF(u.subscription_end, CURDATE()) AS days_left
  FROM `users` u
  WHERE u.status = 'active'
    AND u.subscription_end IS NOT NULL
    AND DATEDIFF(u.subscription_end, CURDATE()) BETWEEN 0 AND 7;

-- Vue : comptage des services du mois courant par client
CREATE OR REPLACE VIEW `v_monthly_service_count` AS
  SELECT
    user_id,
    COUNT(*) AS services_done,
    MONTH(service_date) AS month,
    YEAR(service_date)  AS year
  FROM `services_rendered`
  WHERE status != 'annule'
  GROUP BY user_id, MONTH(service_date), YEAR(service_date);

-- Vue : résumé complet client (pour l'admin)
CREATE OR REPLACE VIEW `v_client_summary` AS
  SELECT
    u.id,
    u.full_name,
    u.phone,
    u.email,
    u.plan_category,
    u.status,
    u.subscription_start,
    u.subscription_end,
    DATEDIFF(u.subscription_end, CURDATE()) AS days_remaining,
    u.registration_fee_paid,
    u.neighborhood,
    u.preferred_day,
    u.created_at,
    COALESCE(s.services_this_month, 0) AS services_this_month,
    p.price_cfa,
    p.max_services,
    p.housing_type
  FROM `users` u
  LEFT JOIN `plan_config` p ON p.plan_key = u.plan_category
  LEFT JOIN (
    SELECT user_id, COUNT(*) AS services_this_month
    FROM services_rendered
    WHERE MONTH(service_date) = MONTH(CURDATE())
      AND YEAR(service_date) = YEAR(CURDATE())
      AND status != 'annule'
    GROUP BY user_id
  ) s ON s.user_id = u.id;

-- ============================================================
-- FIN DU SCRIPT
-- Charger ce fichier avec : mysql -u root -p < database.sql
-- ============================================================
