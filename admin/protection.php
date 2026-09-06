<?php
// PROTECTION : si pas connecté → dehors
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

require '../includes/pdo.php';