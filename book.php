<?php
/*
* This is a page for a particualar book, for users they can buy the book here
* managers will be able to see a button to bring them to a menu to edit the book's info
* managers can also buy stock for the store from this menu without going to the above described menu
*
*/

session_start() ;

include "php/bookactions.php" ;
include "php/bookpurchaseactions.php" ;

if(isset($_GET["book"])) {

  $buyError = "" ;
  $stockError = "" ;


  $bookId = $_GET["book"] ;

  list($bookId, $isbn, $bookName, $authorName, $publisherName, $edition, $thumbnail, $price) = getBookInfo($bookId) ;
  list($stockId, $quantity, $threshold, $salePrice) = getBookStockInfo($bookId) ;


  if (isset($_GET["amount"]) && ($_GET["amount"] != "")) { //maybe check for negatives too
    $amount = $_GET["amount"] ;

    $return = updateStock($bookId, $amount, $price) ;

    $quantity = $quantity+$amount ;

    if($return == 1) {
      $buyError = "Successfully purchased stock." ;
    }
    else {
      $buyError = "There was a problem purchasing stock for the store." ;
    }
  }
  //updating threshold/selling price
  else if (isset($_GET["threshold"]) && isset($_GET["sale-price"]) && isset($_GET["stock-id"])) { //check for empty entries as well
    $threshold = $_GET["threshold"] ;
    $sellPrice = $_GET["sale-price"] ;
    $stockId = $_GET["stock-id"] ;

    $stockUpdate = editStockInfo($stockId, $threshold, $sellPrice) ;

    if ($stockUpdate == 1) {
      $stockError = "Successfully updated stocking information." ;
    }
   else {
     $stockError = "There was a problem updating stock information." ;
   }
  }

  list($stockId, $quantity, $threshold, $salePrice) = getBookStockInfo($bookId) ;

  if(isset($_SESSION["is_manager"]) && ($_SESSION["is_manager"] == true)) {
    $bookBuyHtml = '<p class="book-info-text" id="book-price">$'.$price.' per copy</p>' ;
    $bookBuyHtml = $bookBuyHtml.'<form method="get">' ;
    $bookBuyHtml = $bookBuyHtml.'<input type="hidden" name="book" value="'.$bookId.'">' ;
    $bookBuyHtml = $bookBuyHtml.'<input type="number" class="buy-quantity" min=1 placeholder="#" name="amount">' ;
    $bookBuyHtml = $bookBuyHtml.'<input type="submit" class="buy-submit" value="Buy for Store">' ;
    if ($stockId == 0) {
      $bookBuyHtml = $bookBuyHtml.'<p class="buy-text">Book Boys does not currently carry this book.</p>' ;
      $stockForm = "" ;
    }
    else {
      $bookBuyHtml = $bookBuyHtml.'<p class="buy-text">'.$quantity.' / '.$threshold.' in stock.</p>' ;
      //also put form for changing stock information (getting a bit unruly)
      $stockForm = '<div id="book-stock-div">' ;
      $stockForm = $stockForm.'<form method="get">' ;
      $stockForm = $stockForm.'<input type="hidden" name="book" value="'.$bookId.'">' ;
      $stockForm = $stockForm.'<input type="hidden" name="stock-id" value="'.$stockId.'">' ;
      $stockForm = $stockForm.'<span class="stock-label">Threshold:</span><input type="text" class="stock-threshold" name="threshold" value="'.$threshold.'"><br>' ;
      $stockForm = $stockForm.'<span class="stock-label">Selling Price:</span><input type="text" class="stock-sell-price" name="sale-price" value="'.$salePrice.'"><br>' ;
      $stockForm = $stockForm.'<input type="submit" class="stock-submit" value="Update Stock Info">' ;
      $stockForm = $stockForm.'</div>' ;
      $stockForm = $stockForm.'</form>' ;
    }
    $bookBuyHtml = $bookBuyHtml.'<p class="book-buy-error">'.$buyError.'</p>' ;
    $bookBuyHtml = $bookBuyHtml.'</form>' ;
    $bookBuyHtml = $bookBuyHtml.'<a href="/bookboys/manager/edit_book.php?book='.$bookId.'" class="book-edit-link">Edit Book</a>' ;
    $bookBuyHtml = $bookBuyHtml.$stockForm ;
    $bookBuyHtml = $bookBuyHtml.'<p class="book-buy-error">'.$stockError.'</p>' ;

  }
  //not a manager
  else {
    $buyError = '' ;
    //user has already submitted to buy a book
    if (isset($_GET["buy-amount"]) && isset($_GET["buy"]) && ($_GET["buy-amount"] > 0)) {
      $amount = $_GET["buy-amount"] ;
      $userId = $_SESSION["user_id"] ;

      $addCartResult = addCart($userId, $bookId, $amount) ;

      if ($addCartResult == 0) {
        $buyError = 'There were not enough books in stock.' ;
      }
      else {
        $buyError = 'Successfully added to cart.' ;
      }


    }
    $bookBuyHtml = '<div id="book-cust-div">' ;
    $bookBuyHtml = $bookBuyHtml.'<p class="book-info-text" id="book-price">$'.$salePrice.'</p>' ;
    $bookBuyHtml = $bookBuyHtml.'<p class="book-info-text" id="book-quantity">'.$quantity.' left in stock</p>' ;
    $bookBuyHtml = $bookBuyHtml.'<form method="GET">' ;
    $bookBuyHtml = $bookBuyHtml.'<input type="hidden" name="book" value="'.$bookId.'">' ;
    $bookBuyHtml = $bookBuyHtml.'<input type="number" class="buy-quantity" placeholder="#" min=1 max='.$quantity.' name="buy-amount">' ;
    $bookBuyHtml = $bookBuyHtml.'<input type="submit" class="buy-submit" value="Update Cart" name="buy">' ;
    $bookBuyHtml = $bookBuyHtml.'</form>' ;
    $bookBuyHtml = $bookBuyHtml.'<p class="book-buy-error">'.$buyError.'</p>' ;
    $bookBuyHtml = $bookBuyHtml.'</div>' ;

  }

?>
<html>
	<head>
		<title>BookBoys - <?php echo $bookName ; ?></title>
    <link rel="stylesheet" href="stylesheets/style-main.css">
    <link rel="stylesheet" href="stylesheets/style-manager.css">
    <link rel="stylesheet" href="stylesheets/style-nav.css">
	</head>
	<body>

    <?php include "nav/navbar.php" ?>

    <div class="book-container" id="book-container-main">
      <img src="<?php echo $thumbnail ; ?>" class="book-cover">

      <div id="book-info-box">
        <p class="book-info-text" id="book-title"><?php echo $bookName ; ?></p>
        <p class="book-info-text" id="book-author">by. <?php echo $authorName ; ?></p>
        <p class="book-info-text" id="book-publisher">Publisher: <?php echo $publisherName ; ?></p>
        <p class="book-info-text" id="book-edition">Edition: <?php echo $edition ; ?></p>
        <p class="book-info-text" id="book-isbn">ISBN: <?php echo $isbn ; ?></p>
      </div>

      <div id="book-buy-box">
        <?php echo $bookBuyHtml ; ?>
      </div>

    </div>

	</body>
</html>
<?php
}
else {

  echo "There was a problem." ;

}

?>
