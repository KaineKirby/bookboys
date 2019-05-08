<?php
session_start() ;

if (isset($_GET["trans"])) {

  include "../php/bookpurchaseactions.php" ;

  $transId = $_GET["trans"] ;

  list($userId, $address, $state, $method, $date, $trackNo, $total) = getTransaction($transId) ;

  if ($userId != $_SESSION["user_id"]) {
    echo "Unable to find this transaction. Make sure you are logged into the right account." ;
  }
  else {
    ?>
    <html>
    	<head>
    		<title>BookBoys - Order # <?php echo $transId ; ?></title>
        <link rel="stylesheet" href="../stylesheets/style-main.css">
        <link rel="stylesheet" href="../stylesheets/style-manager.css">
        <link rel="stylesheet" href="../stylesheets/style-nav.css">
        <link rel="stylesheet" href="../stylesheets/style-customer.css">
    	</head>
    	<body>

        <?php include "../nav/navbar.php" ; ?>

        <div class="checkout-div">
          <p class="checkout-text" id="title">Order # <?php echo $transId ; ?></p>
          <p class="checkout-text"><strong>Shipping Type:</strong> <?php echo $method ; ?></p>
          <p class="checkout-text"><strong>Shipping Location:</strong> <?php echo $address.', '.$state ; ?></p>
          <p class="checkout-text"><strong>Tracking Number: </strong> <?php echo $trackNo ; ?></p>
          <p class="checkout-text"><strong>Total:</strong> <?php echo "$".$total ; ?></p>
        </div>
      </body>
    </html>


    <?php


  }




}


?>
