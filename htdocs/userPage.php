<?php	session_start();  ?>

<script type="text/javascript">
  function bid_Display() {
    window.location = "offer.php";
  }
  function offer_Display() {
	window.location = "bid.php";
  }
  function accept_Display() {
	window.location = "accept.php";
  }
</script>

<html>

<body>
	<div>
		<h2>User Profile</h2>
		<button type="button" onclick="bid_Display()">See bids</button>
		<button type="button" onclick="offer_Display()">See offers</button>
		<button type="button" onclick="accept_Display()">Accept bids</button>
	</div>
</body>

</html>