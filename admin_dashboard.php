<?php
include 'includes/admin_header.php';
?>

<h2>Admin Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
<p>Please use the navigation bar above to manage students, schedule labs, and view attendance reports.</p>

<?php include 'includes/footer.php'; ?>
