<?php

if (isset($_SESSION["username"])) {
  $navUsername = $_SESSION["username"] ;
  $isManager = $_SESSION["is_manager"] ;
}

//is a manager
if ($isManager) {
?>
<div id="navbar-container" class="nav">
  <link rel="stylesheet" href="../stylesheets/style-nav.css">
  <span id="logo">Book Boys</span>
  <div id="navbtn-container">
    <span id="nav-username">Logged in as manager: <strong><?php echo $navUsername ; ?></strong></span>
    <a class="navbtn" href="/bookboys/manager/stock.php">Inventory</a>
    <a class="navbtn" href="/bookboys/manager/book_list.php">Manage Books</a>
    <a class="navbtn" href="/bookboys/manager/bookman.php">Add a Book</a>
    <a class="navbtn" href="/bookboys/logout.php">Logout</a>
  </div>
</div>

<?php
}
//not a manager
else {
?>
<div id="navbar-container" class="nav">
  <link rel="stylesheet" href="../stylesheets/style-nav.css">
  <span id="logo">Book Boys</span>
  <div id="navbtn-container">
    <span id="nav-username">Logged in as customer: <strong><?php echo $navUsername ; ?></strong></span>
    <a class="navbtn" href="/bookboys/customer/search.php">Search Store</a>
    <a class="navbtn" href="/bookboys/customer/orders.php">View Orders</a>
    <a class="navbtn" href="/bookboys/customer/cart.php">View Cart</a>
    <a class="navbtn" href="/bookboys/logout.php">Logout</a>
  </div>
</div>

<?php
//    <a class="navbtn" href="/bookboys/customer/top_sellers.php">Top Sellers</a>


}
?>
