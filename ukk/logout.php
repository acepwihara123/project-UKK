<?php
session_start();
session_destroy();
header("Location: /ukk/login.php");
exit();
?>