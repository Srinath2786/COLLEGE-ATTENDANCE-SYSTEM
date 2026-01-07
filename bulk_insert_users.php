<?php
require_once 'db_connect.php';

echo "<h1>Starting Bulk Student Insertion...</h1>";
$start_roll = 1;
$end_roll = 140;
$password_plain = 'Test@123';
$role = 'student';
$class_id = 'CSE-2023'; 

$hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, full_name, email, role, class_id) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("ssssss", $username, $hashed_password, $full_name, $email, $role, $class_id);

$success_count = 0;
$error_count = 0;

for ($i = $start_roll; $i <= $end_roll; $i++) {
    
    $num_padded = sprintf("%02d", $i);
    
    $username = 'es23cs' . $num_padded;
    $full_name = 'ES23CS' . $num_padded;
    $email = 'es23cse_' . $num_padded . '@gmail.com';
    
    if ($stmt->execute()) {
        echo " Successfully inserted user: <strong>" . htmlspecialchars($username) . "</strong><br>";
        $success_count++;
    } else {
        echo " Error inserting user " . htmlspecialchars($username) . ": " . $stmt->error . "<br>";
        $error_count++;
    }
}

echo "<hr><h2>Bulk Insertion Complete!</h2>";
echo "<p style='color:green;'>Total students successfully inserted: $success_count</p>";
echo "<p style='color:red;'>Total errors: $error_count</p>";

$stmt->close();
$conn->close();

?>