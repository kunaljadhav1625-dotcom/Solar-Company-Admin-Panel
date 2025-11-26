<?php
// public/logout.php - Logout
session_start();
session_destroy();
header("Location: index.php?message=Logged out successfully");
exit;
?>