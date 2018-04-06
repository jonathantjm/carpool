<?php  
include("header.php");
include("userNavBar.php");

//echo 'DB is connected';
$result = pg_query($db, "SELECT * FROM advertisements 
	WHERE email_of_driver = '" . $_SESSION['user'] . "';");

$historyResult = pg_query($db, "SELECT * FROM advertisementshistory
	WHERE email_of_driver = '" . $_SESSION['user'] . "';");

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
$counter = 1;
?>

<html>

<h1><b>Your Offers:</b></h1>

<?php 

if (pg_num_rows($result) > 0) {
	echo "<table id='table' class='table table-striped table-bordered' style='width:100%'>
	<thead>
		<tr>
			<th>S/N</th>
			<th>Start Location</th>
			<th>End Location</th>
			<th>Pick up date</th>
			<th>Pick up time</th>
			<th>Closed?</th>
			<th>Self Select?</th>
			<th>Date and Time Created</th>
			<th>Delete</th>
			<th>Edit</th>
		</tr>
	</thead>
	<tbody>";
	while($row = pg_fetch_array( $result )) { 
		echo "<tr>";
		echo "<td>" . $counter . "</td>";
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
		$counter++;
	}
	echo "</tbody>
	</table>";
}
else{
	echo "<br>You do not have any active offers!<br><br>";
}


?>

<h2>Add a new offer</h2>

<p><a href="userCreateOffer.php">New offer</a></p>

<h2>Click <a href = "userOfferHistory.php">here</a> to see your Past Offers</h2>

</body>

</html>