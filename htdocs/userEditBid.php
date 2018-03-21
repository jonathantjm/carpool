<?php
include("header.php");
include("userNavBar.php");

$advertisementID = $_GET['id'];
$email = $_SESSION['user'];
$creator = pg_query($db, "SELECT email FROM bid WHERE advertisementid = '" . $advertisementID . "';");
if(pg_fetch_array($creator)[0] != $email || is_null($advertisementID)){
	header("Location: error.php");
}

$result = pg_query($db, "SELECT * FROM bid 
	WHERE advertisementid = '" . $advertisementID . "'
	AND 	email = '" . $email . "';");

$advertisement = pg_query($db, "SELECT * FROM advertisements, useraccount
	WHERE email_of_driver = email
	AND   advertisementid = " . $advertisementID . ";"); 

$row = pg_fetch_assoc($result);

if (isset($_POST['submit'])) {
	
	$price = $_POST['price'];

	pg_query_params($db, "UPDATE bid SET price = $1
		WHERE 	advertisementid = $2
		AND 	email = $3;",
		array($price, $advertisementID, $email)
	);

	#echo pg_last_error($db);

	header("Location: userBid.php");

}

?>

<html>

<h1>Bid and Driver details</h1>

<table>

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
?>

</table><br><br>

<form action="" method="post">

<strong>Price: *</strong> <input type="number" name="price" value = "<?php echo $row['price']?>"/><br/>

<input type="submit" name="submit" value="Submit">

</form>

</html>
