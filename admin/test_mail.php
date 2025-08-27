<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$root     = dirname(__DIR__);
$autoload = $root . '/vendor/autoload.php';
$notify   = $root . '/lib/notify.php';

// 1) Load Composer FIRST
require $autoload;

// 2) Now the class should be loadable
echo "<pre>";
echo "autoload loaded.\n";
echo "class_exists(PHPMailer)? "; var_dump(class_exists('PHPMailer\\PHPMailer\\PHPMailer'));

// 3) Sanity: does PHPMailer.php exist where Composer expects?
$phpmailerSrc = $root . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
echo "PHPMailer.php exists? "; var_dump(file_exists($phpmailerSrc));

// 4) Check Composer PSR-4 map
$psr4 = require $root . '/vendor/composer/autoload_psr4.php';
echo "PSR-4 mapping has PHPMailer? "; var_dump(isset($psr4['PHPMailer\\PHPMailer\\']));
if (isset($psr4['PHPMailer\\PHPMailer\\'])) {
  echo "Mapped to:\n"; print_r($psr4['PHPMailer\\PHPMailer\\']);
}
echo "</pre>";

// 5) If everything above looks good, include notifier and try sending
require $notify;

$r = [
  'id' => 999,
  'report_date' => date('Y-m-d'),
  'student_name' => 'Test Student',
  'notes' => 'This is a test email from Brody Daily Reports.'
];

try {
  notify_report_created($r);
  echo "<p>✅ notify_report_created() finished.</p>";
} catch (Throwable $e) {
  echo "<p>❌ Exception: " . $e->getMessage() . "</p>";
}
