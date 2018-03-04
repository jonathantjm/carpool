<?php

$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");

if (isset($_POST['submit'])) {
	
	echo time();
	
	echo $first = $_POST['email'];
	echo $second = $_POST['advertisementID'];
	echo $third = $_POST['price'];
	echo $fourth = $_POST['timestamp'];
	
	pg_query_params("INSERT INTO bid VALUES ($1, $2, 'pending', $3, $4)", array($first, $second, $third, $fourth));
	
	echo pg_last_error($db);
	
	header("Location: adminBid.php");

}
?>

<html>

<form action="" method="post">

<div>

<strong>Email: *</strong> <input type="text" name="email" /><br/>

<strong>Advertisement ID: *</strong> <input type="text" name="advertisementID" /><br/>

<strong>Price: *</strong> <input type="number" name="price" /><br/>

<strong>Date and Time Created: *</strong> <input type="test" name="timestamp" /><br/>

<p>* required</p>

<input type="submit" name="submit" value="Submit">

</div>

</form>

</html>
