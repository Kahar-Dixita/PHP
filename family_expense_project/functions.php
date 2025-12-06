<?php
require_once 'config.php';

function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function palette() {
    return [
        'bg' => '#F7EDE7',
        'card' => '#CDB8C8',
        'accent' => '#E6C57A',
        'mint' => '#C9E7D7',
        'coral' => '#F6B6A8',
        'dark' => '#6B5A4A'
    ];
}
?>