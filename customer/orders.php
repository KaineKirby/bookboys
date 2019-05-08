<?php

session_start() ;

include "../php/bookpurchaseactions.php" ;

list($ids, $addresses, $states, $methods, $dates, $trackNos, $totals) = getTransactions($_SESSION["user_id"]) ;

if (!isset($ids)) {

  $orderHtml = "<p class='order-text'>There are no previous orders.</p>" ;

}
else {

  $orderHtml = "" ;

  for ($i = 0 ; $i < count($ids) ; $i++) {
    $orderHtml = $orderHtml.'<div class="order-item">' ;
    $orderHtml = $orderHtml.'<a id="total" class="order-text" href="order.php?trans='.$ids[$i].'">$'.$totals[$i].'</a>' ;
    $orderHtml = $orderHtml.'<span id="address-label" class="order-text">Address:</span><p id="address" class="order-text">'.$addresses[$i].', '.$states[$i].'</p>' ;
    $orderHtml = $orderHtml.'<span id="tracking-label" class="order-text">Track #:</span><p id="tracking" class="order-text">'.$trackNos[$i].'</p>' ;
    $orderHtml = $orderHtml.'<span id="date-label" class="order-text">Date:</span><p id="date" class="order-text">'.$dates[$i].'</p>' ;
    $orderHtml = $orderHtml.'</div>' ;
  }

}

?>
<html>
	<head>
    <title>BookBoys - View Orders</title>
    <link rel="stylesheet" href="../stylesheets/style-main.css">
    <link rel="stylesheet" href="../stylesheets/style-manager.css">
    <link rel="stylesheet" href="../stylesheets/style-nav.css">
    <link rel="stylesheet" href="../stylesheets/style-customer.css">
	</head>
	<body>

    <?php include "../nav/navbar.php" ; ?>

    <div id="order-container">
      <p class="order-text" id="title">Previous Orders: </p>

      <?php echo $orderHtml ; ?>

    </div>



	</body>
</html>
