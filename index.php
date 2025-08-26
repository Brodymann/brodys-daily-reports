<?php 
require __DIR__.'/config.php'; 
require __DIR__.'/helpers.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?=h(APP_NAME)?> – Submit Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    /* Bigger bathroom number inputs */
    .bathroom-field {
      width: 70px;
      font-size: 1.2rem;
      padding: 8px;
      text-align: center;
    }
    .metric {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }
    .group.compact {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>
  <div class="container">
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
          <div class="readonly"><strong>Brody Baumann</strong></div>
        </div>
        <label>Date:
          <strong><input type="date" name="report_date" required></strong>
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

      <label>Bathroom (0–10):</label>
      <div class="group compact">
        <?php foreach (['Changed','Wet','BM','Sat on Toilet','Went on Toilet'] as $b): 
          $key = strtolower(str_replace(' ', '_', $b)); ?>
          <label class="metric">
            <span><?=$b?></span>
            <input
              type="number"
              class="bathroom-field"
              name="bathroom[<?=$key?>]"
              min="0"
              max="10"
              step="1"
              inputmode="numeric"
              value="0"
              required
            >
          </label>
        <?php endforeach; ?>
      </div>

      <label>Please Send In:<textarea name="send_in" rows="2"></textarea></label>
      <label>Notes:<textarea name="notes" rows="6"></textarea></label>

      <button type="submit">Submit Report</button>
    </form>
  </div>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Auto-fill date with today
    const dateInput = document.querySelector('input[name="report_date"]');
    if (dateInput && !dateInput.value) {
      dateInput.value = new Date().toISOString().split('T')[0];
    }

    // Custom validation message for bathroom fields
    const bathroomFields = document.querySelectorAll(".bathroom-field");
    bathroomFields.forEach(field => {
      field.addEventListener("invalid", function() {
        this.setCustomValidity("Please enter a value for some bathroom fields");
      });
      field.addEventListener("input", function() {
        this.setCustomValidity(""); // clear message
      });
    });
  });
  </script>
</body>
</html>
