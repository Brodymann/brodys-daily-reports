<?php
require __DIR__.'/../config.php'; 
require __DIR__.'/../helpers.php'; 
require_login();

$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['p'] ?? 1));
$per  = 20;
$off  = ($page-1)*$per;

$where = '';
$params = [];
if ($q !== '') {
  $where = "WHERE student_name LIKE :q 
            OR notes LIKE :q 
            OR communication LIKE :q 
            OR social LIKE :q 
            OR academic LIKE :q 
            OR adaptive LIKE :q 
            OR food_drink LIKE :q 
            OR send_in LIKE :q";
  $params[':q'] = "%$q%";
}

$total = db()->prepare("SELECT COUNT(*) c FROM reports $where");
$total->execute($params);
$count = (int)$total->fetch()['c'];

$sql = "SELECT * FROM reports $where ORDER BY report_date DESC, id DESC LIMIT $per OFFSET $off";
$stmt = db()->prepare($sql); 
$stmt->execute($params);
$rows = $stmt->fetchAll();
$pages = max(1, (int)ceil($count/$per));
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reports – Admin</title>

<!-- Global stylesheet (pattern background + blue .container) -->
<link rel="stylesheet" href="/assets/css/style.css">

<style>
/* Page-specific tweaks */
body { margin: 0; padding: 16px; font-family: system-ui, Arial, sans-serif; }

/* Blue container (outer wrapper) */
.container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 16px;
  background-color: var(--container-blue);
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Keep header text white on blue */
.topbar h2 { color: #fff; }

/* Inputs readable on blue */
.controls input[type=search] {
  background: #fff;
  color: var(--text);
}

/* Table: readable on white */
.table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.table-wrap table {
  min-width: 880px;
  width: 100%;
  border-collapse: collapse;
  margin-top: 12px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
th, td { border-bottom: 1px solid #eee; padding: 8px; text-align: left; vertical-align: top; color: var(--text); }

/* Buttons in header keep white labels */
a.btn, button.btn { background:#111827; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; border:0; }

/* Pills */
.badge { display:inline-block; padding:4px 8px; border:1px solid #ddd; border-radius:12px; margin-right:4px; font-size:12px; background:#f9fafb; color: var(--text); }

/* Pager links readable */
.pager, .pager a { color: var(--text); }

/* The blue dot indicator */
.dot {
  display:inline-block;
  width:10px;
  height:10px;
  border-radius:50%;
  background-color:#37578f;
}

/* Layout controls row */
.topbar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap}
.controls{display:flex;gap:8px;align-items:center;margin-top:12px}
input[type=search]{padding:8px;width:260px}
.pager a{margin:0 4px}
</style>
</head>
<body>
<div class="container">
  <div class="topbar">
    <h2>Reports (<?=$count?>)</h2>
    <div class="controls">
      <form method="get">
        <input type="search" name="q" placeholder="Search notes, student, etc." value="<?=h($q)?>">
      </form>
      <a class="btn" href="/admin/export_csv.php<?= $q!=='' ? '?q='.urlencode($q):'' ?>">Export CSV</a>
      <a class="btn" href="/admin/logout.php">Logout</a>
    </div>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Communication</th>
          <th>Social</th>
          <th>Academic</th>
          <th>Adaptive</th>
          <th>Specialists</th>
          <th>Bathroom</th>
          <th>Notes</th>
          <th>Send</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $r): ?>
          <tr>
            <td><?=h($r['report_date'])?></td>

            <!-- show blue dot if field has any text -->
            <td><?=!empty($r['communication']) ? '<span class="dot"></span>' : ''?></td>
            <td><?=!empty($r['social']) ? '<span class="dot"></span>' : ''?></td>
            <td><?=!empty($r['academic']) ? '<span class="dot"></span>' : ''?></td>
            <td><?=!empty($r['adaptive']) ? '<span class="dot"></span>' : ''?></td>

            <!-- Specialists (keep pills) -->
            <td>
              <?php foreach(json_decode($r['specialists'] ?? '[]', true) ?: [] as $s) echo '<span class="badge">'.h($s).'</span>'; ?>
            </td>

            <!-- Bathroom (keep numbers/pills as-is) -->
            <td>
              <?php foreach(json_decode($r['bathroom'] ?? '[]', true) ?: [] as $b) echo '<span class="badge">'.h($b).'</span>'; ?>
            </td>

            <!-- Notes + Send: blue dot if non-empty -->
            <td><?=!empty($r['notes']) ? '<span class="dot"></span>' : ''?></td>
            <td><?=!empty($r['send_in']) ? '<span class="dot"></span>' : ''?></td>

            <td><a href="/admin/view.php?id=<?=h($r['id'])?>">View</a></td>
          </tr>
        <?php endforeach; ?>
        <?php if(!$rows): ?>
          <tr><td colspan="10">No reports.</td></tr>
        <?php endif;?>
      </tbody>
    </table>
  </div>

  <div class="pager">
    Page:
    <?php for($i=1;$i<=$pages;$i++): ?>
      <?php if ($i===$page): ?> <strong><?=$i?></strong>
      <?php else: ?>
        <a href="?p=<?=$i?><?= $q!==''?'&q='.urlencode($q):'' ?>"><?=$i?></a>
      <?php endif; ?>
    <?php endfor; ?>
  </div>
</div>
</body>
</html>
