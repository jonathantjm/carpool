<?php  
include("header.php");
include("userNavBar.php");
$advertisementID = $_GET['id'];
$result = pg_query_params($db, "SELECT * FROM bid WHERE advertisementid = $1 ORDER BY price DESC", array($advertisementID));

if (isset($_POST['submit'])) {	
	$email = $_POST['email'];	
	$query = pg_query_params($db, "UPDATE bid SET status = 'Accepted' WHERE email = $1 AND advertisementid = $2", array($email, $advertisementID));	
	header("Location: userOffer.php");	
}
?>

<html>
	<body id="b13">
		<h2 class="text-center">Accept bid</h2>
		<div id="divTable">
			<table id='table' class='table table-striped table-bordered' style='width:100%'>
				<thead>
					<tr>
						<th>S/N</th>
						<th>Name</th>
						<th>Gender</th>
						<th>Contact number</th>
						<th>Price</th>
						<th>Accept</th>
					</tr>
				</thead>
				<tbody>
<?php	
$counter = 1;	
while($row = pg_fetch_array( $result )) {
	$info = pg_query_params($db, "SELECT * FROM useraccount WHERE email = $1", array($row[0]));
	$bidderInfo = pg_fetch_array($info);
	echo "<tr>";
	echo "<td>" . $counter . "</td>";
	echo "<td>" . $bidderInfo[0] . "</td>";
	echo "<td>" . $bidderInfo[1] . "</td>";
	echo "<td>" . $bidderInfo[2] . "</td>";
	echo "<td>" . $row[3] . "</td>";
	echo "<td><form action='' method='post'>
		<input type='hidden' name='email' value='" . $row[0] . "'/>
		<button type='submit' name='submit' class='btn btn-primary'>Accept</button></td>";
	$counter++;
}
?>
				</tbody>
			</table>
		</div>
	</body>
</html>
