<?php include '../includes/admin_header.php'; ?>

<h2>Attendance Reports</h2>
<form method="GET" action="">
    <label for="class_id_filter">Filter by Class ID:</label>
    <input type="text" name="class_id_filter" id="class_id_filter" value="<?php echo isset($_GET['class_id_filter']) ? htmlspecialchars($_GET['class_id_filter']) : ''; ?>">
    <br>
    <label for="date_filter">Filter by Date:</label>
    <input type="date" name="date_filter" id="date_filter" value="<?php echo isset($_GET['date_filter']) ? htmlspecialchars($_GET['date_filter']) : ''; ?>">
    <br>
    <button type="submit">Filter</button>
</form>
<style>
    form{
        align-items: center;
        justify-content: center;
        display: flex;
        flex-direction: column;
    }

</style>
<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Class ID</th>
            <th>Login Time</th>
            <th>Logout Time</th>
            <th>IP Address</th>
            <th>System Name</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT u.full_name, u.class_id, a.login_time, a.logout_time, a.ip_address, a.system_name
                FROM attendance_logs a
                JOIN users u ON a.user_id = u.id
                WHERE u.role = 'student'";
        $params = [];
        $types = '';
        if (!empty($_GET['class_id_filter'])) {
            $sql .= " AND u.class_id = ?";
            $params[] = $_GET['class_id_filter'];
            $types .= 's';
        }
        if (!empty($_GET['date_filter'])) {
            $sql .= " AND DATE(a.login_time) = ?";
            $params[] = $_GET['date_filter'];
            $types .= 's';
        }
        $sql .= " ORDER BY a.login_time DESC";
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['class_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['login_time']) . "</td>";
            echo "<td>" . ($row['logout_time'] ? htmlspecialchars($row['logout_time']) : 'Not logged out') . "</td>";
            echo "<td>" . htmlspecialchars($row['ip_address']) . "</td>";
            echo "<td>" . htmlspecialchars($row['system_name']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>