<?php
ob_start();  
include("header.php");
include("userNavBar.php");

$result = pg_query($db, "SELECT * FROM bid B, advertisements A
	WHERE B.advertisementid = A.advertisementid 
	AND email = '" . $_SESSION['user'] . "';"); 
$historyResult = pg_query($db, "SELECT * FROM bidhistory
	WHERE email = '" . $_SESSION['user'] . "';"); 
$locations = pg_query($db, "SELECT * FROM locations"); 
?>

<html>
	<body id="b2">
		<div class="container">
			<div class="row">
				<div class="col-md-10">
					<div style="margin-left:175px;">
						<h2 class="text-center"><b>Make a bid by searching for offers</b></h2>
					</div>
				</div>
				<div class="col-md-1 col-md-offset-1">
					<a href="userBidHistory.php" class='btn btn-primary' role='button'>View your past bids</a>
				</div>
			</div>
		</div>
		<div id="divBox">
			<form action="" method="post">
			<?php
			$locations_array = array();
			while($row = pg_fetch_array( $locations )) {
				$locations_array[] = $row[0];
			}
			echo "<strong>Start Location: </strong>";
			//echo "<select name= 'start_location' />";
			//echo "<option value= '' selected disabled hidden /> Choose here </option>";
			echo "<select name= \"start_location\" />";
			echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
			foreach ($locations_array as $location){
				echo "<option value ='".$location."' >".$location."</option>";
			}
			echo "</select><br/>";

			echo "<strong>End Location: </strong>";
			//echo "<select name='end_location' />";
			//echo "<option value= '' selected disabled hidden /> Choose here </option>";
			echo "<select name=\"end_location\" />";
			echo "<option value= \"\" selected disabled hidden /> Choose here </option>";
			foreach ($locations_array as $location){
				echo "<option value ='".$location."' >".$location."</option>";
			}
			echo "</select><br/>";
			?>
			<strong>Date Of Pickup: </strong> <input type="date" name="date_of_pickup"><br/>
			<strong>Time Range: </strong> <input type="time" name="start_time" step = "900"> <input type="time" name="end_time" step = "900"/><br/>
			<button type="submit" name="button" class="btn btn-primary">Submit</button>
		</div>
		</br>
<?php
	$counter = 1;
	if(isset($_POST['button'])){
		$start_location = $_POST['start_location'];
		$end_location = $_POST['end_location'];
		$date_of_pickup = $_POST['date_of_pickup'];
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$query_string = "SELECT * FROM advertisements WHERE ";
		if($start_location != ""){
			$query_string = $query_string . "start_location = '" . $start_location . "' AND ";
		}
		if($end_location != ""){
			$query_string = $query_string . "end_location = '" . $end_location . "' AND ";
		}
		if($date_of_pickup != ""){
			$query_string = $query_string . "date_of_pickup = '" . $date_of_pickup . "' AND ";
		}
		if($start_time != ""){
			$query_string = $query_string . "time_of_pickup >= '" . $start_time . "' AND ";
		}
		if($end_time != ""){
			$query_string = $query_string . "time_of_pickup <= '" . $end_time . "' AND ";
		}
		$query_string = $query_string . "closed = false
			AND 	advertisementid NOT IN (
					SELECT advertisementid from bid
					WHERE  email = '" . $_SESSION['user'] . "' 
					UNION 
					SELECT advertisementid from advertisements
					WHERE  email_of_driver = '" . $_SESSION['user'] . "');";
		$search_results = pg_query($query_string);
	echo "<div id='divForm'>";
		if(pg_num_rows($search_results) > 0){
		echo "<table id='table' class='table table-striped table-bordered' style='width:100%'>
		<thead>
			<tr>
				<th>S/N</th>
				<th>Start Location</th>
				<th>End Location</th>
				<th>Pick up date</th>
				<th>Pick up time</th>
				<th>Bid</th>
			</tr>
		</thead>
		<tbody>";
			while ($row = pg_fetch_assoc($search_results)){
				echo "<tr>";
					echo "<td>" . $counter . "</td>";
					echo "<td>" . $row['start_location'] . "</td>";
					echo "<td>" . $row['end_location'] . "</td>";
					echo "<td>" . $row['date_of_pickup'] . "</td>";
					echo "<td>" . $row['time_of_pickup'] . "</td>";
					echo "<td><a href='userCreateBid.php?id=".$row['advertisementid']."' class='btn btn-primary' role='button'>Bid</a></td>";
				echo "</tr>";
				$counter++;
			}	
			echo "</tbody>
			</table>";
		}
		else{
			echo "<br>Sorry. No matching rides found!<br><br>";
		}
	}
	echo "</div>";
?>
		<div id="divText">
			<h2 class="text-center"><b>Your bids</b></h2>
		</div>
		<div id="divTable">
<?php 
$count = 1;
if (pg_num_rows($result) > 0){
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
				<th>Delete</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>";
	while($row = pg_fetch_assoc( $result )) { 
		echo "<tr>";
			echo "<td>" . $count . "</td>";
			echo "<td>" . $row['start_location'] . "</td>";
			echo "<td>" . $row['end_location'] . "</td>";
			echo "<td>" . $row['date_of_pickup'] . "</td>";
			echo "<td>" . $row['time_of_pickup'] . "</td>";
			echo "<td>" . $row['price'] . "</td>";
			echo "<td>" . $row['status'] . "</td>";
			echo "<td><a href='userDeleteBid.php?id=".$row['advertisementid']."' class='btn btn-primary' role='button'>Delete</a></td>";
			echo "<td><a href='userEditBid.php?id=".$row['advertisementid']."' class='btn btn-primary' role='button'>Edit</a></td>";
		echo "</tr>";
		$count++;
	}
	echo "</tbody>
		</table>
	</div>";	
}
else{
	echo "<div id='divBox'>
		<span style='font-size:20px;'>You do have any bids!</span>
		</div>";
}
?>
		<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Scroll to top" data-toggle="tooltip" data-placement="left"><i class="fas fa-arrow-up"></i></a>
	</body>
</html>
