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
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Montserrat:wght@700&display=swap" rel="stylesheet">
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
    width: 160px;
    text-align: center;
  }

  /* Brody photo: responsive perfect circle with shadow */
  .brody-photo {
    display: block;
    margin: 0 auto 20px auto;
    width: clamp(120px, 35vw, 220px);
    aspect-ratio: 1 / 1;
    object-fit: cover;
    border-radius: 50%;
    box-shadow:
      0 4px 6px rgba(0,0,0,0.5),
      0 12px 20px rgba(0,0,0,0.4);
  }
</style>
</head>
<body>
  <div class="container">

    <!-- Title inside styled white box -->
    <div class="title-box">
      <h1><?=h(APP_NAME)?></h1>
    </div>

    <!-- Photo of Brody -->
    <img
      src="assets/images/brody_grade_7.jpg"
      alt="Brodys 7th grade portrait"
      class="brody-photo"
      loading="eager"
      decoding="async"
    >

    <?php if (!empty($_GET['ok'])): ?>
      <div class="success">Report submitted. Thank you!</div>
    <?php endif; ?>

    <form method="post" action="/save_report.php">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <input type="hidden" name="student_name" value="Brody Baumann">

      <!-- Date centered -->
      <div class="date-row">
        <label>
          <strong>
            <input 
              type="date" 
              name="report_date" 
              required 
              value="<?= date('Y-m-d') ?>"  <!-- server-side fallback -->
            >
          </strong>
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
        <?php foreach (['Diaper Changes','Wet','BM','Sat on Toilet','Went on Toilet'] as $b): 
          $key = strtolower(str_replace(' ', '_', $b)); ?>
          <label class="metric">
            <span><?=$b?></span>
            <input
              type="number"
              class="bathroom-field"
              name="bathroom[<?=$key?>]"
              min="0"
              max="9"
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
  // Auto-fill date with local timezone
  document.addEventListener("DOMContentLoaded", () => {
  const dateInput = document.querySelector('input[name="report_date"]');
  if (dateInput) {
    // set to local date, time zeroed to avoid DST quirks
    const now = new Date();
    dateInput.valueAsDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  }

    // Applause on submit
    const form = document.querySelector("form");
    const applause = document.getElementById("applause-sound");
    if (form && applause) {
      form.addEventListener("submit", function(e) {
        e.preventDefault();
        applause.currentTime = 0;
        applause.play();
        setTimeout(() => form.submit(), 6000);
      });
    }
  });
  </script>
  <audio id="applause-sound" src="assets/sounds/applause.mp3" preload="auto"></audio>
</body>
</html>
