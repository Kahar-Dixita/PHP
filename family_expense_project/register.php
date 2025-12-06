<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $family = trim($_POST['family_name']);
    $username = trim($_POST['username']);
    $full = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (!$family || !$username || !$password) {
        $error = 'Family name, username and password are required.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO admin (family_name, username, full_name, email, password_hash) VALUES (?,?,?,?,?)');
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([$family,$username,$full,$email,$hash]);
        header('Location: login.php'); exit;
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Register Family Head</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg"><div class="container-fluid"><a class="navbar-brand" href="#">Family Expense</a></div></nav>
<div class="container py-4">
  <div class="row justify-content-center"><div class="col-md-6"><div class="card">
    <h4>Register Family Head (Admin)</h4>
    <?php if(!empty($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
      <div class="mb-3"><label class="form-label">Family Name</label><input name="family_name" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Username</label><input name="username" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Full Name</label><input name="full_name" class="form-control"></div>
      <div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" class="form-control"></div>
      <div class="mb-3"><label class="form-label">Password</label><input name="password" type="password" class="form-control" required></div>
      <button class="btn btn-accent">Register</button>
    </form>
  </div></div></div>
</div>
</body></html>
