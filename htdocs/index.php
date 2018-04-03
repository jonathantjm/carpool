<?php
include("header.php");
?>

<script type="text/javascript">
  function login_Display() {
    window.location = "login.php";
  }
  function signup_Display() {
	window.location = "signup.php";
  }
</script>

<html>
<body id="backgroundimage">
	<div class="col-lg-12" style="height:320px;"></div>
	<div class = "row">
		<button type="button" class = "btn btn-huge btn-danger col-md-2  col-md-offset-3 text-center" onclick="login_Display()">LOGIN</button>
		<button type="button" class = "btn btn-huge  btn-danger col-md-2  col-md-offset-2 text-center" onclick="signup_Display()">SIGNUP</button>
	</div>
</body>
</html>
