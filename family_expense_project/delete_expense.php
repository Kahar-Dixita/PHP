<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['member_id'])) {
    header("Location: member_dashboard.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) die("Invalid expense ID");

$stmt = $pdo->prepare("SELECT * FROM expense WHERE id=? AND member_id=?");
$stmt->execute([$id, $_SESSION['member_id']]);
$expense = $stmt->fetch();

if ($expense) {
    // adjust spent amount
    $update = $pdo->prepare("UPDATE member SET spent_amount = spent_amount - ? WHERE id=?");
    $update->execute([$expense['amount'], $_SESSION['member_id']]);

    // delete expense
    $del = $pdo->prepare("DELETE FROM expense WHERE id=? AND member_id=?");
    $del->execute([$id, $_SESSION['member_id']]);
}

header("Location: member_dashboard.php");
exit;
