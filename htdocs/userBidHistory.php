<?php  
include("header.php");
include("userNavBar.php");

//echo 'DB is connected';
$historyResult = pg_query($db, "SELECT * FROM bidhistory
	WHERE email = '" . $_SESSION['user'] . "';"); 

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
?>

<html>

<h1><b>Your Past Bids:</b></h1>

<table>

<?php 
	if (pg_num_rows($historyResult)>0){
		$count = 1;
		echo "<table id='table' class='table table-striped table-bordered' style='width:100%'>
			<thead>
				<tr>
					<th>S/N</th>
					<th>Start Location</th>
					<th>End Location</th>
					<th>Pick up date</th>
					<th>Pick up time</th>
					<th>Price Bidded</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>";
		while($row = pg_fetch_assoc( $historyResult )) { 
			echo "<tr>";
				echo "<td>" . $count . "</td>";
				echo "<td>" . $row['start_location'] . "</td>";
				echo "<td>" . $row['end_location'] . "</td>";
				echo "<td>" . $row['date_of_pickup'] . "</td>";
				echo "<td>" . $row['time_of_pickup'] . "</td>";
				echo "<td>" . $row['price'] . "</td>";
				echo "<td>" . $row['status'] . "</td>";
			echo "</tr>";
			$count++;
		}
		echo "</tbody>
			</table>";
	}
	else{
		echo "<br>You do have any past bids!<br><br>";
	}
?>

<h2>Click <a href = "userBid.php">here</a> to go back to your Current Bids</h2></a>

</body>

</html>