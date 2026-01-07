<?php
date_default_timezone_set('Asia/Kolkata'); 
require_once 'db_connect.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == 'student' && isset($_SESSION['log_id'])) {
    $logout_time = date("Y-m-d H:i:s");
    $log_id = $_SESSION['log_id'];
    
    $sql = "UPDATE attendance_logs SET logout_time = ? WHERE id = ?";
    if($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $logout_time, $log_id);
        $stmt->execute();
        $stmt->close();
    }
}
$_SESSION = array();

session_destroy();

header("location: index.php");
exit;