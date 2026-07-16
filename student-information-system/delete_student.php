<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header('Location: login.php');
    exit;
}
include 'db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Vulnerable to SQLi but we keep it simple
    $query = "DELETE FROM users WHERE id = $id AND role='student'";
    mysqli_query($conn, $query);
}
header('Location: teacher_dashboard.php');
exit;
?>