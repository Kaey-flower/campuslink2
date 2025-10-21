<?php
require 'includes/db_config.php';

// Destroy the session and log out
session_unset();
session_destroy();

// Redirect to homepage
header("Location: index.php");
exit;
?>
