<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $stmt = $pdo->prepare('SELECT id, password_hash, family_name FROM admin WHERE username=? LIMIT 1');
    $stmt->execute([$user]);
    $r = $stmt->fetch();
    if ($r && password_verify($pass, $r['password_hash'])){
        $_SESSION['admin_id'] = $r['id'];
        $_SESSION['family_name'] = $r['family_name'];
        header('Location: admin_dashboard.php'); exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/styles.css" rel="stylesheet">
</head>
<body>
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-5"><div class="card p-4">
  <h4>Admin Login</h4>
  <?php if(!empty($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <div class="mb-3"><input name="username" class="form-control" placeholder="username"></div>
    <div class="mb-3"><input name="password" type="password" class="form-control" placeholder="password"></div>
    <button class="btn btn-accent">Login</button>
  </form>
  <hr>
  <a href="register.php">Create new family</a>
</div></div></div></div>
</body></html>
