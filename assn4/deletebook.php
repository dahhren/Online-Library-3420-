<?php

session_start();

include('includes/library.php');
$pdo = connectDB();

$query = "DELETE FROM bookdetails WHERE bookid = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_GET['bookid']]);

header('Location: index.php');
exit();

?>