<?php  
include("header.php");
include("userNavBar.php");

//echo 'DB is connected';
$result = pg_query($db, "SELECT * FROM bid B, advertisements A
	WHERE B.advertisementid = A.advertisementid 
	AND email = '" . $_SESSION['user'] . "';"); 
$locations = pg_query($db, "SELECT * FROM locations"); 

?>

<html>

<h1><b>Search for offers:</b></h1>

<form action="" method="post">

<div>

<?php
$locations_array = array();
while($row = pg_fetch_array( $locations )) {
	$locations_array[] = $row[0];
}


echo "<strong>Start Location: *</strong>";
//echo "<select name= 'start_location' />";
//echo "<option value= '' selected disabled hidden /> Choose here </option>";
echo "<select name= \"start_location\" />";
echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
foreach ($locations_array as $location){
	echo "<option value ='".$location."' >".$location."</option>";
}
echo "</select><br/>";

echo "<strong>End Location: *</strong>";
//echo "<select name='end_location' />";
//echo "<option value= '' selected disabled hidden /> Choose here </option>";
echo "<select name=\"end_location\" />";
echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
foreach ($locations_array as $location){
	echo "<option value ='".$location."' >".$location."</option>";
}
echo "</select><br/>";

?>

<strong>Date Of Pickup: *</strong> <input type="date" name="date_of_pickup" /><br/>

<strong>Time Range: *</strong> <input type="time" name="start_time" /> <input type="time" name="end_time" /><br/>

<input type="submit" name="button" value="Submit">

<?php

if(isset($_POST['button'])){
	$start_location = $_POST['start_location'];
	$end_location = $_POST['end_location'];
	$date_of_pickup = $_POST['date_of_pickup'];
	$start_time = $_POST['start_time'];
	$end_time = $_POST['end_time'];

	$search_results = pg_query_params("SELECT * FROM advertisements 
		WHERE 	start_location = $1
		AND 	end_location = $2
		AND 	date_of_pickup = $3
		AND 	time_of_pickup >= $4
		AND 	time_of_pickup <= $5
		AND 	closed = false
		AND 	advertisementid NOT IN (
				SELECT advertisementid from bid
				WHERE  email = $6);"
		, array($start_location, $end_location, $date_of_pickup, $start_time, $end_time, $email)
	);

	
	if(pg_num_rows($search_results) > 0){
		echo "<br><table><tr>
			<th>Start Location</th>
			<th>End Location</th>
			<th>Pick up date</th>
			<th>Pick up time</th>
		</tr>";
		while ($row = pg_fetch_assoc($search_results)){
			echo "<tr>";
				echo "<td>" . $row['start_location'] . "</td>";
				echo "<td>" . $row['end_location'] . "</td>";
				echo "<td>" . $row['date_of_pickup'] . "</td>";
				echo "<td>" . $row['time_of_pickup'] . "</td>";
				echo "<td><a href='userCreateBid.php?id=".$row['advertisementid']."'>Bid</a></td>";
			echo "</tr>";
		}	
		echo "</table>";
	}
	else{
		echo "<br>Sorry. No matching rides found!<br><br>";
	}
}

?>

</div>

<h1><b>Your Bids:</b></h1>

<table>

<?php 

echo "<tr>
		<th>Start Location</th>
		<th>End Location</th>
		<th>Pick up date</th>
		<th>Pick up time</th>
		<th>Price Bidded</th>
		<th>Status</th>
	</tr>";

while($row = pg_fetch_assoc( $result )) { 
	echo "<tr>";
		echo "<td>" . $row['start_location'] . "</td>";
		echo "<td>" . $row['end_location'] . "</td>";
		echo "<td>" . $row['date_of_pickup'] . "</td>";
		echo "<td>" . $row['time_of_pickup'] . "</td>";
		echo "<td>" . $row['price'] . "</td>";
		echo "<td>" . $row['status'] . "</td>";
		echo "<td><a href='userDeleteBid.php?id=".$row['advertisementid']."'>Delete</a></td>";
		echo "<td><a href='userEditBid.php?id=".$row['advertisementid']."'>Edit</a></td>";
	echo "</tr>";
}

?>
	
</table>

<p>Add a new bid</p>

<p><a href="userCreateBid.php">New bid</a></p>

</body>

</html>