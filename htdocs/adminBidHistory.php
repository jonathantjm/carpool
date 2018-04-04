<?php
include("header.php");
include("adminNavBar.php");

$result = pg_query($db, 'SELECT * FROM bidHistory ORDER BY creation_date_and_time DESC');
$counter = 1;

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
?>

<html>
	<body>
	<div>
		<h1 class="text-center">Past Bidding Information</h1>
		<table id="table" class="table table-striped table-bordered" style="width:100%">
<?php 
echo "<thead>
		<tr>
			<th>S/N</th>
			<th>Email</th>
			<th>Status</th>
			<th>Price(SGD)</th>
			<th>Date and time created</th>
			<th>Start location</th>
			<th>End location</th>
			<th>Date of pickup</th>
			<th>Time of pickup</th>
		</tr>
	</thead>";
echo "<tbody>";
while($row = pg_fetch_array( $result )) { 
	echo "<tr>";
		echo "<td>" . $counter . "</td>";		
		echo "<td>" . $row[0] . "</td>";
		echo "<td>" . $row[1] . "</td>";
		echo "<td>" . $row[2] . "</td>";
		echo "<td>" . $row[3] . "</td>";
		echo "<td>" . $row[4] . "</td>";
		echo "<td>" . $row[5] . "</td>";
		echo "<td>" . $row[6] . "</td>";
		echo "<td>" . $row[7] . "</td>";
	echo "</tr>";
	$counter++;
}
echo "</tbody>";
echo pg_last_erro($db);
?>
		</table>
		<ul class="pager">
			<li class="previous"><a href="adminPage.php">Back</a></li>
		</ul>
	</div>
	</body>
</html>
