<?php

session_start() ;

if (isset($_GET["confirm"]) && ($_GET["confirm"] == true)) {

  include "../php/bookpurchaseactions.php" ;

  //add the purchase to the cart
  $shipType = $_GET["shipping"] ;
  $address = $_GET["address"] ;
  $city = $_GET["city"] ;
  $state = $_GET["state"] ;
  $cardName = $_GET["cardname"] ;
  $cardNum = $_GET["ccn"] ;
  $cardCode = $_GET["ccv"] ;
  $expirationDate = $_GET["expd"] ;
  $total = $_GET["total"] ;

  if (addTransaction($address.", ".$city, $state, $shipType, $total) == 1) {
    echo "Transaction Successful." ;
    //make a page showing shipping and stuff, redirect to it
    header("Location: orders.php") ;

  }


}

else if (
  isset($_GET["shipping"]) && isset($_GET["city"]) && isset($_GET["address"]) && isset($_GET["state"])
  && isset($_GET["cardname"]) && isset($_GET["ccn"]) && isset($_GET["ccv"]) && isset($_GET["expd"])) {

    $shipType = $_GET["shipping"] ;
    $address = $_GET["address"] ;
    $city = $_GET["city"] ;
    $state = $_GET["state"] ;
    $cardName = $_GET["cardname"] ;
    $cardNum = $_GET["ccn"] ;
    $cardCode = $_GET["ccv"] ;
    $expirationDate = $_GET["expd"] ;

    include "../php/bookpurchaseactions.php" ;

    $userId = $_SESSION["user_id"] ;

    list($bookIds, $isbns, $bookNames, $authorNames, $publisherNames, $editions, $thumbnails, $prices, $maxes, $quantities) = getCartItems($userId) ;

    $total = 0 ;
    for($i = 0 ; $i < count($prices) ; $i++) {
      $total += $prices[$i]*$quantities[$i] ;
    }
    if ($shipType == "ground") {
      $total += 9.99 ;
    }
    else {
      $total += 19.99 ;
    }

?>
<html>
	<head>
		<title>BookBoys - Checkout</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-manager.css">
    <link rel="stylesheet" href="../stylesheets/style-nav.css">
    <link rel="stylesheet" href="../stylesheets/style-customer.css">
	</head>
	<body>

    <?php include "../nav/navbar.php" ; ?>

    <div class="checkout-div">
      <p class="checkout-text" id="title">Confirm Info</p>
      <p class="checkout-text">Shipping Info</p>
      <p class="checkout-text"><strong>Shipping Type:</strong> <?php echo $shipType ; ?></p>
      <p class="checkout-text"><strong>Address:</strong> <?php echo $address ; ?></p>
      <p class="checkout-text"><strong>City:</strong> <?php echo $city ; ?></p>
      <p class="checkout-text"><strong>State:</strong> <?php echo $state ; ?></p>
      <p class="checkout-text">Payment Info</p>
      <p class="checkout-text"><strong>Name:</strong> <?php echo $cardName ; ?></p>
      <p class="checkout-text"><strong>Card Number:</strong> <?php echo $cardNum ; ?></p>
      <p class="checkout-text"><strong>CCV:</strong> <?php echo $cardCode ; ?></p>
      <p class="checkout-text"><strong>Expiration Date:</strong> <?php echo $expirationDate ; ?></p>
      <p class="checkout-text"><strong>Total:</strong> <?php echo "$".$total ; ?></p>
    </div>

    <form method="get">

      <input type="hidden" name="shipping" value="<?php echo $shipType ; ?>">
      <input type="hidden" name="city" value="<?php echo $city ; ?>">
      <input type="hidden" name="address" value="<?php echo $address ; ?>">
      <input type="hidden" name="state" value="<?php echo $state ; ?>">
      <input type="hidden" name="cardname" value="<?php echo $cardName ; ?>">
      <input type="hidden" name="ccn" value="<?php echo $cardNum ; ?>">
      <input type="hidden" name="ccv" value="<?php echo $cardCode ; ?>">
      <input type="hidden" name="expd" value="<?php echo $expirationDate ; ?>">
      <input type="hidden" name="total" value="<?php echo $total ; ?>">
      <input type="hidden" name="confirm" value="true">

      <button type="submit">Confirm</button>

    </form>

	</body>
</html>
<?php
}
else {
?>
<html>
	<head>
		<title>BookBoys - Checkout</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-manager.css">
    <link rel="stylesheet" href="../stylesheets/style-nav.css">
    <link rel="stylesheet" href="../stylesheets/style-customer.css">
	</head>
	<body>

    <?php include "../nav/navbar.php" ; ?>

    <div class="checkout-div">
      <p class="checkout-text" id="title">You did not supply necessary information</p>
    </div>

	</body>
</html>
<?php
}
?>
