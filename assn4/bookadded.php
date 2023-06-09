<?php

include 'includes/library.php';
$pdo = connectDB();

if (!isset($_SESSION)) {
    session_start();
  }
  $signed = false;
  if(isset($_SESSION['username'])) {
    $signed = true;
  }
  
  if ($signed) {
  $query = "SELECT * FROM `libraryusers` WHERE id = ?";
  $stmt = $pdo->prepare($query);
  $stmt -> execute([$_SESSION['id']]);
  $results = $stmt->fetch();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php
      $page_title = "Success";
      include 'includes/metadata.php';
    ?>
  </head>
    <body>
        <?php include 'includes/header.php'; ?>
        <section class="thanks"> 
        <p>Book has been successfully added.</p><br>
        <div>
            <a href="addbook.php">Add another book</a>
        </div>
        <br><br>
        <div>
            <a href="index.php">Return to home</a>
        </div>
        </section>
        <?php include 'includes/footer.php'; ?>
    </body>
</html>