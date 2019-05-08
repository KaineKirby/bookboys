<?php

session_start() ;

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
      <p class="checkout-text" id="title">Checkout</p>
      <form action="checkout-confirm.php" method="get">
        <p class="checkout-text">Shipping Info</p>
        <input type="radio" name="shipping" value="ground"><span class="radio-label">Ground ($9.99)</span><br>
        <input type="radio" name="shipping" value="express"><span class="radio-label">Express ($19.99)</span><br>
				<span class="checkout-text">Address:</span>
				<input class="checkout-input" autocomplete="false" type="text" name="address"><br>
				<span class="checkout-text">City:</span>
				<input class="checkout-input" autocomplete="false" type="text" name="city"><br>
				<span class="checkout-text">State:</span>
				<select name="state" class="checkout-input">
					<option value="AL">Alabama</option>
					<option value="AK">Alaska</option>
					<option value="AZ">Arizona</option>
					<option value="AR">Arkansas</option>
					<option value="CA">California</option>
					<option value="CO">Colorado</option>
					<option value="CT">Connecticut</option>
					<option value="DE">Delaware</option>
					<option value="DC">District Of Columbia</option>
					<option value="FL">Florida</option>
					<option value="GA">Georgia</option>
					<option value="HI">Hawaii</option>
					<option value="ID">Idaho</option>
					<option value="IL">Illinois</option>
					<option value="IN">Indiana</option>
					<option value="IA">Iowa</option>
					<option value="KS">Kansas</option>
					<option value="KY">Kentucky</option>
					<option value="LA">Louisiana</option>
					<option value="ME">Maine</option>
					<option value="MD">Maryland</option>
					<option value="MA">Massachusetts</option>
					<option value="MI">Michigan</option>
					<option value="MN">Minnesota</option>
					<option value="MS">Mississippi</option>
					<option value="MO">Missouri</option>
					<option value="MT">Montana</option>
					<option value="NE">Nebraska</option>
					<option value="NV">Nevada</option>
					<option value="NH">New Hampshire</option>
					<option value="NJ">New Jersey</option>
					<option value="NM">New Mexico</option>
					<option value="NY">New York</option>
					<option value="NC">North Carolina</option>
					<option value="ND">North Dakota</option>
					<option value="OH">Ohio</option>
					<option value="OK">Oklahoma</option>
					<option value="OR">Oregon</option>
					<option value="PA">Pennsylvania</option>
					<option value="RI">Rhode Island</option>
					<option value="SC">South Carolina</option>
					<option value="SD">South Dakota</option>
					<option value="TN">Tennessee</option>
					<option value="TX">Texas</option>
					<option value="UT">Utah</option>
					<option value="VT">Vermont</option>
					<option value="VA">Virginia</option>
					<option value="WA">Washington</option>
					<option value="WV">West Virginia</option>
					<option value="WI">Wisconsin</option>
					<option value="WY">Wyoming</option>
				</select>
				<p class="checkout-text">Payment Info</p>
				<span class="checkout-text">Cardholder Name:</span>
				<input class="checkout-input" autocomplete="false" type="text" name="cardname"><br>
				<span class="checkout-text">Credit Card Number:</span>
				<input class="checkout-input" autocomplete="false" type="text" name="ccn"><br>
				<span class="checkout-text">Verification Code:</span>
				<input class="checkout-input" autocomplete="false" type="text" name="ccv"><br>
				<span class="checkout-text">Expiration Date:</span>
				<input class="checkout-input" autocomplete="false" type="month" name="expd"><br>
				<button class="checkout-btn-form" type="submit">Submit</button>
      </form>
    </div>

	</body>
</html>
