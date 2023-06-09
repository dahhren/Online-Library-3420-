<?php
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$errors = [];

if (isset($_POST['submit'])) {
    include 'includes/library.php';

    $pdo = connectDB();

    $query = "SELECT * FROM `libraryusers` WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt -> execute([$username]);
    $result = $stmt->fetch();

    if (!empty($_POST['remb'])){
    setcookie("username",$username,time()+60*60*24*30*12);
    setcookie("password",$password,time()+60*60*24*30*12);
    } else {
      setcookie("username","");
      setcookie("password","");
    }
    if ($result === false){
        $errors ['user'] = true;
      }
      else {
        if (password_verify($password, $result['password'])){
          session_start();
          $_SESSION['username'] = $username;
          $_SESSION['id'] = $result['id'];
          header ("Location:index.php");
          exit();
        }
        else 
            $errors['login'] = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login</title>
    <?php
    $page_title = "Login";
    include "includes/metadata.php";
    ?>
  </head>
  <body>
    <?php include "includes/header.php"?>
    <section class="account">
    <form id="processform" name="process" action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST">
      <h2>Login</h2>
        <div>
          <label for="username">Username:</label>
          <input type="text" placeholder="johnsmith5" name="username" id="username" value="<?=$username;?>">
        </div>

        <div>
          <label for="password">Password:</label>
          <input type="password" placeholder="**********" name="password" id="password" minlength="3" maxlength="16" required/>
          <div><i class="fa-solid fa-eye-slash" id="togglePassword"></i></div>
        </div>

        <div>
          <span class="<?=!isset($errors['user']) ? 'hidden' : "";?>">*User does not exist!</span>
          <span class="<?=!isset($errors['login']) ? 'hidden' : "";?>">*Incorrect username or password</span>
        </div>

        <div>
            <input type="checkbox" name="remb" id="remb" />
            <label for="remb">Remember me</label> 
        </div>

        <div><button type="submit" name="submit">Login</button>
        <a href="./forgot.php">Forgot Password?</a></div>
      </form>
    </section>
    <?php include "includes/footer.php"?>
  </body>
</html>