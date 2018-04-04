<?php
//Verify admin permissions and user is logged in
$isAdmin = $_SESSION['isAdmin'];
if($isAdmin == 'f') {
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
} elseif ($isAdmin == null) {
	$message = "Please login to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='login.php';
	</script>";
}
?>

<!DOCTYPE html>
<html>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                  <a class="navbar-brand" href="adminPage.php"><i class="fas fa-car"></i>Car Pool</a>
                </div>
                <div>
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="adminBid.php">Bids
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="adminBid.php">View existing bids</a></li>
                                <li><a href="adminCreateBid.php">Create bid</a></li>
                                <li><a href="adminBidHistory.php">View past bids</a></li>
							</ul>
                        </li>
                        <li class ="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="adminOffer.php">Advertisements
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="adminOffer.php">View offers</a></li>
                                <li><a href="adminCreateOffer.php">Create offer</a></li>
                            </ul>
                        </li>
                        <li class ="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="adminOffer.php">Users
                            <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="adminUser.php">View users</a></li>
                                <li><a href="adminCreateUser.php">Create user</a></li>
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
