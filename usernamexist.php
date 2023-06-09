<?php

// php script to check if a username already exists in the database
// returns true if username exists, false if it does not
// helps to create the js to confirm deletion of an account

include 'includes/library.php';
$pdo = connectDB();

$username = $_GET['username'] ?? null;
if (!$username) {
    echo 'error';
    exit();
}

$query = "SELECT * from libraryusers where username=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$result = $stmt->fetch();
if ($result) {
    echo 'true';
} else {
    echo 'false';
}
exit();
?>