<?php
// db.php - include at top of every page that needs DB/session
if (session_status() === PHP_SESSION_NONE) {
    // ensure cookie path consistent
    ini_set('session.cookie_path', '/');
    session_start();
}

// adjust credentials if needed
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pass = '';
$db_name = 'prime_furniture';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    // friendly dev message
    die("DB connect failed: " . $mysqli->connect_error);
}
?>
