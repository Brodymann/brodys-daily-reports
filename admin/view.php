<?php
require __DIR__.'/../config.php'; require __DIR__.'/../helpers.php'; require_login();
$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM reports WHERE id = :id");
$stmt->execute([':id'=>$id]);
$r = $stmt->fetch(); if(!$r){ http_response_code(404); exit('Not found'); }
$S = fn($k)=>h($r[$k] ?? '');
$J = fn($k)=>implode(', ', json_decode($r[$k] ?? '[]', true) ?: []);
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Report #<?=h($r['id'])?></title>
<style>body{font-family:system-ui;max-width:860px;margin:24px auto;padding:0 16px}
h2{margin-bottom:0} .meta{color:#555;margin-top:4px} .box{border:1px solid #eee;padding:12px;border-radius:8px;margin:10px 0}</style></head>
<body>
  <a href="/admin/reports.php">&larr; Back</a>
  <h2>Daily Progress Report</h2>
  <div class="meta">Date: <?=$S('report_date')?> • Student: <?=$S('student_name')?></div>

  <div class="box"><strong>Communication:</strong><br><?=$S('communication')?></div>
  <div class="box"><strong>Social:</strong><br><?=$S('social')?></div>
  <div class="box"><strong>Academic:</strong><br><?=$S('academic')?></div>
  <div class="box"><strong>Adaptive:</strong><br><?=$S('adaptive')?></div>
  <div class="box"><strong>Specialists:</strong> <?=h($J('specialists'))?></div>
  <div class="box"><strong>Food/Drink:</strong><br><?=$S('food_drink')?></div>
  <div class="box"><strong>Bathroom:</strong> <?=h($J('bathroom'))?></div>
  <div class="box"><strong>Please Send In:</strong><br><?=$S('send_in')?></div>
  <div class="box"><strong>Notes:</strong><br><?=$S('notes')?></div>
</body></html>
