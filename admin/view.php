<?php
require __DIR__.'/../config.php';
require __DIR__.'/../helpers.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare("SELECT * FROM reports WHERE id = :id");
$stmt->execute([':id' => $id]);
$r = $stmt->fetch();
if (!$r) { http_response_code(404); exit('Not found'); }

$S = fn($k) => h($r[$k] ?? '');
$J = fn($k) => implode(', ', json_decode($r[$k] ?? '[]', true) ?: []);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Report #<?=h($r['id'])?></title>

  <!-- Global styles (background + blue container) -->
  <link rel="stylesheet" href="/assets/css/style.css">

  <style>
    /* --- Admin scroll fix (key part) --- */
    html, body {
      height: auto;           /* let page grow with content */
      min-height: 100dvh;     /* still fill the screen */
      overflow-y: auto;       /* enable scrolling */
      -webkit-overflow-scrolling: touch;
    }
    /* Ensure the main wrapper doesn't cap height or hide overflow */
    .container {
      height: auto;
      min-height: unset;
      overflow: visible;
      padding-bottom: max(16px, env(safe-area-inset-bottom)); /* breathing room at bottom */
    }

    /* Page-specific tweaks ONLY */
    .back-link { display:inline-block; margin-bottom:10px; color:#fff; text-decoration:none; }
    .back-link:hover { text-decoration:underline; }

    .report-header { text-align:center; margin-bottom:20px; }
    .report-header h2 { margin:0; color:#fff; }
    .meta { color:#e5e7eb; margin-top:6px; font-weight:600; }

    .box {
      background:#fff;
      border:1px solid #e5e7eb;
      padding:12px;
      border-radius:8px;
      margin:10px 0;
      color: var(--text);
    }
    .box strong { color: var(--text); }

    @media (max-width: 600px) {
      .box { padding: 14px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <a class="back-link" href="/admin/reports.php">&larr; Back</a>

    <div class="report-header">
      <h2>Brody's Daily Progress Report</h2>
      <div class="meta">
      <?= !empty($r['report_date']) 
        ? date('l, jS F', strtotime($r['report_date'])) 
        : '' ?>
      </div>
    </div>

    <div class="box"><strong>Communication:</strong><br><?=$S('communication')?></div>
    <div class="box"><strong>Social:</strong><br><?=$S('social')?></div>
    <div class="box"><strong>Academic:</strong><br><?=$S('academic')?></div>
    <div class="box"><strong>Adaptive:</strong><br><?=$S('adaptive')?></div>
    <div class="box"><strong>Specialists:</strong> <?=h($J('specialists'))?></div>
    <div class="box"><strong>Food/Drink:</strong><br><?=$S('food_drink')?></div>
    <div class="box"><strong>Bathroom:</strong> <?=h($J('bathroom'))?></div>
    <div class="box"><strong>Please Send In:</strong><br><?=$S('send_in')?></div>
    <div class="box"><strong>Notes:</strong><br><?=$S('notes')?></div>
  </div>
</body>
</html>
