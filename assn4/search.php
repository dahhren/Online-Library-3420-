<?php
include 'includes/library.php';
$pdo = connectDB();

session_start();
$search = $_POST['search'] ?? null;
$errors = [];

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

$stmt = $pdo->prepare("SELECT * FROM `bookdetails` WHERE `id` = ?");
$stmt->execute([$_SESSION['id']]);
$books = $stmt->fetchAll();
}



if (isset($_POST['submit'])) {


    $query = "SELECT * from bookdetails where title like ? and id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['%' . $search . '%', $_SESSION['id']]);
    $books = $stmt->fetchAll();
    if (!$books) {
        $errors['user'] = true;
    } else {
    }

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      $page_title = "Search";
      include 'includes/metadata.php';
    ?>
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
<section class="searchpage">
  <form id="processform" name="process" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post">
    <h2>Search</h2>
    <label for="search"></label>
    <input type="text" placeholder="The Great Gatsby" name="search" id="search" required/>
    <button type="submit" name="submit">Find</button></div>
  </form>
    <section class="display">
        <?php foreach ($books as $book): ?>
        <div class="book">
          <img src=<?= "../../../" . $book['coverimg'] ?> alt="Cover page of the Great Gatsby" height="220" width="200">
          <p><?=$book['title']?></p>
          <p><?=$book['author']?></p>
          <a onclick="displayBook(<?= $book['bookid'] ?>)">More Details</a>
          <a href=<?="./editbook.php?bookid=" . $book['bookid']?>>Edit Book</a>
          <a onclick="confirmDeleteBook(<?= $book['bookid'] ?>)">Delete Book</a>
        </div>
        <section id="<?= 'modal' . $book['bookid'] ?>" class="modal">
        <div class="modalcontents">
        <span class="close">&times;</span>
        <h2>Book Information</h2>
        <img src="<?= "../../../" . $book['coverimg'] ?>" alt="Cover page of the Great Gatsby" height="250" width="230">
        <p class="info">Title</p>
        <p class="details"><?= $book['title'] ?></p>
        <p class="info">Author</p>
        <p class="details"><?= $book['author'] ?></p>
        <p class="info">Rating (out of 10)</p>
        <p class="details"> <?= str_repeat("★", $book['rating']) ?></p>
        <p class="info">Genre</p>
        <p class="details"><?= $book['genre'] ?></p>
        <p class="info">Date Published</p>
        <p class="details"><?= $book['publication'] ?></p>
        <p class="info">ISBN</p>
        <p class="details"><?= $book['isbn'] ?></p>
        <p class="info">Description</p>
        <p class="desc"><?= $book['description'] ?></p>
        </div>
        </section>
        <?php endforeach;?>
</section>
    <?php include 'includes/footer.php';?>
    </body>
</html>