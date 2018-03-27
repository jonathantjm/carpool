<?php
include("header.php");
include("userNavBar.php");

$email = $_SESSION['user'];
$advertisementID = $_GET['id'];

$advertisement = pg_query($db, "SELECT * FROM advertisements, useraccount
	WHERE email_of_driver = email
	AND   advertisementid = " . $advertisementID . ";"); 

//If no advertisement id found
if (pg_num_rows($advertisement) == 0){
	$message = "An error occured!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}

$advertisement_result = pg_fetch_assoc($advertisement);

//If advertisement already closed
if ($advertisement_result['closed'] == 't'){
	$message = "Offer has already been closed!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}

//If bidder is also owner of offer
if ($advertisement_result['email'] == $email){
	$message = "You cannot bid for your own offer!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}

$multiple_bid_check = pg_query_params($db, "SELECT * FROM bid
	WHERE 	email = $1
	AND 	advertisementid = $2
	", array($email, $advertisementID)
);

//If already bid for this offer
if (pg_num_rows($multiple_bid_check) > 0){
	$message = "You've already submitted a bit for this offer!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}

if (isset($_POST['submit'])) {
	
	$price = $_POST['price'];
	
	pg_query_params("INSERT INTO 
		bid (email, advertisementid, price, creation_date_and_time)
		VALUES ($1, $2, $3, NOW())",
		array($email, $advertisementID, $price)
	);
	
	echo pg_last_error($db);
	
		header("Location: userBid.php");

}

?>

<html>

<h1>Bid and Driver details</h1>

<table>

<?php
	echo "<tr>" ;
		echo "<td> Start Location: </td>";
		echo "<td>" . $advertisement_result['start_location'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> End Location: </td>";
		echo "<td>" . $advertisement_result['end_location'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Pick Up Date: </td>";
		echo "<td>" . $advertisement_result['date_of_pickup'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Pick Up Time: </td>";
		echo "<td>" . $advertisement_result['time_of_pickup'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Driver Gender: </td>";
		echo "<td>" . $advertisement_result['gender'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Driver Capacity: </td>";
		echo "<td>" . $advertisement_result['capacity'] . "</td>";
	echo "</tr>";
?>

</table><br><br>

<h2> Enter your bid: </h2>

<form action="" method="post">

<strong>Enter your bid: </strong> <input type="number" name="price" required /><br/>

<input type="submit" name="submit" value="Submit">

</form>

</html>
