<style type="text/css">
table.sample {
	border-width: 2px;
	border-spacing: 1px;
	border-style: solid;
	border-color: black;
	border-collapse: collapse;
	background-color: white;
}
table.sample th {
	border-width: 1px;
	padding: 1px;
	border-style: inset;
	border-color: gray;
	background-color: white;
	-moz-border-radius: ;
}
table.sample td {
	border-width: 1px;
	padding: 1px;
	border-style: inset;
	border-color: gray;
	background-color: white;
	-moz-border-radius: ;
}
</style>

<?php

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

//echo $_GET['id'];
//echo $_GET['mail'];

$advertisementID = $_GET['id'];
$email = $_GET['mail'];
$result = pg_query_params($db, 'SELECT * FROM bid WHERE advertisementid = $1 AND email = $2', array($advertisementID, $email));
$row = pg_fetch_array($result);

if(is_null($advertisementID)){
	echo 'no adv ID detected';
} else {
	echo 'found adv ID...';
	//echo $advertisementID;
	//echo $email;
}

if (isset($_POST['submit'])) {
	
	echo $newMail = $_POST['email'];
	echo $newID = $_POST['advertisementID'];
	echo $newStatus = $_POST['status'];
	echo $newPrice = $_POST['price'];
	echo $newDateAndTime = $_POST['timestamp'];

	pg_query_params($db, 'UPDATE bid SET email = $3, advertisementid = $4, status = $5, price = $6, creation_date_and_time = $7 WHERE advertisementid = $1 AND email = $2', array($advertisementID, $email, $newMail, $newID, $newStatus, $newPrice, $newDateAndTime));

	echo pg_last_error($db);

	header("Location: adminBid.php");

}

?>

<html>

<h2>Old:</h2>

<?php
echo "<table class ='sample'>";
	echo "<tr>";
		echo "<th>Email</th>";
		echo "<th>Advertisement ID</th>";
		echo "<th>Status</th>";
		echo "<th>Price</th>";
		echo "<th>Date and Time created</th>";
	echo "</tr>";
	echo "<tr>";
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[4] . "</td>";
	echo "</tr>";
echo "</table>";
?>

<h2>New:</h2>

<form action="" method="post">

<div>

<strong>Email: *</strong> <input type="text" name="email" /><br/>

<strong>Advertisement ID: *</strong> <input type="text" name="advertisementID" /><br/>

<strong>Status: *</strong> <input type="text" name="status" /><br/>

<strong>Price: *</strong> <input type="number" name="price" /><br/>

<strong>Date and Time created: *</strong> <input type="test" name="timestamp" /><br/>

<p>* required</p>

<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
