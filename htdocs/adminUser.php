<?php  
include("header.php");
include("adminNavBar.php");

$result = pg_query($db, 'SELECT * FROM useraccount'); 
?>

<html>

<h1><b>View Existing User Information</b></h1>

<table>

<?php 

echo "<tr>
    <th>Name</th>
    <th>Gender</th>
    <th>Contact Number</th>
    <th>Email</th>
    <th>Password</th>
    <th>Vehicle Plate</th>
    <th>Capacity</th>
    <th>Is a driver?</th>
    </tr>";

while($row = pg_fetch_array( $result )) { 
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    echo "<td>" . $row[4] . "</td>";
    echo "<td>" . $row[5] . "</td>";
    echo "<td>" . $row[6] . "</td>";
    echo "<td>" . $row[7] . "</td>";
    echo "<td><a href='adminDeleteUser.php?email=".$row[3]."'>Delete</a></td>";
    echo "<td><a href='adminEditUser.php?email=".$row[3]."'>Edit</a></td>";
    echo "</tr>";
}

?>

</table>

<p>Add a new user</p>

<p><a href="adminCreateUser.php">New User</a></p>

</body>

</html>

