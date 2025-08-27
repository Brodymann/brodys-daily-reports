<?php
// config.php
declare(strict_types=1);
session_start();

// DreamHost credentials
const DB_HOST = 'mysql-1.kylebaumann.com';
const DB_NAME = 'brody_reports';
const DB_USER = 'brody_user';
const DB_PASS = 'B0dy!m00vin81';

const APP_NAME = "Brody's Daily Progress Report";
const ADMIN_EMAIL_FROM = 'admin@brodys.site'; // optional

// --- Email / SMTP (Gmail App Password required) ---
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'kylebaumann@gmail.com');                 // your Gmail
define('SMTP_PASS', 'fvrsgvsudswrzsek');             // Google App Password
define('SMTP_PORT', 587);
define('NOTIFY_TO', 'kylebaumann@gmail.com');                 // where to receive notifications

function db(): PDO {
  static $pdo = null;
  if ($pdo === null) {
    $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  }
  return $pdo;
}
