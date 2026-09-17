
<?php
date_default_timezone_set('Asia/Kolkata'); 
session_start();
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ESEC AMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container login-container">
    <h2>ESEC Attendance & Lab Management</h2>
    <p style="text-align: center;">Please enter your credentials to login.</p>

    <?php
    if (isset($_GET['error'])) {
        echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
    }
    ?>

    <form action="login_process.php" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
