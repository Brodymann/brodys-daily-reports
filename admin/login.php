<?php
require __DIR__.'/../config.php'; 
require __DIR__.'/../helpers.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check();
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $stmt = db()->prepare("SELECT id, pass_hash FROM admins WHERE email = :e");
  $stmt->execute([':e'=>$email]);
  $row = $stmt->fetch();
  if ($row && password_verify($pass, $row['pass_hash'])) {
    $_SESSION['admin_id'] = (int)$row['id'];
    header('Location: /admin/reports.php'); 
    exit;
  }
  $err = 'Invalid credentials';
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title>
<link rel="stylesheet" href="../assets/css/style.css">
<style>
  body {
    margin: 0;
    font-family: system-ui, Arial, sans-serif;
    background-image: url('../assets/images/Educational_Icons_Pattern_1.png');
    background-repeat: repeat;
    background-size: 300px auto;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .login-box {
    max-width: 400px;
    width: 100%;
    background: #fff;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }
  h2 { margin-top: 0; text-align: center; }
  label { display: block; margin-top: 12px; font-weight: 600; }
  input {
    width: 100%; padding: 10px; margin-top: 4px;
    border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;
  }
  button {
    margin-top: 16px; width: 100%; padding: 12px;
    background: #2563eb; border: none; border-radius: 6px; color: #fff;
    font-weight: 600; cursor: pointer;
  }
  button:hover { filter: brightness(0.95); }
  .err { color: #b91c1c; margin-top: 10px; text-align: center; }
</style>
</head>
<body>
  <div class="login-box">
    <h2>Admin Login</h2>
    <?php if(!empty($err)):?>
      <div class="err"><?=$err?></div>
    <?php endif;?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?=csrf_token()?>">
      <label>Email
        <input name="email" type="email" required>
      </label>
      <label>Password
        <input name="password" type="password" required>
      </label>
      <button>Sign In</button>
    </form>
  </div>
</body>
</html>
