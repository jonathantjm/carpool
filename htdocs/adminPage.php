<?php	session_start();  ?>

<script type="text/javascript">
  function adminBid() {
    window.location = "adminBid.php";
  }
  function adminOffer() {
	window.location = "adminOffer.php";
  }
  function adminUser() {
	window.location = "adminUser.php";
  }
</script>

<html>

<body>
	<div>
		<h2>Administrator Profile</h2>
		<button type="button" onclick="adminBid()">Add/Change Bid</button>
		<button type="button" onclick="adminOffer()">Add/Change Offer</button>
		<button type="button" onclick="adminUser()">Add/Change User</button>
	</div>
</body>

</html>