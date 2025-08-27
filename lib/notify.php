<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

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

        $mail->setFrom(SMTP_USER, 'Brody Daily Reports');
        $mail->addAddress(NOTIFY_TO);

        $date    = !empty($r['report_date']) ? date('d-m-Y', strtotime($r['report_date'])) : 'Unknown date';
        $student = $r['student_name'] ?? 'Unknown';
        $viewUrl = "https://brodys.site/admin/view.php?id=" . urlencode($r['id']);
        $notesPreview = trim($r['notes'] ?? '') !== '' ? mb_strimwidth($r['notes'], 0, 120, '…') : '—';

        $mail->Subject = "New Daily Report — {$student} ({$date})";
        $mail->Body    =
            "New report added:\n".
            "Date: {$date}\n".
            "Student: {$student}\n".
            "Notes: {$notesPreview}\n\n".
            "View: {$viewUrl}\n";

        $mail->send();
    } catch (Exception $e) {
        error_log("Notify email failed: ".$mail->ErrorInfo);
    }
}
