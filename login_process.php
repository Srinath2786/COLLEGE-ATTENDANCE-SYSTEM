<?php
date_default_timezone_set('Asia/Kolkata'); 
//database connectivity
require_once 'db_connect.php';
//getting credentials form login page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: index.php?error=Username and Password are required");
        exit;
    }

    $sql = "SELECT id, username, password, role, full_name, class_id FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $db_username, $hashed_password, $role, $full_name, $class_id);
                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        if ($role == 'admin') {
                            session_regenerate_id();
                            $_SESSION['loggedin'] = true;
                            $_SESSION['id'] = $id;
                            $_SESSION['username'] = $db_username;
                            $_SESSION['role'] = $role;
                            $_SESSION['full_name'] = $full_name;
                            header("Location: admin_dashboard.php");
                            exit;
                        }

                        if ($role == 'student') {
                            $current_day = date('l'); 
                            
                            $schedule_sql = "SELECT id FROM lab_schedules 
                                             WHERE class_id = ? 
                                             AND day_of_week = ? 
                                             AND CURTIME() BETWEEN start_time AND end_time
                                             AND CURDATE() BETWEEN semester_start_date AND semester_end_date";
                            
                            if ($schedule_stmt = $conn->prepare($schedule_sql)) {
                                $schedule_stmt->bind_param("ss", $class_id, $current_day);
                                $schedule_stmt->execute();
                                $schedule_stmt->store_result();

                                if ($schedule_stmt->num_rows > 0) {
                                    session_regenerate_id();
                                    $_SESSION['loggedin'] = true;
                                    $_SESSION['id'] = $id;
                                    $_SESSION['username'] = $db_username;
                                    $_SESSION['role'] = $role;
                                    $_SESSION['full_name'] = $full_name;
                                    $_SESSION['class_id'] = $class_id;

                                    $ip_address = $_SERVER['REMOTE_ADDR'];
                                    $system_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                                    $login_time = date("Y-m-d H:i:s");
                                    
                                    $log_sql = "INSERT INTO attendance_logs (user_id, login_time, ip_address, system_name) VALUES (?, ?, ?, ?)";
                                    if ($log_stmt = $conn->prepare($log_sql)) {
                                        $log_stmt->bind_param("isss", $id, $login_time, $ip_address, $system_name);
                                        $log_stmt->execute();
                                        $_SESSION['log_id'] = $log_stmt->insert_id;
                                        $log_stmt->close();
                                    }
                                    header("Location: student_dashboard.php");
                                    exit;
                                } else {
                                    // NO active lab session found. Reject the login.
                                    header("Location: index.php?error=No scheduled lab session is active for your class right now.");
                                    exit;
                                }
                                $schedule_stmt->close();
                            }
                        }

                    } else {
                        header("Location: index.php?error=Invalid username or password.");
                    }
                }
            } else {
                header("Location: index.php?error=Invalid username or password.");
            }
        } else {
            header("Location: index.php?error=Oops! Something went wrong.");
        }
        $stmt->close();
    }
    $conn->close();
}
?>