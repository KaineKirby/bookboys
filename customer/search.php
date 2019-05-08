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

//we are currently in a search
if (isset($_GET["query"]) && isset($_GET["searchby"])) {
  $query = $_GET["query"] ;
  $searchType = $_GET["searchby"] ;
  //call the function

  list($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices) = bookSearchCust($query, $searchType) ;

  //figre out current page
  $startingIndex = 0 ;

  if(isset($_GET["page"])) {
    $page = $_GET["page"] ;
    $startingIndex = ($page-1)*$pageSize ;
  }
  else {
    $page = 1 ;
  }

  if ($bookIds != null) {
    //also need number of pages, for page selection
    $numberOfPages = floor(count($bookIds)/$pageSize) ;
    if ($numberOfPages != count($bookIds)/$pageSize) {
      $numberOfPages = $numberOfPages+1 ;
    }
  }
  else {
    $numberOfPages = 0 ;
  }


  //do code here to get proper range (page numbers with ?get)

  //creating divs with range
  $bookHtml = '' ;
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
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-price">$'.$prices[$i].'</p>' ;
    $bookHtml = $bookHtml.'</div>' ;
    $bookHtml = $bookHtml.'</div>' ;
  }


  //making page links
  $pageHtml = '' ;
  for ($i = 1 ; $i <= $numberOfPages ; $i++) {
    $pageHtml = $pageHtml.'<a class="page-number" href="?query='.$query.'&searchby='.$searchType.'&page='.$i.'">'.$i.'</a>' ;
  }


}
//we are currently not in a search
else {

  //by default all books are listed, and we show the first 20, then pages increase beyond that
  list($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices) = getBooksCust() ;

  //we now have all information about all books, we need to put them in a div to be transplanted into the html
  //we base the index on the current ?get, if nothing we show the first 10

  //assume it starts at 0
  $startingIndex = 0 ;

  if(isset($_GET["page"])) {
    $page = $_GET["page"] ;
    $startingIndex = ($page-1)*$pageSize ;
  }
  else {
    $page = 1 ;
  }

  if ($bookIds != null) {
    //also need number of pages, for page selection
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
  for($i = $startingIndex ; (($i < $pageSize*$page) && ($i < count($bookIds))) ; $i++) {
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
    $bookHtml = $bookHtml.'<p class="book-inner-text" id="book-price">$'.$prices[$i].'</p>' ;
    $bookHtml = $bookHtml.'</div>' ;
    $bookHtml = $bookHtml.'</div>' ;
  }
  $bookHtml = $bookHtml."</div>" ;

  //making page links
  $pageHtml = '' ;
  for ($i = 1 ; $i <= $numberOfPages ; $i++) {
    $pageHtml = $pageHtml.'<a class="page-number" href="?page='.$i.'">'.$i.'</a>' ;
  }


}

?>
<html>
	<head>
		<title>BookBoys - Search Store</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-manager.css">
	</head>
	<body>

    <?php include "../nav/navbar.php" ?>

    <div class="search" id="search-container">
      <p class="search-text" id="header">Search Store:</p>
      <div class="search" id="search-form-container">
        <form method="get">
  				<p class="search-text" id="search-label">Search:</p>
          <input class="search-text-box" id="search-box" autocomplete="off" placeholder="Search for books" type="text" name="query">
          <select name="searchby" class="search-text-box" id="selection">
            <option value="name">Name</option>
            <option value="author">Author</option>
            <option value="publisher">Publisher</option>
            <option value="isbn">ISBN</option>
          </select>
          <input class="search-btn" id="search-submit" autocomplete="off" type="submit">
          <?php if(isset($query)){echo '<p class="search-text" id="user-query">Results for: "'.$query.'"' ;} ?>
        </div>
      </form>
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
