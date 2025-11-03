<?php
session_start();

unset($_SESSION['vendor_id']);
unset($_SESSION['vendor_name']);
unset($_SESSION['vendor_email']);
unset($_SESSION['vendor_service']);

session_destroy();

header("Location: index.php");
exit();
?>