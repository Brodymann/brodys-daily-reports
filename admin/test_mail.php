<?php
// admin/test_email.php

error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1) Load app config (this also loads Composer + .env via config.php)
require __DIR__ . '/../config.php';

// 2) Load the notifier
require __DIR__ . '/../lib/notify.php';

// (Optional) quick sanity checks
echo "<pre>NOTIFY_TO: "; var_dump(defined('NOTIFY_TO') ? NOTIFY_TO : '(not defined)'); echo "</pre>";
echo "SMTP_HOST defined? "; var_dump(defined('SMTP_HOST'));
echo "Autoloader present? "; var_dump(file_exists(__DIR__ . '/../vendor/autoload.php'));
echo "PHPMailer class available? "; var_dump(class_exists('PHPMailer\\PHPMailer\\PHPMailer'));
echo "</pre>";

// 3) Fake report to send a test email
$r = [
  'id' => 999,
  'report_date' => date('Y-m-d'),
  'student_name' => 'Test Student',
  'notes' => 'This is a test email from Brody Daily Reports.'
];

try {
  notify_report_created($r);
  echo "✅ Test email triggered — check your inbox: " . NOTIFY_TO;
} catch (Throwable $e) {
  echo "❌ Exception: " . $e->getMessage();
}
