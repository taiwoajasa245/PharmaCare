<?php
require_once __DIR__ . '/../auth/guard.php';

// Suppliers feature has been removed from PharmaCare.
header('Location: dashboard.php');
exit();
?>
