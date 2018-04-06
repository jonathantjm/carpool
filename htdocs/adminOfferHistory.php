<?php
include("header.php");
include("adminNavBar.php");

$result = pg_query($db, 'SELECT DISTINCT * FROM advertisemnetHistory ORDER BY email, creation_date_and_time DESC');
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
		<h1 class="text-center">Past Advertisements Information</h1>
		<table id="table" class="table table-striped table-bordered" style="width:100%">
<?php 
echo "<thead>
		<tr>
			<th>S/N</th>
			<th>Email</th>
			<th>Start Location</th>
			<th>End Location</th>
			<th>Date and time created</th>
			<th>Date of pickup</th>
			<th>Time of pickup</th>
			<th>Driver self-select</th>
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
	echo "</tr>";
	$counter++;
}
echo "</tbody>";
?>
		</table>
		<ul class="pager">
			<li class="previous"><a href="adminPage.php">Back</a></li>
		</ul>
	</div>
	</body>
</html>
