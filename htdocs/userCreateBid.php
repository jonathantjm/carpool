<?php
session_start();
$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

$email = $_SESSION['user'];
$advertisementID = $_GET['id'];

$advertisement = pg_query($db, "SELECT * FROM advertisements, useraccount
	WHERE email_of_driver = email
	AND   advertisementid = " . $advertisementID . ";"); 

if (pg_num_rows($advertisement) == 0){
	header("Location: error.php");
}

$multiple_bid_check = pg_query_params($db, "SELECT * FROM bid
	WHERE 	email = $1
	AND 	advertisementid = $2
	", array($email, $advertisementID)
);

if (pg_num_rows($multiple_bid_check) > 0){
	header("Location: error.php");
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
	$row = pg_fetch_assoc($advertisement);
	echo "<tr>" ;
		echo "<td> Start Location: </td>";
		echo "<td>" . $row['start_location'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> End Location: </td>";
		echo "<td>" . $row['end_location'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Pick Up Date: </td>";
		echo "<td>" . $row['date_of_pickup'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Pick Up Time: </td>";
		echo "<td>" . $row['time_of_pickup'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Driver Gender: </td>";
		echo "<td>" . $row['gender'] . "</td>";
	echo "</tr>";
	echo "<tr>" ;
		echo "<td> Driver Capacity: </td>";
		echo "<td>" . $row['capacity'] . "</td>";
	echo "</tr>";
?>

</table><br><br>

<h2> Enter your bid: </h2>

<form action="" method="post">

<strong>Enter your bid: </strong> <input type="number" name="price" /><br/>

<input type="submit" name="submit" value="Submit">

</form>

</html>
