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

$bookid = $_GET['bookid'];
// fetch book details
$query = "SELECT * from bookdetails where bookid=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$bookid]);
$books = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      $page_title = "Book Details";
      include 'includes/metadata.php';
      ?>
  </head>
    <body>
      <?php include 'includes/header.php'; ?>
 <section class="bookinfo">
  <h2>Book Information</h2>
  <img src="<?= "../../../" . $books['coverimg'] ?>" alt="Cover page of the Great Gatsby" height="250" width="230">
  <p class="info">Title</p>
  <p class="details"><?= $books['title'] ?></p>
  <p class="info">Author</p>
  <p class="details"><?= $books['author'] ?></p>
  <p class="info">Rating (out of 10)</p>
  <p class="details"> <?= str_repeat("★", $books['rating']) ?></p>
  <p class="info">Genre</p>
  <p class="details"><?= $books['genre'] ?></p>
  <p class="info">Date Published</p>
  <p class="details"><?= $books['publication'] ?></p>
  <p class="info">ISBN</p>
  <p class="details"><?= $books['isbn'] ?></p>
  <p class="info">Description</p>
  <p class="desc"><?= $books['description'] ?></p>
 </section>
  <?php include 'includes/footer.php'; ?>
 </body>
 </html>