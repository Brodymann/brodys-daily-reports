<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$autoload = __DIR__ . '/../vendor/autoload.php';
$notify   = __DIR__ . '/../lib/notify.php';
$config   = __DIR__ . '/../config.php';

var_dump(class_exists('PHPMailer\\PHPMailer\\PHPMailer')); // should be true

echo "<pre>";
echo "autoload exists? "; var_dump(file_exists($autoload));
echo "notify exists?   "; var_dump(file_exists($notify));
echo "config exists?   "; var_dump(file_exists($config));
echo "</pre>";

require $notify;

$r = [
  'id' => 999,
  'report_date' => date('Y-m-d'),
  'student_name' => 'Test Student',
  'notes' => 'This is a test email from Brody Daily Reports.'
];

try {
  notify_report_created($r);
  echo "✅ notify_report_created() finished.";
} catch (Throwable $e) {
  echo "❌ Exception: " . $e->getMessage();
}
