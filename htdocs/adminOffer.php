<?php  
$db = pg_connect("host=localhost port=5432 dbname=car_pooling user=postgres password=25071995h!");
$result = pg_query($db, 'SELECT * FROM advertisements'); 
?>

<html>

<h1><b>View Existing Offers Information</b></h1>

<table>

<?php 

echo "<tr>
    <th>Email</th>
    <th>Advertisement ID</th>
    <th>Date and time created</th>
    <th>Starting location</th>
    <th>Ending location</th>
    <th>Time of pick-up</th>
    <th>Date of pick-up</th>
    <th>Offer status</th>
    <th>Driver self-select</th>
    </tr>";

while($row = pg_fetch_array( $result )) { 
    echo "<tr>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[4] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    echo "<td>" . $row[6] . "</td>";
    echo "<td>" . $row[5] . "</td>";
    echo "<td>" . $row[7] . "</td>";
    echo "<td>" . $row[8] . "</td>";
    echo "<td><a href='adminDeleteOffer.php?id=".$row[0]."'>Delete</a></td>";
    echo "<td><a href='adminEditOffer.php?id=".$row[0]."'>Edit</a></td>";
    echo "</tr>";
}

?>

</table>

<p>Add a new bid</p>

<p><a href="adminCreateOffer.php">New bid</a></p>

</body>

</html>

