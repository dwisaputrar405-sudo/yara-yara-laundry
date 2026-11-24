<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['customer'])) {
    header('Location: login.php');
    exit;
}