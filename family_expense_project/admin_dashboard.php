<?php
require_once 'functions.php';
require_login();
$admin_id = $_SESSION['admin_id'];
// fetch members
$members = $pdo->prepare('SELECT * FROM member WHERE admin_id=?');
$members->execute([$admin_id]);
$members = $members->fetchAll();
// compute overall available budget
$total_allocated = $pdo->prepare('SELECT COALESCE(SUM(allocated_budget),0) as s FROM member WHERE admin_id=?');
$total_allocated->execute([$admin_id]); $talloc = $total_allocated->fetchColumn();
$total_spent = $pdo->prepare('SELECT COALESCE(SUM(spent_amount),0) as s FROM member WHERE admin_id=?');
$total_spent->execute([$admin_id]); $tspent = $total_spent->fetchColumn();
$available = $talloc - $tspent;
$all_zero = ($talloc <= 0.001);

// handle allocation save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['split'])){
    $alloc = $_POST['alloc'] ?? [];
    $pdo->beginTransaction();
    foreach ($alloc as $mid => $amt) {
        $stmt = $pdo->prepare('UPDATE member SET allocated_budget = ? WHERE id = ? AND admin_id=?');
        $stmt->execute([floatval($amt), intval($mid), $admin_id]);
    }
    $pdo->commit();
    header('Location: admin_dashboard.php'); exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="public/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg"><div class="container-fluid"><a class="navbar-brand" href="#">
    <?= htmlspecialchars($_SESSION['family_name'] ?? 'Family Expense Dashboard') ?>
</a>
<div class="ms-auto"><a class="btn btn-outline-dark" href="logout.php">Logout</a></div></div></nav>
<div class="container py-4">
  <?php if($all_zero): ?><div class="alert alert-warning">All budgets are zero. Please allocate budgets to members before spending.</div><?php endif; ?>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <h5>Members & Allocations</h5>
        <a href="add_member.php" class="btn btn-sm btn-primary mb-2">Add Member</a>
        <form method="post">
        <table class="table table-sm">
          <thead><tr><th>Member</th><th>Allocated</th><th>Spent</th><th>Remaining</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach($members as $m):
              $rem = $m['allocated_budget'] - $m['spent_amount'];
            ?>
            <tr>
              <td><?=htmlspecialchars($m['full_name'] ?: $m['username'])?></td>
              <td><input name="alloc[<?=$m['id']?>]" type="number" step="0.01" value="<?=$m['allocated_budget']?>" class="form-control form-control-sm" style="width:110px"></td>
              <td><?=number_format($m['spent_amount'],2)?></td>
              <td><?=number_format($rem,2)?></td>
              <td>
                <a href="edit_member.php?id=<?=$m['id']?>" class="btn btn-sm btn-secondary">Edit</a>
                <a href="delete_member.php?id=<?=$m['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete member?')">Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <button name="split" class="btn btn-accent">Save Allocations</button>
        </form>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <h5>Summary</h5>
        <p>Total Allocated: <?=number_format($talloc,2)?></p>
        <p>Total Spent: <?=number_format($tspent,2)?></p>
        <p>Available: <?=number_format($available,2)?></p>
      </div>
      <div class="card mt-3">
        <h6>Recent Expenses</h6>
        <table class="table table-sm">
          <thead><tr><th>Member</th><th>Amount</th><th>Date</th></tr></thead>
          <tbody>
            <?php
        $r = $pdo->prepare('SELECT e.*, m.full_name 
        FROM expense e 
        LEFT JOIN member m ON e.member_id=m.id 
        WHERE e.admin_id=? 
        ORDER BY e.created_at DESC LIMIT 8');


            $r->execute([$admin_id]);
            foreach($r->fetchAll() as $row){
                echo '<tr><td>'.htmlspecialchars($row['full_name'] ?? '—').'</td><td>'.number_format($row['amount'],2).'</td><td>'.htmlspecialchars($row['transaction_date']).'</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</body></html>
