<?php
date_default_timezone_set('Asia/Kolkata'); // or your local timezone

require_once __DIR__ . '/../db_connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
$current_page = basename($_SERVER['PHP_SELF']);
$base_folder = '/esec-attendance';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - ESEC AMS</title>
    <link rel="stylesheet" href="<?php echo $base_folder; ?>/css/style.css">
</head>
<body>

<div class="container">
    <nav class="main-nav">
        <ul>
            <li><a href="<?php echo $base_folder; ?>/admin_dashboard.php" 
                   class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">Dashboard</a></li>
            
            <li><a href="<?php echo $base_folder; ?>/admin/manage_students.php" 
                   class="<?php echo ($current_page == 'manage_students.php') ? 'active' : ''; ?>">Manage Students</a></li>
            
            <li><a href="<?php echo $base_folder; ?>/admin/schedule_lab.php" 
                   class="<?php echo ($current_page == 'schedule_lab.php') ? 'active' : ''; ?>">Schedule Labs</a></li>
            
            <li><a href="<?php echo $base_folder; ?>/admin/reports.php" 
                   class="<?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">Attendance Reports</a></li>
            
            <li class="logout"><a href="<?php echo $base_folder; ?>/logout.php">Logout</a></li>
        </ul>
    </nav>

