<?php
require __DIR__ . '/../lib/notify.php';

// Fake report data for testing
$r = [
    'id' => 999,
    'report_date' => date('Y-m-d'),
    'student_name' => 'Test Student',
    'notes' => 'This is a test email from Brody Daily Reports.'
];

notify_report_created($r);

echo "✅ Test email triggered — check your inbox at " . NOTIFY_TO;
