<?php

include "../php/bookactions.php" ;

session_start() ;

/*
This file is responsible for listing all books, and letting the manager search
through them.

From here, the manager can buy books to add to the stock, and they can also
modify and delete books that are in the database.

Plan is to use get parameters to control content of page
there will be either a page selection, a search, or a both
*/

$pageSize = 10 ;

//get stock information about books that are currently below threshold only
list($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices, $sellPrices, $quantities, $thresholds) = getBelowThreshold() ;

$message = "" ;

if ($bookIds == null) {
  $message = "There are currently no understocked books." ;
}
else if (count($bookIds) == 0) {
  $message = "There are currently no understocked books." ;
}
else {
  $message = "Books that are understocked: " ;
}


//assume it starts at 0
$startingIndex = 0 ;

if(isset($_GET["page"])) {
  $page = $_GET["page"] ;
  $startingIndex = ($page-1)*$pageSize ;
}
else {
  $page = 1 ;
}

//also need number of pages, for page selection
if ($bookIds != null) {
  $numberOfPages = floor(count($bookIds)/$pageSize) ;
  if ($numberOfPages != count($bookIds)/$pageSize) {
    $numberOfPages = $numberOfPages+1 ;
  }
}
else {
  $numberOfPages = 0 ;
}

  //creating divs with range
  $bookHtml = '<div class="books-list" id="book-list-container">' ;
  for($i = $startingIndex ; (($i < $pageSize*$page) && (($bookIds != null) && $i < count($bookIds))) ; $i++) {
    $bookHtml = $bookHtml.'<div class="book-item">' ;
    //add thumbnail
    $bookHtml = $bookHtml.'<img src="'.$thumbnails[$i].'" class="book-thumbnail" id="'.$bookIds[$i].'">' ;
    //start inner div that contains text
    $bookHtml = $bookHtml.'<div class="book-inner-div" id="text-info">' ;
    $bookHtml = $bookHtml.'<a href="/bookboys/book.php?book='.$bookIds[$i].'" class="book-inner-text" id="book-title">'.$bookNames[$i].'</a> ' ; //this will eventually be a link to the book page where the actual managing takes place
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-author">by. '.$authorNames[$i].'</p> ' ;
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-publisher">Publisher: '.$publisherNames[$i].'</p> ' ;
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-edition">Edition: '.$editions[$i].'</p> ' ;
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-isbn">ISBN: '.$isbns[$i].'</p>' ;
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-price">Publisher Price: $'.$prices[$i].'</p>' ;
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-sell-price">Selling Price: $'.$sellPrices[$i].'</p>' ;
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-quantity-threshold">'.$quantities[$i].' / '.$thresholds[$i].' in stock</p>' ;
    $bookHtml = $bookHtml.'</div>' ;
    $bookHtml = $bookHtml.'</div>' ;
  }
  $bookHtml = $bookHtml."</div>" ;

  //making page links
  $pageHtml = '' ;
  for ($i = 1 ; $i <= $numberOfPages ; $i++) {
    $pageHtml = $pageHtml.'<a class="page-number" href="?page='.$i.'">'.$i.'</a>' ;
  }




?>
<html>
	<head>
		<title>BookBoys - Manage Stock</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-manager.css">
	</head>
	<body>

    <?php include "../nav/navbar.php" ?>

    <p class="inventory-text" id="header">Inventory</p>

    <div class="inventory" id="inventory-header-container">
      <p class="inventory-text"><?php echo $message ; ?></p>
    </div>

      <div class="books-list" id="book-list-container">
        <div class="page-container">
          <?php echo $pageHtml ; ?>
        </div>
        <div id="book-items-container">
          <?php echo $bookHtml ; ?>
        </div>
        <div class="page-container">
          <?php echo $pageHtml ; ?>
        </div>
      </div>

	</body>
</html>
