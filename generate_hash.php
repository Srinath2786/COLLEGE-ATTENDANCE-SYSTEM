<?php
// The password you want to use
$passwordToHash = 'Admin@123';

// Generate the hash using your server's PHP environment
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);

echo "<h3>Your New Password Hash:</h3>";
echo "<p>Copy the entire line of text below. This is the new hash for 'Admin@123'.</p>";
echo "<hr>";
echo "<strong>" . htmlspecialchars($hashedPassword) . "</strong>";
echo "<hr>";
?>