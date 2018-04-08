<?php  
include("header.php");
include("userNavBar.php");

$historyResult = pg_query($db, "SELECT * FROM bidhistory
	WHERE email = '" . $_SESSION['user'] . "';"); 
?>

<html>
	<body id="b2">
	<h2 class="text-center"><b>Your Past Bids</b></h2>
	<div id="divForm">
<?php 
	if (pg_num_rows($historyResult)>0){
		$count = 1;
		echo "<div id='divTable'>
		<table id='table' class='table table-striped table-bordered' style='width:100%'>
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
			</table>
		</div>";
	}
	else{
		echo "<br>You do have any past bids!<br><br>";
	}
?>
	</div>
	</body>
</html>