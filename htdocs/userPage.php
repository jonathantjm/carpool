<?php
ob_start();
include("header.php");
include("userNavBar.php");
$email = $_SESSION['user'];
$driver = pg_query_params($db, "SELECT is_driver FROM useraccount
	WHERE email = $1", array($email));
?>

<script type="text/javascript">
  function bid_Display() {
    window.location = "userBid.php";
  }
  function offer_Display() {
	window.location = "userOffer.php";
  }
  function accept_Display() {
	window.location = "accept.php";
  }
</script>

<html>
	<body id="b1">
		<div>
			<h2>User Profile</h2>
			<table cellpadding = "10">
				<tr>
				<td><button type="button" class="btn btn-primary" role="button" onclick="bid_Display()">Manage your bids</button></td>
				<?php
				if (pg_fetch_assoc($driver)['is_driver'] == 't'){
					echo "<td><button type=\"button\" class='btn btn-primary' role='button' onclick=\"offer_Display()\">Manage your offers</button></td>";
					echo "<td><button type=\"button\" class='btn btn-primary' role='button' onclick=\"accept_Display()\">Accept bids</button></td>";
				}
				?>
				</tr>
			</table>
			<h2 class="text-center">Latest rides available</h2>
			<div id="divTable">
				<table id="table" class="table table-striped table-bordered" style="width:100%">
					<?php 
					$result = pg_query_params($db, 'SELECT * FROM advertisements WHERE email_of_driver <> $1 ORDER BY date_of_pickup, time_of_pickup', array($email)); 
					$counter = 1;
					echo "<thead>
						<tr>
							<th>S/N</th>
							<th>Email</th>
							<th>Advertisement ID</th>
							<th>Date and time created</th>
							<th>Starting location</th>
							<th>Ending location</th>
							<th>Time of pick-up</th>
							<th>Date of pick-up</th>
							<th>Offer status</th>
							<th>Driver self-select</th>
							<th>Place bid</th>
						</tr>
						</thead>";
					echo "<tbody>";
					while($row = pg_fetch_array( $result )) { 
						echo "<tr>";
						echo "<td>" . $counter . "</td>";
						echo "<td>" . $row[1] . "</td>";
						echo "<td>" . $row[0] . "</td>";
						echo "<td>" . $row[4] . "</td>";
						echo "<td>" . $row[2] . "</td>";
						echo "<td>" . $row[3] . "</td>";
						echo "<td>" . $row[6] . "</td>";
						echo "<td>" . $row[5] . "</td>";
						echo "<td>" . $row[7] . "</td>";
						echo "<td>" . $row[8] . "</td>";
						echo "<td class='table-fit'><a href='userCreateBid.php?id=", urlencode($row[0]), "'class='btn btn-primary' role='button'>Place Bid</a></td>";
						echo "</tr>";
						$counter++;
					}
					echo "</tbody>";
					?>
				</table>
			</div>
		</div>
		<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Scroll to top" data-toggle="tooltip" data-placement="left"><i class="fas fa-arrow-up"></i></a>
	</body>
</html>