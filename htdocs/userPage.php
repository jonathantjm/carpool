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
	window.location = "userAcceptBid.php";
  }
</script>
	<body id="b1">
		<div class="btn-group btn-group-justified" style="width:80%; margin:auto">
			<a href="userBid.php" class="btn btn-default" style="font-size:30px; background-color:#F3951D; border-color:#040404">Manage bid</a>
			<?php if (pg_fetch_assoc($driver)['is_driver'] == 't'){
				echo "<a href='userOffer.php' class='btn btn-default' style='font-size:30px; background-color:#F3951D; border-color:#040404'>Manage offer</a>";
			}
			?>
		</div>
		<div>
			<h2 class="text-center">Latest rides available</h2>
			<div id="divTable">
				<table id="table" class="table table-striped table-bordered" style="width:100%;">
					<?php 
					$result = pg_query_params($db, 'SELECT * FROM advertisements WHERE email_of_driver <> $1 AND closed = false ORDER BY date_of_pickup, time_of_pickup', array($email)); 
					$counter = 1;
					echo "<thead>
						<tr>
							<th>S/N</th>
							<th>Start location</th>
							<th>End location</th>
							<th>Pick-up date</th>
							<th>Pick-up time</th>
							<th>Driver self-select</th>
							<th>Place bid</th>
						</tr>
						</thead>";
					echo "<tbody>";
					while($row = pg_fetch_assoc( $result )) { 
						echo "<tr>";
						echo "<td>" . $counter . "</td>";
						echo "<td>" . $row['start_location'] . "</td>";
						echo "<td>" . $row['end_location'] . "</td>";
						echo "<td>" . $row['date_of_pickup'] . "</td>";
						echo "<td>" . $row['time_of_pickup'] . "</td>";
						if ($row['self_select'] == 'f'){
							echo "<td> No </td>";	
						}else{
							echo "<td> Yes </td>";	
						}
						echo "<td class='table-fit'><a href='userCreateBid.php?id=", urlencode($row['advertisementid']), "'class='btn btn-primary' role='button'>Place Bid</a></td>";
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