<?php
require_once 'config.php';
// session_start();

if (!isset($_SESSION['member_id'])) {
    header("Location: member_dashboard.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid expense ID");

$stmt = $pdo->prepare("SELECT * FROM expense WHERE id=? AND member_id=?");
$stmt->execute([$id, $_SESSION['member_id']]);
$expense = $stmt->fetch();

if (!$expense) die("Expense not found");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $description = trim($_POST['description']);

    // adjust spent_amount
    $diff = $amount - $expense['amount'];
    $updateMember = $pdo->prepare("UPDATE member SET spent_amount = spent_amount + ? WHERE id=?");
    $updateMember->execute([$diff, $_SESSION['member_id']]);

    // update expense
    $update = $pdo->prepare("UPDATE expense SET amount=?, description=? WHERE id=? AND member_id=?");
    $update->execute([$amount, $description, $id, $_SESSION['member_id']]);

    header("Location: member_dashboard.php");
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Expense</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <h3>Edit Expense</h3>
  <form method="post">
    <div class="mb-3">
      <label>Amount</label>
      <input type="number" step="0.01" name="amount" class="form-control" value="<?=htmlspecialchars($expense['amount'])?>" required>
    </div>
    <div class="mb-3">
      <label>Description</label>
      <input type="text" name="description" class="form-control" value="<?=htmlspecialchars($expense['description'])?>">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="member_dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</body>
</html>
