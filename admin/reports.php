<?php
require __DIR__.'/../config.php';
require __DIR__.'/../helpers.php';
require_login();

$q     = trim($_GET['q'] ?? '');
$page  = max(1, (int)($_GET['p'] ?? 1));
$per   = 20;
$off   = ($page - 1) * $per;

$where  = '';
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

$sql = "SELECT * FROM reports $where 
        ORDER BY report_date DESC, id DESC 
        LIMIT $per OFFSET $off";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$rows  = $stmt->fetchAll();
$pages = max(1, (int)ceil($count / $per));
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reports – Admin</title>
<style>
  /* Page background */
  body {
    margin: 0;
    padding: 0;
    font-family: system-ui, Arial, sans-serif;
    background-image: url('../assets/images/Educational_Icons_Pattern_1.png');
    background-repeat: repeat;
    background-size: 300px auto;
    background-color: #ffffff;
  }

  /* Centered white container */
  .container {
    max-width: 1100px;
    margin: 24px auto;
    padding: 16px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
  }

  .topbar { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    gap: 12px; 
  }
  .controls { display: flex; gap: 8px; align-items: center; }
  input[type=search] { padding: 8px; width: 260px; }

  a.btn, button.btn {
    background: #111827; color: #fff; 
    padding: 8px 12px; border-radius: 6px; 
    text-decoration: none; border: 0;
  }

  table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  th, td { border-bottom: 1px solid #eee; padding: 8px; text-align: left; vertical-align: top; }
  th { font-weight: 700; }

  /* Specialists pills */
  .pill {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 999px;
    background: #eef2ff;
    border: 1px solid #e5e7eb;
    font-size: 12px;
    margin-right: 6px;
    white-space: nowrap;
  }

  /* Dot indicators for filled text fields */
  .indicator-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e; /* green */
    vertical-align: middle;
  }

  .pager { margin-top: 12px; }
  .pager a { margin: 0 4px; }
</style>
</head>
<body>
  <div class="container">
    <div class="topbar">
      <h2>Reports (<?= $count ?>)</h2>
      <div class="controls">
        <form method="get">
          <input type="search" name="q" placeholder="Search notes, communication, etc." value="<?= h($q) ?>">
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
          <th>View</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= date('d-m-y', strtotime($r['report_date'])) ?></td>

          <td><?= trim((string)$r['communication']) !== '' ? '<span class="indicator-dot" title="Has text"></span>' : '' ?></td>
          <td><?= trim((string)$r['social'])        !== '' ? '<span class="indicator-dot" title="Has text"></span>' : '' ?></td>
          <td><?= trim((string)$r['academic'])      !== '' ? '<span class="indicator-dot" title="Has text"></span>' : '' ?></td>
          <td><?= trim((string)$r['adaptive'])      !== '' ? '<span class="indicator-dot" title="Has text"></span>' : '' ?></td>

          <td>
            <?php if (!empty($r['specialists'])):
              $spec = json_decode($r['specialists'], true) ?: [];
              foreach ($spec as $s) echo '<span class="pill">'.h($s).'</span> ';
            endif; ?>
          </td>

          <td>
            <?php if (!empty($r['bathroom'])):
              $bm = json_decode($r['bathroom'], true) ?: [];
              echo (int)($bm['changed'] ?? 0).' / ';
              echo (int)($bm['wet'] ?? 0).' / ';
              echo (int)($bm['bm'] ?? 0).' / ';
              echo (int)($bm['sat_on_toilet'] ?? 0).' / ';
              echo (int)($bm['went_on_toilet'] ?? 0);
            endif; ?>
          </td>

          <td><?= trim((string)$r['notes'])   !== '' ? '<span class="indicator-dot" title="Has text"></span>' : '' ?></td>
          <td><?= trim((string)$r['send_in']) !== '' ? '<span class="indicator-dot" title="Has text"></span>' : '' ?></td>

          <td><a href="view.php?id=<?= $r['id'] ?>">View</a></td>
        </tr>
      <?php endforeach; ?>

      <?php if (!$rows): ?>
        <tr><td colspan="10">No reports.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
    <div class="pager">
      Page:
      <?php for ($i=1; $i <= $pages; $i++): ?>
        <?php if ($i === $page): ?>
          <strong><?= $i ?></strong>
        <?php else: ?>
          <a href="?p=<?= $i ?><?= $q!=='' ? '&q='.urlencode($q) : '' ?>"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>
    </div>
  </div>
</body>
</html>
