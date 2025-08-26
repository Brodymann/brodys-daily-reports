<?php require __DIR__.'/config.php'; require __DIR__.'/helpers.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?=h(APP_NAME)?> – Submit Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body{font-family:system-ui,Arial,sans-serif;max-width:860px;margin:24px auto;padding:0 16px}
  label{font-weight:600;margin-top:14px;display:block}
  textarea,input[type=text]{width:100%;padding:8px}
  .group{display:flex;flex-wrap:wrap;gap:16px;margin-top:8px}
  .group label{font-weight:400}
  button{margin-top:18px;padding:10px 18px;border:0;border-radius:6px;background:#2563eb;color:#fff;cursor:pointer}
  .two{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .success{background:#ecfdf5;border:1px solid #10b981;color:#065f46;padding:10px;border-radius:6px;margin:10px 0}
  .readonly{background:#f9fafb;border:1px dashed #d1d5db;padding:8px;border-radius:6px}
</style>
</head>
<body>
  <h1><?=h(APP_NAME)?></h1>
  <p>Fill out the daily progress report below. Student is preset to <strong>Brody Baumann</strong>.</p>

  <?php if (!empty($_GET['ok'])): ?>
    <div class="success">Report submitted. Thank you!</div>
  <?php endif; ?>

  <form method="post" action="/save_report.php">
    <input type="hidden" name="csrf" value="<?=csrf_token()?>">
    <input type="hidden" name="student_name" value="Brody Baumann">

    <div class="two">
      <div>
        <label>Daily Progress Report for:</label>
        <div class="readonly">Brody Baumann</div>
      </div>
      <label>Date:
        <input type="date" name="report_date" required>
      </label>
    </div>

    <label>Communication:<textarea name="communication" rows="3"></textarea></label>
    <label>Social:<textarea name="social" rows="3"></textarea></label>
    <label>Academic:<textarea name="academic" rows="3"></textarea></label>
    <label>Adaptive:<textarea name="adaptive" rows="3"></textarea></label>

    <label>Specialists:</label>
    <div class="group">
      <?php foreach (['APE','OT','PT','Speech'] as $s): ?>
        <label><input type="checkbox" name="specialists[]" value="<?=$s?>"> <?=$s?></label>
      <?php endforeach; ?>
    </div>

    <label>Food/Drink:<textarea name="food_drink" rows="3"></textarea></label>

    <label>Bathroom:</label>
    <div class="group">
      <?php foreach (['Changed','Wet','BM','Sat on Toilet','Went on Toilet'] as $b): ?>
        <label><input type="checkbox" name="bathroom[]" value="<?=$b?>"> <?=$b?></label>
      <?php endforeach; ?>
    </div>

    <label>Please Send In:<textarea name="send_in" rows="2"></textarea></label>
    <label>Notes:<textarea name="notes" rows="6"></textarea></label>

    <button type="submit">Submit Report</button>
  </form>
</body>
</html>
