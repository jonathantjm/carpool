<?php
//Verify user is logged in
$isAdmin = $_SESSION['isAdmin'];
if ($isAdmin == null) {
    $message = "Please login to view this page!";
    echo "<script type='text/javascript'>alert('$message');
    window.location.href='login.php';
    </script>";
}

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
	
echo "<script type='text/javascript'>
	$(document).ready(function(){
		$(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
        // scroll body to 0px on click
        $('#back-to-top').click(function () {
            $('#back-to-top').tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
        
        $('#back-to-top').tooltip('show');
	});
	</script>";
?>

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
                        <li>
                            <a href="userEditProfile.php">Edit profile</a>
                        </li>
                        <li>
                            <a href="userEditPassword.php">Change password?</a>
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
