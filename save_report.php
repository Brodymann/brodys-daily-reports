<?php
require __DIR__.'/config.php';
require __DIR__.'/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }
if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(403); exit; }

$pdo = new PDO(
  'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
  DB_USER, DB_PASS,
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$student_name = trim($_POST['student_name'] ?? 'Brody Baumann');
$report_date  = trim($_POST['report_date'] ?? date('Y-m-d'));
$communication = trim($_POST['communication'] ?? '');
$social        = trim($_POST['social'] ?? '');
$academic      = trim($_POST['academic'] ?? '');
$adaptive      = trim($_POST['adaptive'] ?? '');
$food_drink    = trim($_POST['food_drink'] ?? '');
$send_in       = trim($_POST['send_in'] ?? '');
$notes         = trim($_POST['notes'] ?? '');

/* specialists[] from checkboxes -> JSON array */
$specialists = $_POST['specialists'] ?? [];
if (!is_array($specialists)) { $specialists = []; }
$specialists_json = json_encode(array_values($specialists), JSON_UNESCAPED_UNICODE);

/* bathroom[changed|wet|bm|sat_on_toilet|went_on_toilet] -> JSON object */
$bath = $_POST['bathroom'] ?? [];
$bathroom = [
  'changed'        => (int)($bath['changed'] ?? 0),
  'wet'            => (int)($bath['wet'] ?? 0),
  'bm'             => (int)($bath['bm'] ?? 0),
  'sat_on_toilet'  => (int)($bath['sat_on_toilet'] ?? 0),
  'went_on_toilet' => (int)($bath['went_on_toilet'] ?? 0),
];
$bathroom_json = json_encode($bathroom, JSON_UNESCAPED_UNICODE);

/* insert */
$sql = "INSERT INTO reports
  (student_name, report_date, communication, social, academic, adaptive, specialists, food_drink, bathroom, send_in, notes)
  VALUES
  (:student_name, :report_date, :communication, :social, :academic, :adaptive, :specialists, :food_drink, :bathroom, :send_in, :notes)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':student_name' => $student_name,
  ':report_date'  => $report_date,
  ':communication'=> $communication,
  ':social'       => $social,
  ':academic'     => $academic,
  ':adaptive'     => $adaptive,
  ':specialists'  => $specialists_json,
  ':food_drink'   => $food_drink,
  ':bathroom'     => $bathroom_json,
  ':send_in'      => $send_in,
  ':notes'        => $notes,
]);

/* redirect back with success flag */
header('Location: /index.php?ok=1');
exit;
