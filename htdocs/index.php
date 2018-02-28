<?php	session_start();  ?>

<script type="text/javascript">
  function login_Display() {
    window.location = "login.php";
  }
  function signup_Display() {
	window.location = "signup.php";
  }
</script>

<html>

<body>
	<div>
		<h2>Car Pooling</h2>
		<button type="button" onclick="login_Display()">Login</button>
        <button type="button" onclick="signup_Display()">Signup</button>
	</div>
</body>

</html>
