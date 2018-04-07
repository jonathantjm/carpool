<?php
include("header.php");
include("adminNavBar.php");

$result = pg_query($db, 'SELECT * FROM bidHistory ORDER BY creation_date_and_time DESC');
?>

<html>
	<body id = 'adminpage'>
		<h2 class="text-center">Past Bidding Information</h2>
		<div id="divTable">
			<table id="table" class="table table-striped table-bordered" style="width:100%">
				<?php
				$counter = 1;
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
				?>
			</table>
		</div>
		<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Scroll to top" data-toggle="tooltip" data-placement="left"><i class="fas fa-arrow-up"></i></a>
	</body>
</html>
