<?php
include '../includes/admin_header.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_schedule'])) {
        $class_id = $_POST['class_id'];
        $semester = $_POST['semester'];
        $course_name = $_POST['course_name'];
        $faculty_name = $_POST['faculty_name'];
        $day_of_week = $_POST['day_of_week'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $semester_start_date = $_POST['semester_start_date'];
        $semester_end_date = $_POST['semester_end_date'];
        $sql = "INSERT INTO lab_schedules (class_id, semester, course_name, faculty_name, day_of_week, start_time, end_time, semester_start_date, semester_end_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssssss", $class_id, $semester, $course_name, $faculty_name, $day_of_week, $start_time, $end_time, $semester_start_date, $semester_end_date);
        if($stmt->execute()){
            $message = " Lab scheduled successfully!";
        } else {
            $message = " Error: " . $stmt->error;
        }
    }
    if (isset($_POST['delete_schedule'])) {
        $schedule_id = $_POST['schedule_id'];
        $sql = "DELETE FROM lab_schedules WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $schedule_id);
        if($stmt->execute()){
            $message = " Schedule deleted successfully!";
        } else {
            $message = " Error: " . $stmt->error;
        }
    }
}
?>
<h2>Schedule a New Lab</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="POST" action="">
    <label>Class ID (e.g., CSE-A)</label>
    <br>
    <input type="text" name="class_id" required>
    <br>
    <label>Semester</label>
    <br>
    <input type="number" name="semester" required>
    <br>
    <label>Course Name</label>
    <br>
    <input type="text" name="course_name" required>
    <br>
    <label>Faculty Name</label>
    <br>
    <input type="text" name="faculty_name" required>
    <br>
    <label>Day of the Week</label>
    <br>
    <select name="day_of_week" required>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
        <option value="Saturday">Saturday</option>
    </select>
    <br>
    <label>Start Time</label>
    <input type="time" name="start_time" required>
    <br>
    <label>End Time</label>
    <input type="time" name="end_time" required>
    <br>
    <label>Semester Start Date</label>
    <input type="date" name="semester_start_date" required>
    <br>
    <label>Semester End Date</label>
    <input type="date" name="semester_end_date" required>
    <br>
    <button type="submit" name="add_schedule">Schedule Lab</button>
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
<hr style="margin: 40px 0;">
<h2>Currently Scheduled Labs</h2>
<table>
    <thead>
        <tr>
            <th>Class ID</th>
            <th>Course</th>
            <th>Faculty</th>
            <th>Day</th>
            <th>Time</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM lab_schedules ORDER BY class_id, day_of_week");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $start_time_formatted = date("g:i A", strtotime($row['start_time']));
                $end_time_formatted = date("g:i A", strtotime($row['end_time']));
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['class_id']) . " (Sem " . htmlspecialchars($row['semester']) . ")</td>";
                echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['faculty_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['day_of_week']) . "</td>";
                echo "<td>" . $start_time_formatted . " - " . $end_time_formatted . "</td>";
                echo "<td>
                        <form method='POST' action='' onsubmit='return confirm(\"Are you sure you want to delete this schedule?\");'>
                            <input type='hidden' name='schedule_id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete_schedule' class='logout-btn' style='background-color: #c0392b;'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>No labs have been scheduled yet.</td></tr>";
        }
        ?>
    </tbody>
</table>
<?php include '../includes/footer.php'; ?>