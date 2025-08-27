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

<!-- Global styles: provides background pattern and BLUE container -->
<link rel="stylesheet" href="/assets/css/style.css">

<style>

/* Page background */
body {
  margin: 0;
  padding: 16px;
  font-family: system-ui, Arial, sans-serif;
  background-image: url('../assets/images/Educational_Icons_Pattern_1.png');
  background-repeat: repeat;
  background-size: 300px auto;
}

/* Blue container */
.container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 16px;
  background-color: var(--container-blue);
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Keep table white inside container */
.table-wrap table {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

table{width:100%;border-collapse:collapse;margin-top:12px}
th,td{border-bottom:1px solid #eee;padding:8px;text-align:left;vertical-align:top}
.controls{display:flex;gap:8px;align-items:center;margin-top:12px}
input[type=search]{padding:8px;width:260px}
a.btn,button.btn{background:#111827;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none;border:0}
.badge{display:inline-block;padding:4px 8px;border:1px solid #ddd;border-radius:12px;margin-right:4px;font-size:12px;background:#f9fafb}
.pager a{margin:0 4px}
.topbar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap}

/* Mobile-friendly table */
.table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.table-wrap table {
  min-width: 880px; /* prevents columns from squishing too much */
}
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
      <thead><tr>
        <th>Date</th><th>Communication</th><th>Social</th><th>Academic</th><th>Adaptive</th><th>Specialists</th><th>Bathroom</th><th>Notes</th><th>Send</th>
      </tr></thead>
      <tbody>
        <?php foreach($rows as $r): ?>
          <tr>
            <td><?=h($r['report_date'])?></td>
            <td><?=h($r['communication'])?></td>
            <td><?=h($r['social'])?></td>
            <td><?=h($r['academic'])?></td>
            <td><?=h($r['adaptive'])?></td>
            <td><?php foreach(json_decode($r['specialists'] ?? '[]', true) ?: [] as $s) echo '<span class="badge">'.h($s).'</span>'; ?></td>
            <td><?php foreach(json_decode($r['bathroom'] ?? '[]', true) ?: [] as $b) echo '<span class="badge">'.h($b).'</span>'; ?></td>
            <td><?=h(mb_strimwidth($r['notes'] ?? '', 0, 80, '…'))?></td>
            <td><?=h($r['send_in'])?></td>
            <td><a href="/admin/view.php?id=<?=$r['id']?>">View</a></td>
          </tr>
        <?php endforeach; ?>
        <?php if(!$rows): ?><tr><td colspan="10">No reports.</td></tr><?php endif;?>
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
