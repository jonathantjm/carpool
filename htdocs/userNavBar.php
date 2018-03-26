<!DOCTYPE html>
<html>
	<body>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
				  <a class="navbar-brand" href="userPage.php"><i class="fas fa-car"></i>Car Pool</a>
				</div>
				<div>
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="userBid.php">Bids
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="userBid.php">View bids</a></li>
								<li><a href="userCreateBid.php">Create bid</a></li>
							</ul>
						</li>
						<li class ="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="userOffer.php">Advertisements
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="userOffer.php">View offers</a></li>
								<li><a href="userCreateOffer.php">Create offer</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</body>
</html>
