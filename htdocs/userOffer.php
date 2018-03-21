<?php  
include("header.php");
include("userNavBar.php");

//echo 'DB is connected';
$result = pg_query($db, "SELECT * FROM advertisements 
	WHERE email_of_driver = '" . $_SESSION['user'] . "';"); 

?>

<html>

<h1><b>Your Offers:</b></h1>

<table>

<?php 

echo "<tr>
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

?>
	
</table>

<p>Add a new bid</p>

<p><a href="userCreateOffer.php">New bid</a></p>

</body>

</html>