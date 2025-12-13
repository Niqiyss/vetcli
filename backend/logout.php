<!-- logout.php (for all user) -->

<?php
session_start();

$_SESSION = [];

session_destroy();

header("Location: ../frontend/home.php");
exit();

?>
