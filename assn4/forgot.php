<?php
require 'includes/library.php';


define('FROM_EMAIL', '<noreply@loki.trentu.ca>');
define('RESET_SUBJECT', 'The Online Times - Password Reset');


if (isset($_POST['submit'])) 
{
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    $errors = [];

    // Validate input
    if (empty($username) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid input';
    }

    // Check if user exists
    $pdo = connectDB();
    $stmt = $pdo->prepare('SELECT * FROM `libraryusers` WHERE username = ? AND email = ?');
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors[] = 'User not found';
    } 
    else 
    {
        // Generate a unique token and update the database
        $token = uniqid(); // newpassword
        $hash = password_hash($token, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE `libraryusers` SET password = ? WHERE username = ?');
        $stmt->execute([$hash, $username]);

        require_once 'Mail.php';
        // Send password reset email
        $to = $user['email'];
        $body = "This is your new password {$token}";
        $host = "smtp.trentu.ca";
        $headers = ['From' => FROM_EMAIL, 'To' => $to, 'Subject' => RESET_SUBJECT];

        
        $smtp = Mail::factory('smtp', ['host' => "smtp.trentu.ca"]);
        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
            $errors[] = 'Email not sent';
        } else {
            header('Location:login.php');
            exit();
        }
    }

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
    $page_title = "Reset Password";
    include 'includes/metadata.php';
    ?>
  </head>
  <body>
    <?php include 'includes/header.php'; ?>
    <section class="account">
    <form id="processform" name="process" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
      <h2>Password Reset</h2>
        <div>
            <label for="username">Username:</label>
            <input type="text" placeholder="johnsmith1" name="username" id="username"/>
          </div>
          <p class="option">and</p>
        <div>
          <label for="email">E-Mail:</label>
          <input type="email" placeholder="jsmith789@gmail.com" name="email" id="email"/>
        </div>
        <div><button type="submit" name="submit" id="submit">Submit</button></div>
    </form>
    </section>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>