<?php  
include("header.php");
include("adminNavBar.php");

//Verify admin permissions
$isAdmin = $_SESSION['isAdmin'];
if($isAdmin == 'f') {
    $message = "You are not authorized to view this page!";
    echo "<script type='text/javascript'>alert('$message');
    window.location.href='login.php';
    </script>";
}

$result = pg_query($db, 'SELECT * FROM advertisements'); 
$counter = 1;
?>

<html>
<body>
<h1>View Existing Offers Information</h1>
<table class="table table-bordered">

<?php 

echo "<tr>
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
    </tr>";

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
    echo "<td class='table-fit'><a href='adminDeleteOffer.php?id=", urlencode($row[0]), "' class='btn btn-primary' role='button'>Delete</a></td>";
    echo "<td class='table-fit'><a href='adminEditOffer.php?id=", urlencode($row[0]), "'class='btn btn-primary' role='button'>Edit</a></td>";
    echo "</tr>";
    $counter++;
}

?>

    </table>
    <a href="adminCreateOffer.php" class='btn btn-primary' role='button'>Create a new offer?</a>
    <ul class='pager'>
        <li class='previous'><a href='adminPage.php'>Back</a></li>
    </ul>
</body>
</html>

