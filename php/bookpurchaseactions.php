<?php
//functions relating specifically to the buying of books

/*
add an item to cart given a userId bookId and number of items to add
0 is failure
1 is success
-1 not enough in stock
*/
function addCart($userId, $bookId, $quantity) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return 0 ;
  }
  //there wasnt a connection error
  else {

    if (updateCartExisting($userId, $bookId) != 1) {
      return 0 ;
    }

    if (addCartCheck($bookId, $quantity) != 1) {
      return 0 ;
    }

    $stmt = $conn->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)") ;

    $stmt->bind_param("iii", $userId, $bookId, $quantity) ;

    //if we can execute statement
    if ($stmt->execute()) {
      return 1 ;
      //also need to decrement that amount from stock
    }
    else {

      return 0 ;

    }

  }

}
//check if its okay to add that amount to a cart
//1 is good
// 0 is not good
function addCartCheck($bookId, $quantity) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return 0 ;
  }
  //there wasnt a connection error
  else {

    $stmt = $conn->prepare("SELECT quantity FROM stock where book_id=$bookId") ;

    if ($stmt->execute()) {
      $stmt->bind_result($resultQuantity) ;
      if($stmt->fetch()) {

        if ($quantity <= $resultQuantity) {
          return 1 ;
        }
        else {
          return 0 ;
        }

      }
      else {
        return 0 ;
      }
    }
    else {
    return  0 ;
    }

  }

}
//if the entry already exists for the user, then it will delete the entry in prep for adding a new one
function updateCartExisting($userId, $bookId) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return 0 ;
  }
  //there wasnt a connection error
  else {

    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=$userId AND book_id=$bookId") ;

    if ($stmt->execute()) {

      return 1 ;

    }
    else {
      return 1 ;
    }

  }

}
//returns an array of cart items and relevant info for the user
function getCartItems($userId) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    //welp
  }
  //there wasnt a connection error
  else {

    $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, sale_price, stock.quantity, cart.quantity FROM books, authors, publishers, stock, cart WHERE cart.user_id=$userId AND books.book_id=cart.book_id AND books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id GROUP BY book_id") ;

    //echo mysqli_error($conn) ;

    if ($stmt->execute()) {
      $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price, $max, $quantity) ;
      $index = 0 ;
      while($stmt->fetch()) {
        $bookIds[$index] = $bookId ;
        $isbns[$index] = $isbn ;
        $bookNames[$index] = $bookName ;
        $authorNames[$index] = $authorName ;
        $publisherNames[$index] = $publisherName ;
        $editions[$index] = $edition ;
        $thumbnails[$index] = $thumbnail ;
        $prices[$index] = $price ;
        $maxes[$index] = $max ;
        $quantities[$index] = $quantity ;
        $index++ ;
      }

      if (isset($bookIds)) {
        return array($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices, $maxes, $quantities) ;
      }
      else {
        return null ;
      }




    }
    else {
      return null ;
    }


  }

}
//returns a fake shipping number of n numbers
function randomShipGen($n=15) {

  $num = "" ;
  for ($i = 0 ; $i < $n ; $i++) {
    $num = $num.rand(0, 9) ;

  }

  return $num ;

}
//add a transaction to the cart
function addTransaction($address, $state, $method, $total) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return 0 ;
  }
  //there wasnt a connection error
  else {

    $stmt = $conn->prepare("INSERT INTO transaction (user_id, shipping_address, shipping_state, shipping_method, transaction_date, tracking_number, total) VALUES (?, ?, ?, ?, ?, ?, ?)") ;

    $date = date('Y-m-d') ;
    $trackNum = randomShipGen() ;

    $userId = $_SESSION["user_id"] ;

    $stmt->bind_param("isssssd", $userId, $address, $state, $method, $date, $trackNum, $total) ;

    //can we execute statement
    if ($stmt->execute()) {
      /*
      * Need to:
      * 1. Remove the items from the stock
      * 2. Remove the items from the user's cart
      *
      *
      * Get list of user's cart items
      * then use a function do deduct an amount from the stock that they had in cart?
      * in a loop?????
      * ????????????????????
      * then do basic query to delete items from cart
      */


      //removing the items from stock
      //first need to get an array of items, both the quantity and the ids
      list($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices, $stockQuantities, $amountInCart) = getCartItems($userId) ;

      //now we have the info
      $i = 0 ;
      while ($i < count($bookIds)) {

        //find what to set the stock to
        $newStock = $stockQuantities[$i] - $amountInCart[$i] ;

        $stmt = $conn->prepare("UPDATE stock SET quantity='$newStock' WHERE book_id='$bookIds[$i]'") ;

        if ($stmt->execute()) {
          //success
        }
        else {
          return -1 ;
        }
        $i++ ;
      }

      //now to clear cart

      $stmt = $conn->prepare("DELETE FROM cart WHERE user_id='$userId'") ;

      if ($stmt->execute()) {
        //good
      }
      else {
        return -1 ;
      }


      return 1 ;

    }
    else {
      return -1 ;
    }



  }



}
//get a list of transactions for a particular user id
function getTransactions($userId) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return 0 ;
  }
  //there wasnt a connection error
  else {

    $stmt = $conn->prepare("SELECT transaction_id, shipping_address, shipping_state, shipping_method, transaction_date, tracking_number, total FROM transaction WHERE user_id='$userId'") ;

    if ($stmt->execute()) {
      $stmt->bind_result($id, $address, $state, $method, $date, $trackNo, $total) ;

      $i = 0 ;
      while ($stmt->fetch()) {
        $ids[$i] = $id ;
        $addresses[$i] = $address ;
        $states[$i] = $state ;
        $methods[$i] = $method ;
        $dates[$i] = $date ;
        $trackNos[$i] = $trackNo ;
        $totals[$i] = $total ;
        $i++ ;
      }

      if (isset($ids)) {
        return array($ids, $addresses, $states, $methods, $dates, $trackNos, $totals) ;

      }
      else {
        return null ;
      }


    }
    else {
      return -1 ;
    }

  }

}
//get a single transaction by id
function getTransaction($transId) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return 0 ;
  }
  //there wasnt a connection error
  else {

    $stmt = $conn->prepare("SELECT user_id, shipping_address, shipping_state, shipping_method, transaction_date, tracking_number, total FROM transaction WHERE transaction_id='$transId'") ;

    if ($stmt->execute()) {
      $stmt->bind_result($userId, $address, $state, $method, $date, $trackNo, $total) ;
      if ($stmt->fetch()) {

        return array($userId, $address, $state, $method, $date, $trackNo, $total) ;

      }
    }
    else {
      return -1 ;
    }

  }

}
?>
