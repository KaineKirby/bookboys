<?php

include "../php/logreg.php" ;

//if a register attempt is in progress
if(isset($_POST["username"]) && isset($_POST["password"])) {

  $username = $_POST["username"] ;
  $password = $_POST["password"] ;
  $result = userRegistration($username, $password) ;

  if ($result == 1) {
    $error = "Registration successful" ;
  }
  else if($result == 0) {
    $error = "Username is already taken. Please choose another" ;
  }
  else {
    $error = "There was an unknown error." ;
  }

}
//there is no register attempt in progress
else {
  $error = "" ;
}

?>
<html>

	<head>
		<title>BookBoys - Customer Registration</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-login.css">
	</head>
	<body>

    <div class="login" id="login-container">
      <form method="post">
        <p class="login-text" id="header">Customer Registration</p>
        <input class="login-box" id="username" autocomplete="off"  placeholder="Username" type="text" name="username">
        <br>
        <input class="login-box" id="password" autocomplete="off" placeholder="Password" type="password" name="password">
        <br>
        <input class="login-btn" id="login-submit" autocomplete="off" value="Register" type="submit" name="submit">
      </form>

      <p class="login-text" id="error"><?php echo $error ; ?></p>

      <a class="login-link" href="login.php">Already have an account? Login</a>

    </div>

	</body>
</html>
