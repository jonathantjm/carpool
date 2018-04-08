<?php  
include("header.php");
include("userNavBar.php");

$historyResult = pg_query($db, "SELECT * FROM advertisementshistory
	WHERE email_of_driver = '" . $_SESSION['user'] . "';");
?>

<html>
	<body id="b11">
		<h2 class="text-center"><b>Your Past Offers</b></h2>
		<div id="divTable">
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
} else {
	echo "<h5 class='text-center'>You do not have any past offers!</h5>";
}
?>
		</div>
	</body>
</html>
