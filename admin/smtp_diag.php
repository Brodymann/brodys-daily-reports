<?php
// /admin/smtp_diag.php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../config.php';

echo "<h3>Connectivity checks</h3><pre>";
$host = SMTP_HOST; $port = (int)SMTP_PORT;
$fp = @fsockopen($host, $port, $errno, $errstr, 10);
echo "fsockopen($host:$port): "; var_dump((bool)$fp);
if (!$fp) echo "errno=$errno errstr=$errstr\n";
if ($fp) fclose($fp);
echo "</pre>";

require __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    // SMTP settings (same as notify.php)
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = SMTP_PORT;
    $mail->CharSet    = 'UTF-8';
    $mail->Timeout    = 20;

    // Show verbose SMTP dialog on the page
    $mail->SMTPDebug  = 2;        // 0=off, 2=client+server
    $mail->Debugoutput = 'html';

    $mail->setFrom(SMTP_USER, 'Brody Daily Reports');
    $mail->addAddress(NOTIFY_TO);

    $mail->Subject = 'SMTP DIAG: test from brodys.site';
    $mail->Body    = "Hello! This is a diagnostic email from brodys.site.\n\nTime: ".date('c');

    echo "<h3>Attempting to send…</h3>";
    $ok = $mail->send();
    echo $ok ? "<p>✅ send() returned true</p>" : "<p>❌ send() returned false</p>";
} catch (Exception $e) {
    echo "<p>❌ Exception: ".htmlentities($mail->ErrorInfo ?: $e->getMessage())."</p>";
}
