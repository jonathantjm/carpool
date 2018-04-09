<?php
include("header.php");
include("userNavBar.php");

$email = $_SESSION['user'];
$advertisementID = $_GET['id'];

$advertisement = pg_query_params($db, "SELECT * FROM advertisements ad, useraccount acc WHERE acc.email = ad.email_of_driver AND ad.advertisementid = $1", array($advertisementID)); 

$row = pg_fetch_assoc($advertisement);
$msg;
$price = 0;

if (isset($_POST['submit'])) {
	
	$price = $_POST['price'];

	$query = pg_query_params($db, "SELECT addBid($1, $2, $3)", array($email, $advertisementID, $price));
	
	$result = pg_fetch_array($query);
    $error = $result[0];

    $error = $result[0];
    if (($error === 'You cannot bid for your own offer!') OR ($error === 'Your email is invalid!') OR ($error === 'You have already submitted a bid for this offer!')) {
        $emailError = $error;
    } elseif (($error === 'Advertisement id does not exist!') OR ($error === 'Sorry, advertisement has already been closed!')) {
        $adIdError = $error;
    } elseif ($error === 'Price should be numeric and greater than 0!') {
        $priceError = $error;
    } elseif ($error == '') {
		header("Location: userBid.php");
    }
}
?>

<html>
	<body id="b4">
	<h2 class="text-center">Bid and Driver details</h2>
	<div id="divTable">
		<table id="table" class="table table-striped table-bordered" style="width:100%">
<?php 
echo "<thead>
	<tr>
		<th>Start Location</th>
		<th>End Location</th>
		<th>Pick up date</th>
		<th>Pick up time</th>
		<th>Driver gender</th>
		<th>Vehicle capacity</th>
    </tr>
	</thead>";
echo "<tbody>";
	echo "<tr>";
		echo "<td>" . $row['start_location'] . "</td>";
		echo "<td>" . $row['end_location'] . "</td>";
		echo "<td>" . $row['date_of_pickup'] . "</td>";
		echo "<td>" . $row['time_of_pickup'] . "</td>";
		echo "<td>" . $row['gender'] . "</td>";
		echo "<td>" . $row['capacity'] . "</td>";
	echo "</tr>
	</tbody>";
?>
		</table>
	</div>
	</br>
	<div id="divForm">
		<form action="" method="post">
			<div class="form-group">
				<label for="inputPrice">Enter bidding price</label>
				<input type="text" pattern="(0\.((0[1-9]{1})|([1-9]{1}([0-9]{1})?)))|(([1-9]+[0-9]*)(\.([0-9]{1,2}))?)" name="price" class="form-control" id="inputPrice" placeholder="<?php echo $price; ?>" required>
				<span style="color:red"><?php echo $msg; ?></span>
			</div>
			<button type="submit" name="submit" class="btn btn-primary">Bid</button>
		</form>
	</div>
	</body>
</html>
