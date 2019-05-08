<?php
/*
* This is where the manager edits the actual info for a particular book
* they can delete it here as well
*/

session_start() ;

include "../php/bookactions.php" ;

/*
TODO: Use a $_GET super variable for editing the info of a particular book,
this will preload the actual info into appropriate boxes

Obviouslly need to add more functions to book options for the updating actions
Also a new function for deleting as well

The book.php page will have a link to this for managers
NOTE: need to make sure they are actually a manager $_SESSION["is_manager"] = true
*/

if(isset($_SESSION["is_manager"]) && ($_SESSION["is_manager"] == true) && isset($_GET["book"])) {
  $bookId = $_GET["book"] ;

  //get info about book to fill out the form ahead of time so itll be like editing
  list($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) = getBookInfo($bookId) ;
  $error = "" ;

  //if we have submitted the form to update info...
  if(isset($_GET["book-name"]) && $_GET["book-name"] != "" &&
  isset($_GET["book-author"]) && $_GET["book-author"] != "" &&
  isset($_GET["book-publisher"]) && $_GET["book-publisher"] != "" &&
  isset($_GET["book-isbn"]) && $_GET["book-isbn"] != "" &&
  isset($_GET["book-edition"]) && $_GET["book-edition"] != "" &&
  isset($_GET["book-thumbnail"]) && $_GET["book-thumbnail"] != "" &&
  isset($_GET["book-price"]) && $_GET["book-price"] != "" ) {

    //we will simplify the variables
    $bookName = $_GET["book-name"] ;
    $authorName = $_GET["book-author"] ;
    $publisherName = $_GET["book-publisher"] ;
    $isbn = $_GET["book-isbn"] ;
    $edition = $_GET["book-edition"] ;
    $thumbnail = $_GET["book-thumbnail"] ;
    $price = $_GET["book-price"] ;

    //call our update for the book
    $returnValue = updateBookInfo($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) ;

    if ($returnValue == 1) {
      $error = "Successfully updated book info." ;
    }
    else if ($returnValue == 0) {
      $error = "The book name or ISBN is already in use." ;
    }
    else {
      $error = "There was a problem updating book info." ;
    }

  }
  else if(isset($_GET["deleteBook"]) && ($_GET["deleteBook"] == 1) && isset($_GET["book"])) {
    $bookId = $_GET["book"] ;

    $return = deleteBook($bookId) ;

    if ($return == 1) {
      header('Location: /bookboys/manager/book_list.php') ;
      die() ;
    }
    else {
      //don't really know what to do
    }
  }

  ?>
  <html>
  	<head>
  		<title>BookBoys - Edit Book - <?php echo $bookName ; ?></title>
      <link rel="stylesheet" href="../stylesheets/style-main.css">
      <link rel="stylesheet" href="../stylesheets/style-manager.css">
  	</head>
  	<body>

      <?php include "../nav/navbar.php" ?>

      <div class="edit-book" id="edit-book-container">
        <form method="get">
          <p class="edit-book-text" id="header">Edit book:</p>
  				<p class="edit-book-text">Book Name:</p>
          <input class="edit-book-box" id="book-name" autocomplete="off"  placeholder="Book Name" type="text" name="book-name" value="<?php echo $bookName ; ?>">
          <br>
  				<p class="edit-book-text">Book Author:</p>
          <input class="edit-book-box" id="book-author" autocomplete="off" placeholder="Book Author" type="text" name="book-author" value="<?php echo $authorName ; ?>">
          <br>
  				<p class="edit-book-text">Book Publisher:</p>
          <input class="edit-book-box" id="book-publisher" autocomplete="off" placeholder="Book Publisher" type="text" name="book-publisher" value="<?php echo $publisherName ; ?>">
          <br>
  				<p class="edit-book-text">ISBN Number:</p>
          <input class="edit-book-box" id="book-isbn" autocomplete="off" placeholder="Book ISBN" type="text" name="book-isbn" value="<?php echo $isbn ; ?>">
          <br>
  				<p class="edit-book-text">Book Edition:</p>
          <input class="edit-book-box" id="book-edition" autocomplete="off" placeholder="Book Edition" type="text" name="book-edition" value="<?php echo $edition ; ?>">
          <br>
  				<p class="edit-book-text">Book Thumbnail (pic):</p>
          <input class="edit-book-box" id="book-thumbnail" autocomplete="off" placeholder="Book Thumbnail" type="text" name="book-thumbnail" value="<?php echo $thumbnail ; ?>">
          <br>
  				<p class="edit-book-text">Book Price ($USD):</p>
          <input class="edit-book-box" id="book-price" autocomplete="off" placeholder="Book Price" type="text" name="book-price" value="<?php echo $price ; ?>">
          <input type="hidden" name="book" value="<?php echo $bookId ;?>">
          <br>
          <input class="edit-book-btn" id="book-submit" autocomplete="off" value="Update Info" type="submit" name="submit">
          <br>
          <a class="edit-book-text" href="?book=<?php echo $bookId ; ?>&deleteBook=1">Delete Book</a>
        </form>
  			<p class="edit-book-text" id="error"><?php echo $error ; ?></p>

      </div>

  	</body>
  </html>
 <?php
}
//either book not set or osmething else
else {
  echo "Something went wrong" ;
}

 ?>
