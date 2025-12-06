<?php
require_once 'functions.php'; require_login();
$id = intval($_GET['id'] ?? 0); $admin_id = $_SESSION['admin_id'];
$pdo->prepare('DELETE FROM member WHERE id=? AND admin_id=?')->execute([$id,$admin_id]);
header('Location: admin_dashboard.php'); exit;
?>