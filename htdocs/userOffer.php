<?php  
include("header.php");
include("userNavBar.php");

$result = pg_query($db, "SELECT * FROM advertisements 
	WHERE email_of_driver = '" . $_SESSION['user'] . "';");

$historyResult = pg_query($db, "SELECT * FROM advertisementshistory
	WHERE email_of_driver = '" . $_SESSION['user'] . "';");
?>

<html>
	<body id="b9">
		<div class="container">
			<div class="row">
				<div class="col-md-2" style="padding:20px">
					<a href="userOfferHistory.php" class='btn btn-primary' role='button'>Past offers</a>				
				</div>
				<div class="col-md-8">
					<h2 class="text-center">Your current offers</h2>
				</div>
				<div class="col-md-1 col-md-offset-1" style="padding:20px">
					<a href="userCreateOffer.php" class='btn btn-primary' role='button'>New offer</a>
				</div>
			</div>
		</div>
		</br>
		<div id="divTable">
<?php 
$counter = 1;
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
			<th>Bids</th>
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
		} else {
			echo "<td>No</td>";	
		}
		if ($row[8] == 't'){
			echo "<td>Yes</td>";
		} else {
			echo "<td>No</td>";	
		}
		echo "<td>" . $row[4] . "</td>";
		echo "<td><a href='userDeleteOffer.php?id=".$row[0]."'>Delete</a></td>";
		echo "<td><a href='userEditOffer.php?id=".$row[0]."'>Edit</a></td>";
		if ($row[8] == 't') {
			echo "<td><a href='userAcceptBid.php?id=".$row[0]."'>See all bids</a></td>";
		} else {
			echo "<td>Auto-accept on</td>";
		}
		echo "</tr>";
		$counter++;
	}
	echo "</tbody>
	</table>";
} else {
	echo "<h5 class='text-center'>You do not have any active offers!</h5>";
}
?>
		</div>
	</body>
</html>