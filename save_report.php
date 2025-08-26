<?php
require __DIR__.'/config.php';
require __DIR__.'/helpers.php';
csrf_check();

$student = trim($_POST['student_name'] ?? '');
$date    = $_POST['report_date'] ?? '';
if ($student === '' || $date === '') { http_response_code(422); exit('Name and Date required.'); }

$specialists = $_POST['specialists'] ?? [];
$bathroom    = $_POST['bathroom'] ?? [];

$sql = "INSERT INTO reports
(student_name, report_date, communication, social, academic, adaptive, specialists, food_drink, bathroom, send_in, notes)
VALUES (:student, :report_date, :communication, :social, :academic, :adaptive, :specialists, :food, :bathroom, :send_in, :notes)";

$stmt = db()->prepare($sql);
$stmt->execute([
  ':student'      => $student,
  ':report_date'  => $date,
  ':communication'=> $_POST['communication'] ?? null,
  ':social'       => $_POST['social'] ?? null,
  ':academic'     => $_POST['academic'] ?? null,
  ':adaptive'     => $_POST['adaptive'] ?? null,
  ':specialists'  => json_encode(array_values($specialists), JSON_UNESCAPED_UNICODE),
  ':food'         => $_POST['food_drink'] ?? null,
  ':bathroom'     => json_encode(array_values($bathroom), JSON_UNESCAPED_UNICODE),
  ':send_in'      => $_POST['send_in'] ?? null,
  ':notes'        => $_POST['notes'] ?? null,
]);

header('Location: /index.php?ok=1');
