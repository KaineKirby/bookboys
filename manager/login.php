<?php

include "../php/logreg.php" ;

//TODO: also make sure we aren't already logged in

//if a login attempt is in progress
if(isset($_POST["username"]) && isset($_POST["password"])) {

  $username = $_POST["username"] ;
  $password = $_POST["password"] ;
  $result = managerLogin($username, $password) ;

  if ($result == 1) {
    $error = "Login successful" ;
    header('Location: stock.php') ;
  }
  else if($result == 0) {
    $error = "Incorrect username/password" ;
  }
  else {
    $error = "There was an unknown error." ;
  }

}
//there is no login attempt in progress
else {
  $error = "" ;
}

?>
<html>
	<head>
		<title>BookBoys - Manager Login</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-login.css">
	</head>
	<body>

    <div class="login" id="login-container">
      <form method="post">
        <p class="login-text" id="header">Manager Login</p>
        <input class="login-box" id="username" autocomplete="off"  placeholder="Username" type="text" name="username">
        <br>
        <input class="login-box" id="password" autocomplete="off" placeholder="Password" type="password" name="password">
        <br>
        <input class="login-btn" id="login-submit" autocomplete="off" value="Login" type="submit" name="submit">
      </form>

      <p class="login-text" id="error"><?php echo $error ; ?></p>

    </div>

	</body>
</html>
