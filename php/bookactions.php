<?php

/*
* Adds book to database
* Checks to see if the author and publisher exist already, and if they do uses the appopriate one
* If not, it creates a new one of that name (respectively)
* Possible return values
* 1. 1 - successfully added
* 2. -1 - There was some sort of error
* 3. 0 - duplicate book title
*/
function addBook($bookName, $bookAuthor, $bookPublisher, $isbn, $bookEdition, $bookThumbnail, $bookPrice) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //if there was a connection error
  if ($conn->connect_error) {
    return -1 ;
  }
  //there wasnt a connection error
  else {

    //also need to make sure that the book name or ISBN isn't already in use
    $stmt = $conn->prepare("SELECT book_id FROM books WHERE book_name=? OR isbn=?") ;

    $stmt->bind_param("si", $bookName, $isbn) ;
    //if we can execute the statement
    if($stmt->execute()) {
      //and if there is a result (either or exists)
      if($stmt->fetch()) {
        return 0 ;
      }
    }
    else {
      return -1 ;
    }

    //we are now guarenteed no duplicates

    //need to check for both the author and the publisher
    //if they are in the database then i need to just use the ids already existing, if not i create them

    $stmt = $conn->prepare("SELECT publisher_id FROM publishers WHERE publisher_name=?") ;

    $stmt->bind_param("s", $bookPublisher) ;

    //if we can execute the statement
    if($stmt->execute()) {
      $stmt->bind_result($publisherId) ;

      //and if there is a result
      if($stmt->fetch()) {
        //should now have publisherId available
      }
      //no results
      else {
        //make a new publisher entry
        $stmt2 = $conn->prepare("INSERT INTO publishers (publisher_name) VALUES (?)") ;
        $stmt2->bind_param("s", $bookPublisher) ;
        if ($stmt2->execute()) {
          //now we need to get the id for the recently added publisher

          $stmt3 = $conn->prepare("SELECT publisher_id FROM publishers WHERE publisher_name=?") ;
          $stmt3->bind_param("s", $bookPublisher) ;
          $stmt3->execute() ;
          $stmt3->bind_result($publisherId) ;
          $stmt3->fetch() ;
          //should have it now
          if(!isset($publisherId)) {
            return -1 ; //just in case we dont
          }
          //turned out to be necessary to clear the queue
          while ($stmt3->fetch()) {

          }


        }
        else {
          return -1 ;
        }
      }
    }
    else {
      return -1 ;
    }

    while($stmt->fetch()) {
      //clear out the detritus
    }

    $stmt = $conn->prepare("SELECT author_id FROM authors WHERE author_name=?") ;

    $stmt->bind_param("s", $bookAuthor) ;

    //if we can execute the statement
    if($stmt->execute()) {
      $stmt->bind_result($authorId) ;

      //and if there is a result
      if($stmt->fetch()) {
        //should now have publisherId available
      }
      //no results
      else {
        //make a new author entry
        $stmt2 = $conn->prepare("INSERT INTO authors (author_name) VALUES (?)") ;
        $stmt2->bind_param("s", $bookAuthor) ;
        if ($stmt2->execute()) {
          //inserted okay
          $stmt3 = $conn->prepare("SELECT author_id FROM authors WHERE author_name=?") ;
          $stmt3->bind_param("s", $bookAuthor) ;
          $stmt3->execute() ;
          $stmt3->bind_result($authorId) ;
          $stmt3->fetch() ;
          //should have it now
          if(!isset($authorId)) {
            return -1 ; //just in case we dont
          }

          //turned out to be necessary to clear the queue
          while ($stmt3->fetch()) {

          }
        }
        else {
          return -1 ;
        }
      }
    }
    else {
      return -1 ;
    }

    while($stmt->fetch()) {
      //clear out the detritus
    }

    //so we should now have authorId and publisherId
    $stmt = $conn->prepare("INSERT INTO books (isbn, book_name, author_id, publisher_id, edition, book_thumbnail, book_price) VALUES (?, ?, ?, ?, ?, ?, ?)") ;

    $stmt->bind_param("isiiisd", $isbn, $bookName, $authorId, $publisherId, $bookEdition, $bookThumbnail, $bookPrice) ;


    if ($stmt->execute()) {
      return 1 ;
    }
    else {
      return -1 ;
    }

  }

}
//retrieves all author names (maybe ids havent thought about it yet)
function getAuthors() {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT author_name, author_id FROM authors") ;

  if($stmt->execute()) {
    $stmt->bind_result($authorName, $authorId) ;
    $index = 0 ;
    while($stmt->fetch()) {
      $authors[$index] = $authorName ;
      $index++ ;
    }

  }

  return $authors ;

}
//retrieves all publisher names (maybe ids havent thought about it yet)
function getPublishers() {

    include "vars.php" ;

    //creating an sql conntection
    $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

    $stmt = $conn->prepare("SELECT publisher_name, publisher_id FROM publishers") ;

    if($stmt->execute()) {
      $stmt->bind_result($publisherName, $publisherId) ;
      $index = 0 ;
      while($stmt->fetch()) {
        $publishers[$index] = $publisherName ;
        $index++ ;
      }

    }

    return $publishers ;

}
//returns all books for manager
function getBooks() {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, book_price FROM books, authors, publishers WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id") ;

  if($stmt->execute()) {
    $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;
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
      $index++ ;
    }

  }

  if (isset($bookIds)) {
    return array($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices) ;
  }
  else {
    return null ;
  }

}
function getBooksCust() {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, sale_price FROM books, authors, publishers, stock WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id AND stock.quantity>0") ;

  if($stmt->execute()) {
    $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;
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
      $index++ ;
    }

  }

  if (isset($bookIds)) {
    return array($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices) ;
  }
  else {
    return null ;
  }

}

function bookSearch($query, $searchType) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  if ($searchType == "author") {
    $stmt = $conn->prepare("SELECT book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, book_price FROM books, authors, publishers WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND author_name LIKE '%$query%'") ;
  }
  else if($searchType == "publisher") {
    $stmt = $conn->prepare("SELECT book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, book_price FROM books, authors, publishers WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND publisher_name LIKE '%$query%'") ;
  }
  else if ($searchType == "isbn"){
    $stmt = $conn->prepare("SELECT book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, book_price FROM books, authors, publishers WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND isbn='$query'") ;
  }
  else {
    $stmt = $conn->prepare("SELECT book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, book_price FROM books, authors, publishers WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND book_name LIKE '%$query%'") ;
  }

  if($stmt->execute()) {
    $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;
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
      $index++ ;
    }

  }

  if (isset($bookIds)) {
    return array($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices) ;
  }
  else {
    return null ;
  }


}
function bookSearchCust($query, $searchType) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  if ($searchType == "author") {
    $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, sale_price FROM books, authors, publishers, stock WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id AND stock.quantity>0 AND author_name LIKE '%$query%'") ;
  }
  else if($searchType == "publisher") {
    $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, sale_price FROM books, authors, publishers, stock WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id AND stock.quantity>0 AND publisher_name LIKE '%$query%'") ;
  }
  else if ($searchType == "isbn"){
    $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, sale_price FROM books, authors, publishers, stock WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id AND stock.quantity>0 AND isbn='$query'") ;
  }
  else {
    $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, sale_price FROM books, authors, publishers, stock WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id AND stock.quantity>0 AND book_name LIKE '%$query%'") ;
  }



  if($stmt->execute()) {
    $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;
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
      $index++ ;
    }

  }

  if (isset($bookIds)) {
    return array($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices) ;
  }
  else {
    return null ;
  }


}

//get book info given a book id
function getBookInfo($bookId) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, book_price FROM books, authors, publishers WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND book_id=$bookId") ;

  if($stmt->execute()) {
    $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;
    $stmt->fetch() ;

  }

  return array($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;

}
//update book info for a book given a book id and info
function updateBookInfo($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  //also need to make sure that the book name or ISBN isn't already in use
  $stmt = $conn->prepare("SELECT book_id FROM books WHERE (book_name=? OR isbn=?) AND NOT book_id=$bookId") ;

  $stmt->bind_param("si", $bookName, $isbn) ;
  //if we can execute the statement
  if($stmt->execute()) {
    //and if there is a result (either or exists)
    if($stmt->fetch()) {
      return 0 ;
    }
  }
  else {
    return -1 ;
  }

  while($stmt->fetch()) {

  }

  //find both the author and publisher id OR make new ones depending
  $authorId = updateAuthor($authorName) ;
  $publisherId = updatePublisher($publisherName) ;

  if(($authorId == -1) || ($publisherId == -1)) {
    return -1 ;
  }


  $stmt = $conn->prepare("UPDATE books SET book_name=?, edition=?, author_id=?, publisher_id=?, isbn=?, book_thumbnail=?, book_price=? WHERE book_id=$bookId") ;

  $stmt->bind_param("siiissd", $bookName, $edition, $authorId, $publisherId, $isbn, $thumbnail, $price) ;


  if($stmt->execute()) {

    return 1 ;

  }

  return -1 ;

}
//deletes a book given a book id
//1: successfully deleted
//0: not successful
//does not touch the author or publisher table
//dont plan to change that either
function deleteBook($bookId) {

    include "vars.php" ;

    //creating an sql conntection
    $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

    $stmt = $conn->prepare("DELETE FROM books WHERE book_id=$bookId") ;

    if($stmt->execute()) {
      return 1 ;
    }
    else {
      return 0 ;
    }

}
//given an author name either return the author_id with that name
//or make a new one depending on if it exists or not
function updateAuthor($authorName) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT author_id FROM authors WHERE author_name='$authorName'") ;

  if($stmt->execute()) {
    $stmt->bind_result($authorId) ;
    if($stmt->fetch()) {
      //we now have access to the authorId
      return $authorId ;
    }
    else {
      //need to make a new author entry

      $stmt2 = $conn->prepare("INSERT INTO authors (author_name) VALUES (?)") ;
      $stmt2->bind_param("s", $authorName) ;

      if($stmt2->execute()) {
        //we were able to insert
        //find that just inserted author id
        $stmt3 = $conn->prepare("SELECT author_id FROM authors WHERE author_name='$authorName'") ;
        $stmt3->execute() ;
        $stmt3->bind_result($authorId) ;
        return $authorId ;
      }
      else {
        //there was an issue
        return -1 ;
      }
    }
  }

}
function updatePublisher($publisherName) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT publisher_id FROM publishers WHERE publisher_name='$publisherName'") ;

  if($stmt->execute()) {
    $stmt->bind_result($publisherId) ;
    if($stmt->fetch()) {
      //we now have access to the authorId
      return $publisherId ;
    }
    else {
      //need to make a new author entry

      $stmt2 = $conn->prepare("INSERT INTO publishers (publisher_name) VALUES (?)") ;
      $stmt2->bind_param("s", $publisherName) ;

      if($stmt2->execute()) {
        //we were able to insert
        //find that just inserted author id
        $stmt3 = $conn->prepare("SELECT publisher_id FROM publishers WHERE publisher_name='$publisherName'") ;
        $stmt3->execute() ;
        $stmt3->bind_result($publisherId) ;
        return $publisherId ;
      }
      else {
        //there was an issue
        return -1 ;
      }
    }
  }
}
//this will get the stock information for a book, if it exists
//returns a -1 if there was an error
//returns a 0 if the book isn't in stock
function getBookStockInfo($bookId) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT stock_id, quantity, threshold, sale_price FROM books, stock WHERE books.book_id=stock.book_id AND stock.book_id=$bookId") ;

  if($stmt->execute()) {
    $stmt->bind_result($stockId, $quantity, $threshold, $salePrice) ;
    if($stmt->fetch()) {
      return array($stockId, $quantity, $threshold, $salePrice) ;
    }
    else {
      return array(0, 0, 0, 0) ;
    }

  }
  else {
    return array(-1, -1, -1, -1) ;
  }

}
//needs to check to see if book already is in stock
function updateStock($bookId, $quantity, $price) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT stock_id FROM books, stock WHERE books.book_id=stock.book_id AND stock.book_id=$bookId") ;

  if($stmt->execute()) {
    $stmt->bind_result($stockId) ;
    if($stmt->fetch()) {
      //its already in the stock so we just update it
    }
    else {
      //we need to make a new one
      $stockId = newStock($bookId, $quantity, $price) ;
      if ($stockId == -1) {
        return -1 ;
      }

    }

    while($stmt->fetch()) {

    }

    //either way we should have a viable stockId
    $stmt = $conn->prepare("UPDATE stock SET quantity=quantity+$quantity, sale_price=$price WHERE stock_id=$stockId") ;

    if($stmt->execute()) {
      return 1 ;
    }
    else {
      return -1 ;
    }
  }

}
//given a book id, makes a new entry for the stock and returns the stock_id for the new entry
function newStock($bookId, $quantity, $bookPrice) {

  //assumes the threshold starts at 5 and price is $5 more than the price of book (bleh)
  $initalThreshold = 5 ;
  $price = $bookPrice+5 ;

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("INSERT INTO stock (book_id, quantity, threshold, sale_price) VALUES (?, ?, ?, ?)") ;
  //new stock never gets called out of context of an update
  $quantity = 0 ;

  $stmt->bind_param("iiid", $bookId, $quantity, $initalThreshold, $price) ;

  if($stmt->execute()) {

    while($stmt->fetch()) {

    }

    $stmt2 = $conn->prepare("SELECT stock_id FROM stock, books WHERE books.book_id=stock.book_id AND stock.book_id='$bookId'") ;
    $stmt2->bind_result($stockId) ;
    if($stmt2->execute()) {
      $stmt2->fetch() ;
      $id = $stockId ;
      if(isset($id)) {
        return $stockId ;
      }
      else {
        return -1 ;
      }
    }

  }
  else {
    return -1 ;
  }

}
//different errors:
//1: success
//0: failure (random error)
function editStockInfo($stockId, $threshold, $salePrice) {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("UPDATE stock SET threshold=?, sale_price=? WHERE stock_id=$stockId") ;

  $stmt->bind_param("id", $threshold, $salePrice) ;

  if($stmt->execute()) {
    return 1 ;
  }
  else {
    return 0 ;
  }

}

//returns all books below threshold
function getBelowThreshold() {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, book_price, quantity, threshold, sale_price FROM books, authors, publishers, stock WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id AND stock.quantity<stock.threshold") ;


  if($stmt->execute()) {
    $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price, $quantity, $threshold, $salePrice) ;
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
      $sellPrices[$index] = $salePrice ;
      $quantities[$index] = $quantity ;
      $thresholds[$index] = $threshold ;
      $index++ ;
    }

  }

  if (isset($bookIds)) {
    return array($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices, $sellPrices, $quantities, $thresholds) ;
  }
  else {
    return null ;
  }

}
//get the books in order of amount sold
function getBooksCustBySold() {

  include "vars.php" ;

  //creating an sql conntection
  $conn = new mysqli("localhost", $dbuser, $dbpass, $db) ;

  $stmt = $conn->prepare("SELECT books.book_id, isbn, book_name, author_name, publisher_name, edition, book_thumbnail, sale_price FROM books, authors, publishers, stock WHERE books.author_id=authors.author_id AND books.publisher_id=publishers.publisher_id AND books.book_id=stock.book_id AND NOT amount_sold=0 ORDER BY amount_sold DESC") ;

  if($stmt->execute()) {
    $stmt->bind_result($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;
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
      $index++ ;
    }

  }

  if (isset($bookIds)) {
    return array($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices) ;
  }
  else {
    return null ;
  }

}


?>
