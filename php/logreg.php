<?php
/*
* This script will define all of the functions neccessary for:
* 1. Creating new users/managers
* 2. Logging into a user or manager account
*/

//given a username and password, register a new manager
//possible return states:
//1. Returns a 1, successful registration
//2. -1, there was a misc error
//3. 0 , the username was already taken
function managerRegistration($username, $password) {

  include "vars.php" ;

  //we shouldn't be able to see our user's passwords
  $password = $saltLeft.$password.$saltRight ;
  $password = hash('ripemd160', $password) ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    $error = "There was a problem with registration." ;
  }
  //there wasnt a connection error
  else {

    //need to check for duplicate usernames
    $stmt = $conn->prepare("SELECT id from managers WHERE username=?") ;
    //? = username
    $stmt->bind_param("s", $username) ;
    //if we can execute the statement
    if($stmt->execute()) {
      //and if there is a result (username exists)
      if($stmt->fetch()) {
        return 0 ;
      }
    }
    else {
      return -1 ;
    }

    //if we got here the username wasn't taken

    $stmt = $conn->prepare("INSERT INTO managers (username, password) VALUES (?, ?)") ;
    //ads are disabled, string length defaults to 5, and there is no langauge selected by default
    $stmt->bind_param("ss", $username, $password) ;

    if ($stmt->execute()) {
      return 1 ;
    }
    else {
      return -1 ;
    }

  }

}
//given a username and password, register a new manager
//possible return states:
//1. Returns a 1, successful registration
//2. -1, there was a misc error
//3. 0 , the username was already taken
function userRegistration($username, $password) {

  include "vars.php" ;

  //we shouldn't be able to see our user's passwords
  $password = $saltLeft.$password.$saltRight ;
  $password = hash('ripemd160', $password) ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    $error = "There was a problem with registration." ;
  }
  //there wasnt a connection error
  else {

    //need to check for duplicate usernames
    $stmt = $conn->prepare("SELECT id from users WHERE username=?") ;

    //echo mysqli_error($conn) ;
    //? = username
    $stmt->bind_param("s", $username) ;
    //if we can execute the statement
    if($stmt->execute()) {
      //and if there is a result (username exists)
      if($stmt->fetch()) {
        return 0 ;
      }
    }
    else {
      return -1 ;
    }

    //if we got here the username wasn't taken

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)") ;
    //ads are disabled, string length defaults to 5, and there is no langauge selected by default
    $stmt->bind_param("ss", $username, $password) ;

    if ($stmt->execute()) {
      return 1 ;
    }
    else {
      return -1 ;
    }

  }

}

//given a username and password, attempt to login
//possible return values
//1. 1, successful login
//2. -1, misc error
//3. 0 incorrect username/password
function managerLogin($username, $password) {

  include "vars.php" ;

  //get the password in the same state it would be in the database (salted and hashed)
  $password = $saltLeft.$password.$saltRight ;
  $password = hash('ripemd160', $password) ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return -1 ;
  }
  //there was no connection error
  else {

    $stmt = $conn->prepare("SELECT id, username, password from managers WHERE username=?") ;

    //bind our username parameter to the ?
    $stmt->bind_param("s", $username) ;

    //store results in these values
    $stmt->bind_result($id, $dbUsername, $dbPassword) ;

    if($stmt->execute()) {

      if($stmt->fetch()) {

        if ($password == $dbPassword) {
          //passwords match, go ahead and login
          session_start() ;

          //set the session variables
          $_SESSION["username"] = $dbUsername ;
          $_SESSION["user_id"] = $id ;
          $_SESSION["is_manager"] = true ;

          return 1 ;

        }
        else {
          //incorrect password
          return 0 ;
        }
      }
      else {
        //incorrect username
        return 0 ;
      }
    }
    else {
      return -1 ;
    }
  }
}


//given a username and password, attempt to login
//possible return values
//1. 1, successful login
//2. -1, misc error
//3. 0 incorrect username/password
function userLogin($username, $password) {

  include "vars.php" ;

  //get the password in the same state it would be in the database (salted and hashed)
  $password = $saltLeft.$password.$saltRight ;
  $password = hash('ripemd160', $password) ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return -1 ;
  }
  //there was no connection error
  else {

    $stmt = $conn->prepare("SELECT id, username, password from users WHERE username=?") ;

    //bind our username parameter to the ?
    $stmt->bind_param("s", $username) ;

    //store results in these values
    $stmt->bind_result($id, $dbUsername, $dbPassword) ;

    if($stmt->execute()) {

      if($stmt->fetch()) {

        if ($password == $dbPassword) {
          //passwords match, go ahead and login
          session_start() ;

          //set the session variables
          $_SESSION["username"] = $dbUsername ;
          $_SESSION["user_id"] = $id ;
          $_SESSION["is_manager"] = false ;

          return 1 ;

        }
      }
      else {
        //incorrect username
        return 0 ;
      }
    }
    else {
      return -1 ;
    }
  }
}


?>
