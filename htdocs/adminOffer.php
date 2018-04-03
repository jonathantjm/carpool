<?php  
include("header.php");
include("adminNavBar.php");

$result;
$locations = pg_query($db, "SELECT * FROM locations");
if (isset($_POST['submit'])){
    $searchTermStart = $_POST['searchForOfferStart'];
    $searchTermEnd = $_POST['searchForOfferEnd'];
    $result = pg_query_params($db, 'SELECT * FROM advertisements WHERE start_location = $1 AND end_location = $2', array($searchTermStart, $searchTermEnd));
} else {
    $result = pg_query($db, 'SELECT * FROM advertisements'); 
}

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
?>

<html>
<body>
<h2>View Existing Offers Information</h2>
<form action='' method='post'>
    <div class='form-group'>
        <label>Search for offers based on start and end locations: </label></br>
    <?php
        $locations_array = array();
        while($adv_row = pg_fetch_array( $locations )) {
            $locations_array[] = $adv_row[0];
    }


    echo "<strong> Start Location: </strong>";
    echo "<select name= \"searchForOfferStart\" />";
    foreach ($locations_array as $location){
        if ($location == $row[1]){
            echo "<option value =\"".$location."\" selected>".$location."</option>";
        }
        else{
            echo "<option value =\"".$location."\" >".$location."</option>";
        }
    }
    echo "</select>";
    
    echo "<strong> End Location: </strong>";
    echo "<select name=\"searchForOfferEnd\" />";
    foreach ($locations_array as $location){
        if ($location == $row[2]){
            echo "<option value =\"".$location."\" selected>".$location."</option>";
        }
        else{
            echo "<option value =\"".$location."\" >".$location."</option>";
        }
    }
    echo "</select>";

    ?>
        <input type="submit" name="submit" class='btn btn-primary' value="Search">
        <a href="adminOffer.php" class='btn btn-primary' role='button'>Refresh?</a>
    </div>
</form>

	<table id="table" class="table table-striped table-bordered" style="width:100%">
<?php 
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
		<th>Edit</th>
		<th>Delete</th>
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
    echo "<td class='table-fit'><a href='adminEditOffer.php?id=", urlencode($row[0]), "'class='btn btn-primary' role='button'>Edit</a></td>";
    echo "<td class='table-fit'><a href='adminDeleteOffer.php?id=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Delete</a></td>";
    echo "</tr>";
    $counter++;
}
echo "</tbody>";
?>

    </table>
    <a href="adminCreateOffer.php" class='btn btn-primary' role='button'>Create a new offer?</a>
    <ul class='pager'>
        <li class='previous'><a href='adminPage.php'>Back</a></li>
    </ul>
</body>
</html>

