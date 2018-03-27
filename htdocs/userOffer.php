<?php  
include("header.php");
include("userNavBar.php");

//echo 'DB is connected';
$result = pg_query($db, "SELECT * FROM advertisements 
	WHERE email_of_driver = '" . $_SESSION['user'] . "';"); 

?>

<html>

<h1><b>Your Offers:</b></h1>

<?php 

if (pg_num_rows($result) > 0){
	echo "<table cellpadding = \"10\"><tr>
		<th>Start Location</th>
		<th>End Location</th>
		<th>Pick up date</th>
		<th>Pick up time</th>
		<th>Closed?</th>
		<th>Self Select?</th>
		<th>Date and Time Created</th>
	</tr>";

	while($row = pg_fetch_array( $result )) { 
		echo "<tr>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[5] . "</td>";
		echo "<td>" . $row[6] . "</td>";
		if ($row[7] == 't'){
			echo "<td>Yes</td>";
		}
		else{
			echo "<td>No</td>";	
		}
		if ($row[8] == 't'){
			echo "<td>Yes</td>";
		}
		else{
			echo "<td>No</td>";	
		}
		echo "<td>" . $row[4] . "</td>";
		echo "<td><a href='userDeleteOffer.php?id=".$row[0]."'>Delete</a></td>";
		echo "<td><a href='userEditOffer.php?id=".$row[0]."'>Edit</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}
else{
	echo "<br>You have not made any offers!<br><br>";
}


?>

<h2>Add a new offer</h2>

<p><a href="userCreateOffer.php">New offer</a></p>

</body>

</html>