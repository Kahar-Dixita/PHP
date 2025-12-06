<?php
require_once 'config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: member_dashboard.php'); exit; }
$member_id = intval($_POST['member_id']); $amount = floatval($_POST['amount']); $desc = trim($_POST['description']);
$pdo->beginTransaction();
$m = $pdo->prepare('SELECT * FROM member WHERE id=? FOR UPDATE'); $m->execute([$member_id]); $mem = $m->fetch();
if (!$mem) { $pdo->rollBack(); die('Member not found'); }
$remaining = $mem['allocated_budget'] - $mem['spent_amount'];
$ins = $pdo->prepare('INSERT INTO expense (admin_id, member_id, amount, description, transaction_date) VALUES (?,?,?,?,?)');
$ins->execute([$mem['admin_id'],$member_id,$amount,$desc,date('Y-m-d')]);
$new_spent = $mem['spent_amount'] + $amount;
$upd = $pdo->prepare('UPDATE member SET spent_amount=? WHERE id=?'); $upd->execute([$new_spent,$member_id]);
$pdo->commit();
if ($amount > $remaining) {
    $_SESSION['alert'] = "You have overspent by " . number_format($amount - $remaining,2);
}
header('Location: member_dashboard.php'); exit;
?>