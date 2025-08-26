<?php
require __DIR__.'/../config.php'; require __DIR__.'/../helpers.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  csrf_check();
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';
  $stmt = db()->prepare("SELECT id, pass_hash FROM admins WHERE email = :e");
  $stmt->execute([':e'=>$email]);
  $row = $stmt->fetch();
  if ($row && password_verify($pass, $row['pass_hash'])) {
    $_SESSION['admin_id'] = (int)$row['id'];
    header('Location: /admin/reports.php'); exit;
  }
  $err = 'Invalid credentials';
}
?>
<!doctype html><html><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login</title>
<style>body{font-family:system-ui;max-width:420px;margin:60px auto;padding:0 16px}
label{display:block;margin-top:12px}input{width:100%;padding:8px}button{margin-top:14px;padding:10px 16px;background:#2563eb;border:0;color:#fff;border-radius:6px}
.err{color:#b91c1c;margin-top:10px}</style></head><body>
<h2>Admin Login</h2>
<?php if(!empty($err)):?><div class="err"><?=$err?></div><?php endif;?>
<form method="post">
  <input type="hidden" name="csrf" value="<?=csrf_token()?>">
  <label>Email<input name="email" type="email" required></label>
  <label>Password<input name="password" type="password" required></label>
  <button>Sign In</button>
</form>
</body></html>
