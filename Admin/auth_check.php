<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'mcq_management'])) {
    header('Location: SignIn.php');
    exit();
}
