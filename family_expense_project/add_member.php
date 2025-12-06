<?php
require_once 'functions.php'; require_login();
$admin_id = $_SESSION['admin_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $full = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $alloc = floatval($_POST['allocated_budget']);
    $stmt = $pdo->prepare('INSERT INTO member (admin_id, username, full_name, email, allocated_budget) VALUES (?,?,?,?,?)');
    $stmt->execute([$admin_id,$username,$full,$email,$alloc]);
    header('Location: admin_dashboard.php'); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Add Member</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link href="public/styles.css" rel="stylesheet"></head><body>
<div class="container py-4"><div class="card p-3"><h4>Add Member</h4>
<form method="post">
  <div class="mb-3"><label>Username</label><input name="username" class="form-control" required></div>
  <div class="mb-3"><label>Full Name</label><input name="full_name" class="form-control"></div>
  <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control"></div>
  <div class="mb-3"><label>Allocated Budget</label><input name="allocated_budget" type="number" step="0.01" class="form-control" value="0"></div>
  <button class="btn btn-accent">Add</button>
  <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
</form>
</div></div></body></html>
