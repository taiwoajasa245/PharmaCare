<?php

session_start();

// Any authenticated page includes this file so we do not repeat the same check everywhere.
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?auth=login');
    exit();
}