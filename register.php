<?php

$errors = array(); //declare empty array to add errors too

//get values from post or set to NULL if doesn't exist
$name = $_POST['name'] ?? null;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$cpassword = $_POST['cpassword'] ?? null;

  include 'includes/library.php';
  $pdo = connectDB();
  
  $query = "SELECT ID FROM `libraryusers`";
  $stmt = $pdo->query($query);

function usernamexist($pdo, $username)
{
    $query = "SELECT * from libraryusers where username=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $result = $stmt->fetch();
    if ($result) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST['submit'])) { //only do this code if the form has been submitted
    
    if (!isset($username) || strlen($username) === 0 || usernamexist($pdo, $username)) {
        $errors['username'] = true;
    }

    //only do this if there weren't any errors
    if (count($errors) ===  0) {
      $query = "INSERT INTO libraryusers (username, password, email, name) values (?,?,?,?) "; 
      $stmt = $pdo->prepare($query)->execute([$username, password_hash($password,PASSWORD_DEFAULT), $email, $name]);

        //send the user to the login page.
        header("Location:login.php");
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      $page_title = "Register";
      include 'includes/metadata.php';
    ?>
  </head>
  <body>
    <?php include 'includes/header.php';?>
    <section class="account">
    <form id="registerform" name="registerform" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" autocomplete="off" novalidate>
      <h2>Create Account</h2>
        <div>
          <label for="name">Name:</label>
          <input type="text" placeholder="John Smith" name="name" id="name"/>
        </div>
        <span class="hidden">Please enter your Name!</span>

        <div>
          <label for="username">Username:</label>
          <input type="text" placeholder="johnsmith5" name="username" id="username" minlength="3" maxlength="16" required/>
        </div>
        <span class="hidden">Please create a username!</span>
        
        <div>
            <label for="email">E-Mail:</label>
            <input type="email" placeholder="jsmith789@gmail.com" name="email" id="email" required/>
          </div>
          <span class="hidden">Please enter a valid E-Mail address!</span>

        <div>
            <label for="password">Password:</label>
            <input type="password" placeholder="***********" name="password" id="password" minlength="6" required/>
            <div><i class="fa-solid fa-eye-slash" id="togglePassword"></i></div>
          </div>
          <span class="hidden">Passwords do not match!</span>

        <div>
            <label for="cpassword">Confirm Password:</label>
            <input type="password" placeholder="***********" name="cpassword" id="cpassword" required/>
        </div>

        <button type="submit" name="submit">Create Account</button>
        
        <div>
            <a href="./login.php">Existing User?</a>
        </div>

        </form>
        </section>
        <?php include 'includes/footer.php';?>
  </body>
</html>