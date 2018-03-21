<?php	session_start();  ?>

<script type="text/javascript">
  function home_page() {
    window.location = "userPage.php";
  }
</script>

<html>

<body>
	<div>
		<h2>Something went wrong...</h2>
		<h3>Click the button below to go back to the home page</h3>
		<button type="button" onclick="home_page()">Go back home</button>
	</div>
</body>

</html>