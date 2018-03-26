<?php
include("header.php");
include("userNavBar.php");

$driver = pg_query($db, "SELECT is_driver FROM useraccount
	WHERE email = '" . $_SESSION['user'] . "';"); 
?>

<script type="text/javascript">
  function bid_Display() {
    window.location = "userBid.php";
  }
  function offer_Display() {
	window.location = "userOffer.php";
  }
  function accept_Display() {
	window.location = "accept.php";
  }
</script>

<html>

<body>
	<div>
		<h2>User Profile</h2>
		<table cellpadding = "10">
			<tr>
			<td><button type="button" onclick="bid_Display()">Manage your bids</button></td>
			<?php

			if (pg_fetch_assoc($driver)['is_driver'] == 't'){
				echo "<td><button type=\"button\" onclick=\"offer_Display()\">Manage your offers</button></td>";
				echo "<td><button type=\"button\" onclick=\"accept_Display()\">Accept bids</button></td>";
			}
			?>
			</tr>
		</table>
	</div>
</body>

</html>