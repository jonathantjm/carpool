<?php
include("header.php");
include("adminNavBar.php");

$result = pg_query($db, 'SELECT * FROM bid ORDER BY advertisementid');
?>


<html>
	<body id = 'adminpage'>
		<h2 class="text-center">Existing Bidding Information</h2>
		<div class="container">
			<div class="row">
				<div class="col-md-offset-11">
					<a href="adminCreateBid.php" class='btn btn-primary' role='button'>Create bid</a>
				</div>
			</div>
		</div>
		</br>
		<div id="divTable">
			<table id="table" class="table table-striped table-bordered" style="width:100%">
				<?php
				$counter = 1;
				echo "<thead>
						<tr>
							<th>S/N</th>
							<th>Email</th>
							<th>Advertisement ID</th>
							<th>Status</th>
							<th>Price(SGD)</th>
							<th>Date and time created</th>
							<th>Edit</th>
							<th>Delete</th>
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
						echo "<td><a href='adminEditBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Edit</a></td>";
						echo "<td><a href='adminDeleteBid.php?id=", urlencode($row[1]), "&mail=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Delete</a></td>";
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
