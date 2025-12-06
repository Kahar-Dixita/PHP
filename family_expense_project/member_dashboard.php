<?php
require_once 'config.php';
// simple member login by username for demo
if (isset($_POST['login_member'])){
    $username = trim($_POST['username']);
    $stmt = $pdo->prepare('SELECT * FROM member WHERE username=? LIMIT 1'); $stmt->execute([$username]); $m = $stmt->fetch();
    if ($m) { $_SESSION['member_id']=$m['id']; $_SESSION['admin_id']=$m['admin_id']; header('Location: member_dashboard.php'); exit; }
    $error='Member not found';
}
if (!isset($_SESSION['member_id'])){
    ?>
    <!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Member Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link href="public/styles.css" rel="stylesheet"></head><body>
    <div class="container py-5"><div class="row justify-content-center"><div class="col-md-5"><div class="card p-3"><h4>Member Login</h4>
    <?php if(!empty($error)) echo '<div class="alert alert-danger">'.htmlspecialchars($error).'</div>'; ?>
    <form method="post"><input name="username" class="form-control mb-2" placeholder="username"><button name="login_member" class="btn btn-accent">Enter</button></form>
    </div></div></div></div></body></html>
    <?php
    exit;
}
$member_id = $_SESSION['member_id'];
$m = $pdo->prepare('SELECT * FROM member WHERE id=?'); $m->execute([$member_id]); $member = $m->fetch();
$admin_id = $member['admin_id'];
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Member Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"><link href="public/styles.css" rel="stylesheet"></head><body>
<div class="container py-4">
  <div class="card p-3"><h4>Welcome, <?=htmlspecialchars($member['full_name'] ?: $member['username'])?></h4>
    <p>Allocated: <?=number_format($member['allocated_budget'],2)?></p>
    <p>Spent: <?=number_format($member['spent_amount'],2)?></p>
    <p>Remaining: <?=number_format($member['allocated_budget'] - $member['spent_amount'],2)?></p>
    <?php if(($member['allocated_budget'] - $member['spent_amount']) <= 0): ?>
      <div class="alert alert-danger">Your budget is zero. Do not spend further.</div>
    <?php endif; ?>
    <hr>
    <h5>Record Expense</h5>
    <form method="post" action="spend.php">
      <input type="hidden" name="member_id" value="<?=$member['id']?>">
      <div class="mb-2"><input name="amount" type="number" step="0.01" class="form-control" placeholder="amount" required></div>
      <div class="mb-2"><input name="description" class="form-control" placeholder="description"></div>
      <button class="btn btn-accent">Record</button>

      <!-- <div class="d-flex justify-content-between align-items-center mb-3"> -->

     <a href="logoutuser.php" class="btn btn-danger">Logout</a>

</div>
    </form>
  </div>
</div>
</body></html>

<hr>
<h5>Your Expenses</h5>
<?php
$stmt = $pdo->prepare("SELECT * FROM expense WHERE member_id=? ORDER BY transaction_date DESC");
$stmt->execute([$member['id']]);
$expenses = $stmt->fetchAll();
?>

<?php if ($expenses): ?>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Date</th>
        <th>Amount</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($expenses as $exp): ?>
        <tr>
          <td><?=htmlspecialchars($exp['transaction_date'])?></td>
          <td><?=number_format($exp['amount'],2)?></td>
          <td><?=htmlspecialchars($exp['description'])?></td>
          <td>
            <a href="edit_expense.php?id=<?=$exp['id']?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete_expense.php?id=<?=$exp['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this expense?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No expenses recorded yet.</p>
<?php endif; ?>

