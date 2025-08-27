<?php
// config.php
declare(strict_types=1);
session_start();

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database constants
const DB_HOST = $_ENV['DB_HOST'];
const DB_NAME = $_ENV['DB_NAME'];
const DB_USER = $_ENV['DB_USER'];
const DB_PASS = $_ENV['DB_PASS'];

const APP_NAME = $_ENV['APP_NAME'] ?? "Brody's Daily Progress Report";

// Email constants
define('SMTP_HOST', $_ENV['SMTP_HOST']);
define('SMTP_USER', $_ENV['SMTP_USER']);
define('SMTP_PASS', $_ENV['SMTP_PASS']);
define('SMTP_PORT', (int)$_ENV['SMTP_PORT']);
define('NOTIFY_TO', $_ENV['NOTIFY_TO']);

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
