<?php

if (!isset($_SESSION)) {
    session_start();
}
$signed = false;
if(isset($_SESSION['username'])) {
    $signed = true;
}
if ($signed) { ?>
  <header>
    <h1>The Online Times</h1>
    <button id="togglenav" class="button">
		Toggle Menu
    </button>
      <nav id="nav">
        <ul>
          <li><a href="index.php"><i class="fa-solid fa-house"></i>&nbsp;Home</a></li>
          <li><a href="search.php"><i class="fa-solid fa-magnifying-glass"></i>&nbsp;Search</a></li>
          <li><a href="addbook.php"><i class="fa-solid fa-plus"></i><i class="fa-solid fa-book"></i>&nbsp;Add Book</a></li>
          <li><a href="editaccount.php"><i class="fa-regular fa-user"></i>&nbsp;<?php echo $results['name']?></a></li>
          <li><a href="logout.php"><i class="fa-solid fa-sign-out"></i>&nbsp;Logout</a></li>
        </ul>
      </nav>
  </header>
<?php }
if(!$signed) { ?>
  <header>
    <h1>The Online Times</h1>
    <button id="togglenav" class="button">
		Toggle Menu
    </button>
      <nav id="nav">
        <ul>
          <li><a href="index.php"><i class="fa-solid fa-house"></i>&nbsp;Home</a></li>
          <li><a href="login.php"><i class="fa-solid fa-right-to-bracket"></i>&nbsp;Login</a></li>
          <li><a href="register.php"><i class="fa-solid fa-user-plus"></i>&nbsp;Register</a></li>
        </ul>
      </nav>
  </header>
<?php
  } 
?>