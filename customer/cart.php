<?php
//displays cart to user

session_start() ;

include "../php/bookpurchaseactions.php" ;

if (isset($_SESSION["user_id"])) {

  $userId = $_SESSION["user_id"] ;

  if (isset($_GET["remove"])) {

    $item = $_GET["remove"] ;

    updateCartExisting($userId, $item) ;

  }

  list($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices, $maxes, $quantities) = getCartItems($userId) ;

  if (($bookIds != null) && (count($bookIds) > 0)) {
    $completeTotal = 0 ;
    $cartHtml = "" ;

    for($i = 0 ; $i < count($bookIds) ; $i++) {
      $cartHtml = $cartHtml.'<div class="cart-item">' ;
      $cartHtml = $cartHtml.'<img src="'.$thumbnails[$i].'" class="book-cart-thumbnail" id="'.$bookIds[$i].'">' ;
      $cartHtml = $cartHtml.'<div class="inner-cart-item">' ;
      $cartHtml = $cartHtml.'<a href="/bookboys/book.php?book='.$bookIds[$i].'" class="cart-inner-text" id="cart-book-name">'.$bookNames[$i].'</a>' ;
      $cartHtml = $cartHtml.'<p class="cart-inner-text" id="cart-isbn">'.$isbns[$i].'</p>' ;
      $cartHtml = $cartHtml.'<p class="cart-inner-text" id="cart-author-name">'.$authorNames[$i].'</p>' ;
      //$cartHtml = $cartHtml.'<p class="cart-inner-text" id="cart-publisher-name">'.$publisherNames[$i].'</p>' ;
      //$cartHtml = $cartHtml.'<p class="cart-inner-text" id="cart-edition">'.$editions[$i].'</p>' ;
      $cartHtml = $cartHtml.'<p class="cart-inner-text" id="cart-price">$'.$prices[$i].'</p>' ;
      $cartHtml = $cartHtml.'<p class="cart-inner-text" id="cart-quantity">x'.$quantities[$i].' in cart</p>' ;
      $total[$i] = $prices[$i]*$quantities[$i] ;
      $completeTotal += $total[$i] ;
      $cartHtml = $cartHtml.'<p class = "cart-inner-text" id="cart-total">$'.$total[$i].' total</p>' ;

      //form part
      //just a delete button
      $cartHtml = $cartHtml.'<form method="get">' ;
      $cartHtml = $cartHtml.'<button class="cart-btn" value="'.$bookIds[$i].'" type="submit" name="remove">Remove</button>' ;
      $cartHtml = $cartHtml.'</div>' ;
      $cartHtml = $cartHtml.'</div>' ;

    }

    $cartHtml = $cartHtml.'<div class="cart-item">' ;
    $cartHtml = $cartHtml.'<p class="cart-inner-text" id="complete-total">Total: $'.$completeTotal.'</p>' ;
    $cartHtml = $cartHtml.'<a href="checkout.php" class="checkout-btn">Checkout</a>' ;
    $cartHtml = $cartHtml.'</div>' ;
  }
  else {
    $cartHtml = '<p class="cart-text">You currently have no items in your cart.</p>' ;
  }

}

?>
<html>
	<head>
		<title>BookBoys - View Cart</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-manager.css">
    <link rel="stylesheet" href="../stylesheets/style-nav.css">
    <link rel="stylesheet" href="../stylesheets/style-customer.css">
	</head>
	<body>

    <?php include "../nav/navbar.php" ; ?>

    <div class="cart-div" id="main">

      <p class="cart-text" id="header">Cart</p>

      <div class="cart-div" id="sub-div">

        <?php echo $cartHtml ; ?>

      </div>

    </div>

	</body>
</html>
