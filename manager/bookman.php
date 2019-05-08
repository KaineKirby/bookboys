<?php

session_start() ;

include "../php/bookactions.php" ;

$authorList = getAuthors() ;
$publisherList = getPublishers() ;

$error = "" ;

if(isset($_GET["book-name"]) && $_GET["book-name"] != "" &&
isset($_GET["book-author"]) && $_GET["book-author"] != "" &&
isset($_GET["book-publisher"]) && $_GET["book-publisher"] != "" &&
isset($_GET["book-isbn"]) && $_GET["book-isbn"] != "" &&
isset($_GET["book-edition"]) && $_GET["book-edition"] != "" &&
isset($_GET["book-thumbnail"]) && $_GET["book-thumbnail"] != "" &&
isset($_GET["book-price"]) && $_GET["book-price"] != "" ) {
	//if we have submitted and none of the boxes are empty

	//we will simplify the variables
	$bookName = $_GET["book-name"] ;
	$bookAuthor = $_GET["book-author"] ;
	$bookPublisher = $_GET["book-publisher"] ;
	$bookIsbn = $_GET["book-isbn"] ;
	$bookEdition = $_GET["book-edition"] ;
	$bookThumbnail = $_GET["book-thumbnail"] ;
	$bookPrice = $_GET["book-price"] ;

	$result = addBook($bookName, $bookAuthor, $bookPublisher, $bookIsbn, $bookEdition, $bookThumbnail, $bookPrice) ;

	if($result == 1) {
		$error = "Book was successfully added to the records. Go to inventory to purchase it for store." ;
	}
	else if ($result == 0) {
		$error = "We already have a book with either that name, ISBN, or both in our records." ;
	}
	else {
		$error = "A mysterious error occured (SQL Statements)." ;
	}

}

?>
<html>
	<head>
		<title>BookBoys - Add Book</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-manager.css">
	</head>
	<body>

    <?php include "../nav/navbar.php" ?>

    <div class="add-book" id="add-book-container">
      <form method="get">
        <p class="add-book-text" id="header">Add a new book:</p>
				<p class="add-book-text">Book Name:</p>
        <input class="add-book-box" id="book-name" autocomplete="off"  placeholder="Book Name" type="text" name="book-name">
        <br>
				<p class="add-book-text">Book Author:</p>
        <input class="add-book-box" id="book-author" autocomplete="off" placeholder="Book Author" type="text" name="book-author">
        <br>
				<p class="add-book-text">Book Publisher:</p>
        <input class="add-book-box" id="book-publisher" autocomplete="off" placeholder="Book Publisher" type="text" name="book-publisher">
        <br>
				<p class="add-book-text">ISBN Number:</p>
        <input class="add-book-box" id="book-isbn" autocomplete="off" placeholder="Book ISBN" type="text" name="book-isbn">
        <br>
				<p class="add-book-text">Book Edition:</p>
        <input class="add-book-box" id="book-edition" autocomplete="off" placeholder="Book Edition" type="text" name="book-edition">
        <br>
				<p class="add-book-text">Book Thumbnail (pic):</p>
        <input class="add-book-box" id="book-thumbnail" autocomplete="off" placeholder="Book Thumbnail" type="text" name="book-thumbnail">
        <br>
				<p class="add-book-text">Book Price ($USD):</p>
        <input class="add-book-box" id="book-price" autocomplete="off" placeholder="Book Price" type="text" name="book-price">
        <br>
        <input class="add-book-btn" id="book-submit" autocomplete="off" value="Submit" type="submit" name="submit">
      </form>
			<p class="add-book-text" id="error"><?php echo $error ; ?></p>

    </div>

	</body>
</html>
