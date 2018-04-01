<?php  
include("header.php");
include("adminNavBar.php");

$result;
if(isset($_POST['submit'])){
    $searchTerm = $_POST['searchForUser'];
    $result = pg_query_params($db, 'SELECT * FROM useraccount WHERE email = $1 OR name = $1', array($searchTerm));
}else{
    $result = pg_query($db, 'SELECT * FROM useraccount'); 
}
$counter = 1;

echo "<script type='text/javascript' class='init'>
		$(document).ready(function() {
			$('#table').DataTable();
		});
	</script>";
?>

<html>
<body>
<h2>View Existing User Information</h2>
<form action='' method='post'>
    <div class='form-group'>
        <label for='searchForUser'>Search for: </label>
        <input type='text' name='searchForUser' id='searchForUser' size='40'>
        <input type="submit" name="submit" class='btn btn-primary' value="Search">
        <a href="adminUser.php" class='btn btn-primary' role='button'>Refresh?</a>
    </div>
</form>
	
	<table id="table" class="table table-striped table-bordered" style="width:100%">
<?php 

echo "<thead>
	<tr>
		<th>S/N</th>
		<th>Name</th>
		<th>Gender</th>
		<th>Contact Number</th>
		<th>Email</th>
		<th>Password</th>
		<th>Vehicle Plate</th>
		<th>Capacity</th>
		<th>Is a driver?</th>
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
        echo "<td>" . $row[5] . "</td>";
        echo "<td>" . $row[6] . "</td>";
        echo "<td>" . $row[7] . "</td>";
        echo "<td class='table-fit'><a href='adminDeleteUser.php?email=", urlencode($row[3]), "'class='btn btn-primary' role='button'>Delete</a></td>";
        echo "<td class='table-fit'><a href='adminEditUser.php?email=", urlencode($row[3]), "'class='btn btn-primary' role='button'>Edit</a></td>";
        echo "</tr>";
        $counter++;
    }
echo "</tbody>";
?>

</table>
<a href="adminCreateUser.php" class='btn btn-primary' role='button'>Create a new user account?</a>
<ul class='pager'>
<li class='previous'><a href='adminPage.php'>Back</a></li>
</ul>
</body>

</html>

