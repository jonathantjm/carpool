<?php  
include("header.php");
include("userNavBar.php");

//echo 'DB is connected';

$historyResult = pg_query($db, "SELECT * FROM advertisementshistory
	WHERE email_of_driver = '" . $_SESSION['user'] . "';");

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
?>

<html>

<h1><b>Your Past Offers:</b></h1>

<?php 
$counter = 1;
if (pg_num_rows($historyResult) > 0) {
	echo "<table id='table' class='table table-striped table-bordered' style='width:100%'>
	<thead>
		<tr>
			<th>S/N</th>
			<th>Start Location</th>
			<th>End Location</th>
			<th>Pick up date</th>
			<th>Pick up time</th>
		</tr>
	</thead>
	<tbody>";
	while($row = pg_fetch_array($historyResult)) { 
		echo "<tr>";
		echo "<td>" . $counter . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[4] . "</td>";
		echo "</tr>";
		$counter++;
	}
	echo "</tbody>
	</table>";
}
else{
	echo "<br>You do not have any past offers!<br><br>";
}


?>

<h2>Click <a href = "userOffer.php">here</a> to go back to your Current Offers</h2></a>

</body>

</html>