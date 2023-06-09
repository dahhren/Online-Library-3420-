<?php

$errors = array(); //declare empty array to add errors too

//get values from post or set to NULL if doesn't exist
$name = $_POST['name'] ?? null;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;
$cpassword = $_POST['cpassword'] ?? null;

session_start();
$id=$_SESSION['username'];

  include 'includes/library.php';
  $pdo = connectDB();

$query = "SELECT * FROM `libraryusers` WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt -> execute([$_SESSION['id']]);
$results = $stmt->fetch();

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

function updateaccount($pdo, $field, $value)
{
    $query = "UPDATE `libraryusers` SET `$field` = ? WHERE `libraryusers`.`id` = ? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$value, $_SESSION['id']]);
}


if (isset($_POST['submit'])) { //only do this code if the form has been submitted

    //  validate user has entered a name
    if (!isset($name) || strlen($name) === 0) {
        $errors['name'] = true;
    }
    
    if ($username != $_SESSION['username'] && usernamexist($pdo, $username)) {
      $errors['username'] = true;
    }

    // if (!isset($username) || strlen($username) === 0 || usernamexist($pdo, $username)) {
    //   $errors['username'] = true;
    // }
    
    if (strpos($email, "@") === false) {
      $errors['email'] = true;
    }
    // if (($npassword) != $results($name) === 0) {
    //     $stmt = $pdo->prepare("UPDATE `libraryusers` SET `name` = ? WHERE `libraryusers`.`id` = ?")->execute([$newname, $name]);  
    // }
  
    if ($_POST['password'] != null) {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      updateaccount($pdo, 'password', $password);
    }

    // //checking if passwords match
    // if ($password != $cpassword) {
    //     $errors['password'] = true;
    // }

    $id = $_POST['submit'];

    //only do this if there weren't any errors
    if (count($errors) ===  0) {
      $query = "UPDATE `libraryusers` SET `username` = ?, `email` = ?, `name` = ? WHERE `libraryusers`.`id` = ? ";
      $stmt = $pdo->prepare($query)->execute([$username, $email, $name, $_SESSION['id']]);

        //send the user to the login page.
        header("Location:accountedited.php");
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      $page_title = "Edit Account";
      include 'includes/metadata.php';
    ?>
  </head>
  <body>
    <?php include 'includes/header.php';?>
    <section class="account">
    <form id="processform" name="process" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
      <h2>Edit Account Details</h2>
        <div>
          <label for="name">Name:</label>
          <input type="text" placeholder="John Smith" name="name" id="name" value="<?php echo $results['name'] ?>" required/>
        </div>
        <div><span class="error <?=!isset($errors['name']) ? 'hidden' : "";?>">Please enter your Name</span></div>

        <div>
          <label for="username">Username:</label>
          <input type="text" placeholder="johnsmith5" name="username" id="username" minlength="3" maxlength="16" value="<?php echo $results['username'] ?>" required/>
        </div>
        <div><span class="error <?=!isset($errors['username']) ? 'hidden' : "";?>">Please create a username</span></div>

        <div>
            <label for="email">E-Mail:</label>
            <input type="email" placeholder="jsmith789@gmail.com" name="email" id="email" value="<?php echo $results['email']?>" required/>
          </div>
          <div><span class="error <?=!isset($errors['email']) ? 'hidden' : "";?>">Please enter a valid E-Mail address</span></div>

        <div>
            <label for="password">New Password:</label>
            <input type="password" placeholder="***********" name="password" id="password" minlength="6" required/>
          </div>
          <div><span class="error <?=!isset($errors['password']) ? 'hidden' : "";?>">Passwords do not match</span></div>
        </div>

        <div>
            <label for="cpassword">Confirm Password:</label>
            <input type="password" placeholder="***********" name="cpassword" id="cpassword" required/>
        </div>

        <div><button type="submit" name="submit">Save Changes</button></div>
        
        <div class="warning">
          <a href="deleteaccount.php" id="cdeleteb" onclick="confirmDeleteAcc()">DELETE ACCOUNT</a>
        </div>
        
        </form>
        </section>
        <?php include 'includes/footer.php';?>
  </body>
</html>