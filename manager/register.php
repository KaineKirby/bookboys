<?php

include "../php/logreg.php" ;

//if a register attempt is in progress
if(isset($_POST["username"]) && isset($_POST["password"])) {

  $username = $_POST["username"] ;
  $password = $_POST["password"] ;
  $result = managerRegistration($username, $password) ;

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
		<title>BookBoys - Manager Registration</title>
	</head>
	<body>

    <div>

      <form method="post">
        <p>Manager Registration</p>
        <input autocomplete="off"  placeholder="Username" type="text" name="username">
        <br>
        <input autocomplete="off" placeholder="Password" type="password" name="password">
        <br>
        <input autocomplete="off" value="Register" type="submit" name="submit">
      </form>

      <p><?php echo $error ; ?></p>

    </div>

	</body>
</html>
