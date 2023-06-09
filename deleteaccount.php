<?php

session_start();

include 'includes/library.php';
$pdo = connectDB();

$query = "DELETE FROM `libraryusers` WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id']]);

session_destroy();
header("Location:login.php");

?>