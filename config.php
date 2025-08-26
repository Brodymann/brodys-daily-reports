<?php
// config.php
declare(strict_types=1);
session_start();

// TODO: fill with your DreamHost credentials
const DB_HOST = 'mysql-1.kylebaumann.com';
const DB_NAME = 'brody_reports';
const DB_USER = 'brody_user';
const DB_PASS = 'B0dy!m00vin81';

const APP_NAME = "BRODY'S DAILY PROGRESS REPORT";
const ADMIN_EMAIL_FROM = 'admin@brodys.site'; // optional

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
