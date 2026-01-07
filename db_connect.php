<?php
//to diplay errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//to check an active session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//variable declaration for database connectivity
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');     
define('DB_PASSWORD', '');          
define('DB_NAME', 'esec_ams');
//database connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
//to check database connection
if ($conn->connect_error) {
    die("ERROR: Could not connect. " . $conn->connect_error);
}
?>