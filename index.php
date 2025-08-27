<?php require __DIR__.'/config.php'; require __DIR__.'/helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?=h(APP_NAME)?> – Submit Report</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="assets/css/style.css?v=<?=time()?>" />
  <style>
    /* Centered date field */
    .date-row {
      display: flex;
      justify-content: center;
      margin: 20px 0;
    }
    .date-row label {
      font-weight: 600;
      font-size: 1.2rem;
    }
    .date-row input[type="date"] {
      font-size: 1.2rem;
      padding: 6px 10px;
      width: 160px; /* shorter box */
      text-align: center;
    }
    /* Prevent margin-collapsing inside colored containers */
    .container { overflow: hidden; }

    /* Brody + Kyle photo */
    .brody-photo {
      display: block;
      margin: 0 auto 20px auto;
      max-width: 220px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.25);
    }
  </style>
</head>
<body>
  <div class="container">
    <h1><?=h(APP_NAME)?></h1>

    <!-- Photo of Brody + Kyle -->
    <img src="assets/images/Brody_Kyle.jpg" alt="Brody and Kyle" class="brody-photo">

    <?php if (!empty($_GET['ok'])): ?>
      <div class="success">Report submitted. Thank you!</div>
    <?php endif; ?>

    <form method="post" action="/save_report.php">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <input type="hidden" name="student_name" value="Brody Baumann">

      <!-- Date centered -->
      <div class="date-row">
        <label>
          <strong><input type="date" name="report_date" required></strong>
        </label>
      </div>

      <label>Communication:<textarea name="communication" rows="3"></textarea></label>
      <label>Social:<textarea name="social" rows="3"></textarea></label>
      <label>Academic:<textarea name="academic" rows="3"></textarea></label>
      <label>Adaptive:<textarea name="adaptive" rows="3"></textarea></label>

      <label>Specialists:</label>
      <div class="group">
        <?php foreach (['APE','O/T','P/T','Speech'] as $s): ?>
          <label><input type="checkbox" name="specialists[]" value="<?=$s?>"> <?=$s?></label>
        <?php endforeach; ?>
      </div>

      <label>Food/Drink:<textarea name="food_drink" rows="3"></textarea></label>

      <label>Bathroom:</label>
      <div class="group compact">
        <?php foreach (['Diaper Changed','Wet','BM','Sat on Toilet','Went on Toilet'] as $b): 
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
    // Auto-fill date
    const dateInput = document.querySelector('input[name="report_date"]');
    if (dateInput && !dateInput.value) {
      dateInput.value = new Date().toISOString().split('T')[0];
    }

    // Applause on submit
    const form = document.querySelector("form");
    const applause = document.getElementById("applause-sound");
    if (form && applause) {
      form.addEventListener("submit", function(e) {
        e.preventDefault();   // stop instant reload
        applause.currentTime = 0;
        applause.play();

        // let the applause play for ~2 seconds, then submit
        setTimeout(() => form.submit(), 6000);
      });
    }
  });
  </script>
  <audio id="applause-sound" src="assets/sounds/applause.mp3" preload="auto"></audio>
</body>
</html>
