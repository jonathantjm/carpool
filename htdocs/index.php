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
	<body id="indexpage">
		<div class ="wrapper">
			<div class="container">
					<h2 class="text-center">Carpool<h2>
				  <div id="myCarousel" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
					  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					  <li data-target="#myCarousel" data-slide-to="1"></li>
					  <li data-target="#myCarousel" data-slide-to="2"></li>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner">
					  <div class="item active">
						<img src="css/images/a.jpg" alt="introduction" style="width:100%;">
						<div class="carousel-caption">
							<h3>Join us today</h3>
							<p>Sign up as a driver or user</p>
						</div>
					  </div>
					  <div class="item">
						<img src="css/images/b.jpg" alt="howitworks" style="width:100%;">
						<div class="carousel-caption">
							<h3>Your destination is our goal</h3>
							<p>Look no further</p>
						</div>
					  </div>
					  <div class="item">
						<img src="css/images/c.jpg" alt="joinnow" style="width:100%;">
						<div class="carousel-caption">
							<h3>Book your ride now</h3>
							<p>It's quick and easy</p>
						</div>
					  </div>
					</div>

					<!-- Left and right controls -->
					<a class="left carousel-control" href="#myCarousel" data-slide="prev">
					  <span class="glyphicon glyphicon-chevron-left"></span>
					  <span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#myCarousel" data-slide="next">
					  <span class="glyphicon glyphicon-chevron-right"></span>
					  <span class="sr-only">Next</span>
					</a>
				  </div>
			</div>
			<div class="col-lg-12" style="height:25px;"></div>
			<div class = "row">
				<button type="button" class = "btn btn-huge btn-danger col-md-2  col-md-offset-3 text-center" style="font-size:30px" onclick="login_Display()">LOGIN</button>
				<button type="button" class = "btn btn-huge  btn-danger col-md-2  col-md-offset-2 text-center" style="font-size:30px" onclick="signup_Display()">SIGNUP</button>
			</div>
			<div class="push"></div>
		</div>
		<footer class="footer">Copyright &copy; CS2102 Team 20</footer>
	</body>
</html>
