<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';
require_once __DIR__ . '/../config.php';   // bring in SMTP_* and NOTIFY_TO

function notify_report_created(array $r): void {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 10;

        // From should match authenticated user for Gmail
        $mail->setFrom(SMTP_USER, 'Brody Daily Reports');
        $mail->addAddress(NOTIFY_TO);

        $date    = !empty($r['report_date']) ? date('d-m-Y', strtotime($r['report_date'])) : 'Unknown date';
        $student = $r['student_name'] ?? 'Unknown';
        $viewUrl = "https://brodys.site/admin/view.php?id=" . urlencode($r['id']);

        $mail->Subject = "New Daily Report — {$student} ({$date})";
        $mail->Body    = "New report for {$student} on {$date}\n\nView it here: {$viewUrl}";

        $mail->send();
    } catch (Exception $e) {
        error_log("Notify email failed: " . $mail->ErrorInfo);
    }
}
