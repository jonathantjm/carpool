<?php
include("header.php");
include("userNavBar.php");

$advertisementID = $_GET['id'];
$email = $_SESSION['user'];
$creator = pg_query($db, "SELECT email FROM bid WHERE advertisementid = '" . $advertisementID . "' AND email = '".$email. "';");

if(is_null($advertisementID)){
	$message = "Bid not found!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}
/*if(pg_num_rows($creator == 0)){
	$message = "Bid not found!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";	
}
if(pg_fetch_array($creator)[0] != $email){
	$message = "You are not authorized to view this page!";
	echo "<script type='text/javascript'>alert('$message');
		window.location.href='userPage.php';
	</script>";
}*/

$currentBid = pg_query_params($db, "SELECT * FROM bid 
	WHERE email = $1 AND advertisementid = $2", array($email, $advertisementID));

$advertisement = pg_query($db, "SELECT * FROM advertisements, useraccount
	WHERE email_of_driver = email
	AND   advertisementid = " . $advertisementID . ";"); 
	
$row = pg_fetch_assoc($currentBid);

if (isset($_POST['submit'])) {
	
	$price = $_POST['price'];

	$query = pg_query_params($db, "SELECT editBid($1, $2, $3, $4, $5)", array($email, $advertisementID, $row['status'], $price, $row['creation_date_and_time']));
	$result = pg_fetch_array($query);
	$error = $result[0];

    if (($error === 'You cannot bid for your own offer!') OR ($error === 'Your email is invalid!')) {
		$emailError = $error;
    } elseif (($error === 'Advertisement id does not exist!') OR ($error === 'Sorry, advertisement has already been closed!') 
		OR ($error === 'Please make sure you have an existing bid for that offer!')) {
		$adIdError = $error;
    } elseif ($error === 'Price should be numeric and greater than 0!') {
		$priceError = $error;
    } elseif ($error === 'Status is case-sensitive and should be Pending, Rejected, Accepted, Offer retracted or Offer expired') {
		$statusError='';
	} elseif ($error == '') {
		header("Location: userBid.php");
    }
}
?>

<html>
	<body id="b7">
		<h2 class="text-center">Details about the bid</h2>
		<div id="divBox">
			<table id='table' class='table table-striped table-bordered'>
				<col width="150">
				<col width="150">
<?php
	$adv_row = pg_fetch_assoc($advertisement);
	echo "<tr>" ;
		echo "<td> Start Location: </td>";
		echo "<td>" . $adv_row['start_location'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> End Location: </td>";
		echo "<td>" . $adv_row['end_location'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Pick Up Date: </td>";
		echo "<td>" . $adv_row['date_of_pickup'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Pick Up Time: </td>";
		echo "<td>" . $adv_row['time_of_pickup'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Driver Gender: </td>";
		echo "<td>" . $adv_row['gender'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Driver Capacity: </td>";
		echo "<td>" . $adv_row['capacity'] . "</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td> Your price: </td>";
		echo "<td>" . $row['price'] . "</td>";
	echo "</tr>";
?>
			</table>
		</div>
		</br>
		<div id="divBox">
			<form action="" method="post">
				<strong>Price: *</strong> <input type="number" name="price" value = "<?php echo $row['price']?>" required/><br/>
				<button type="submit" name="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</body>	
</html>
