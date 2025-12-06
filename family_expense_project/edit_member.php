<?php
require_once 'functions.php'; require_login();
$id = intval($_GET['id'] ?? 0); $admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare('SELECT * FROM member WHERE id=? AND admin_id=?'); $stmt->execute([$id,$admin_id]); $m = $stmt->fetch();
if (!$m) { header('Location: admin_dashboard.php'); exit; }
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $full = trim($_POST['full_name']); $email = trim($_POST['email']); $alloc = floatval($_POST['allocated_budget']);
    $u = $pdo->prepare('UPDATE member SET full_name=?, email=?, allocated_budget=? WHERE id=? AND admin_id=?');
    $u->execute([$full,$email,$alloc,$id,$admin_id]);
    header('Location: admin_dashboard.php'); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Member</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link href="public/styles.css" rel="stylesheet"></head><body>
<div class="container py-4"><div class="card p-3"><h4>Edit Member</h4>
<form method="post">
  <div class="mb-3"><label>Full Name</label><input name="full_name" class="form-control" value="<?=htmlspecialchars($m['full_name'])?>"></div>
  <div class="mb-3"><label>Email</label><input name="email" type="email" class="form-control" value="<?=htmlspecialchars($m['email'])?>"></div>
  <div class="mb-3"><label>Allocated Budget</label><input name="allocated_budget" type="number" step="0.01" class="form-control" value="<?=number_format($m['allocated_budget'],2)?>"></div>
  <button class="btn btn-accent">Save</button>
  <a href="admin_dashboard.php" class="btn btn-secondary">Cancel</a>
</form>
</div></div></body></html>
