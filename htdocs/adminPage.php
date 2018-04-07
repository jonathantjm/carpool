<?php
include("header.php");
include("adminNavBar.php");

$queryUsers = pg_query($db, "SELECT COUNT(*) FROM useraccount");
$totalUsers = pg_fetch_array($queryUsers)[0];
$queryAdvertisements = pg_query($db, "SELECT COUNT(*) FROM advertisements");
$totalAdvertisements = pg_fetch_array($queryAdvertisements)[0];
$queryBids = pg_query($db, "SELECT COUNT(*) FROM bid WHERE status = 'Pending'");
$totalBids = pg_fetch_array($queryBids)[0];
?>

<html>
	<body id = 'adminpage'>
		</br>
		<div class="row">
			<div class="col-md-8 col-md-offset-1">
				<ul class="list-group">
					<li class="list-group-item" style="background-color:transparent; border-color:transparent">
						<a href="adminUser.php" style="font-size:48px; color:black"><i class="fas fa-users" style="font-size:48px; color:black"></i>Manage users</a>
					</li>
					<li class="list-group-item" style="background-color:transparent; border-color:transparent">
						<a href="adminOffer.php" style="font-size:48px; color:black"><i class="fas fa-list-alt" style="font-size:48px; color:black"></i> Manage offers</a>
					</li>
					<li class="list-group-item" style="background-color:transparent; border-color:transparent">
						<a href="adminBid.php" style="font-size:48px; color:black"><i class="fas fa-dollar-sign" style="font-size:48px; color:black; transform:scale(1.5,1)"></i>      Manage bids</a>
					</li>
				</ul>
			</div>
			<div class="col-md-3" style="border-radius:25px">
				<ul class="list-group">
					<li class="list-group-item" style="background-color:transparent; border-color:transparent">
					<span class="badge"><?php echo $totalUsers; ?></span>
					<b>Total number of users</b>
					</li>
					<li class="list-group-item" style="background-color:transparent; border-color:transparent">
					<span class="badge"><?php echo $totalAdvertisements; ?></span>
					<b>Total number of advertisements currently</b>
					</li>
					<li class="list-group-item" style="background-color:transparent; border-color:transparent">
					<span class="badge"><?php echo $totalBids; ?></span>
					<b>Total number of on-going bids</b>
					</li>
				</ul>
			</div>
		</div>
	</body>
</html>
