<?php

session_start();

include 'includes/library.php';
$pdo = connectDB();

session_destroy();
header("Location:index.php");

?>