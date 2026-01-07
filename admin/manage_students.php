<?php
include '../includes/admin_header.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_student'])) {
        $username = $_POST['username'];
        $full_name = $_POST['full_name'];
        $class_id = $_POST['class_id'];
        $password = password_hash('Test@123', PASSWORD_DEFAULT); // Default password
        $role = 'student';

        $sql = "INSERT INTO users (username, password, full_name, role, class_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $password, $full_name, $role, $class_id);
        if ($stmt->execute()) {
            $message = "Student added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
    if (isset($_POST['delete_student'])) {
        $user_id = $_POST['user_id'];
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $message = "Student deleted successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
    if (isset($_POST['reset_password'])) {
        $user_id = $_POST['user_id'];
        $password = password_hash('Test@123', PASSWORD_DEFAULT); // Default password
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password, $user_id);
        if ($stmt->execute()) {
            $message = "Password reset successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>
<h2>Manage Students</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<h3>Add New Student</h3>
<form method="POST" action="">
    <label for="username">Username (e.g., Roll Number)</label>
    <input type="text" name="username" required>
    <br>
    <label for="full_name">Full Name</label>
    <input type="text" name="full_name" required>
    <br>
    <label for="class_id">Class ID (e.g., CSE-A)</label>
    <input type="text" name="class_id" required>
    <br>
    <button type="submit" name="add_student">Add Student</button>
    <br>
</form>
<style>
    form{
        justify-content: center;
        align-items: center;
        display: flex;
        flex-direction: column;
    }
</style>
<hr>
<h3>Existing Students</h3>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Class ID</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT id, username, full_name, class_id FROM users WHERE role = 'student'");
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['class_id']) . "</td>";
            echo "<td>
                    <form method='POST' action='' style='display:inline-block;'>
                        <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                        <button type='submit' name='reset_password'>Reset Password</button>
                    </form>
                    <form method='POST' action='' style='display:inline-block;'>
                        <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                        <button type='submit' name='delete_student' class='logout-btn'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>