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

$result = pg_query($db, 'SELECT * FROM useraccount'); 
$counter = 1;
?>

<html>
<body>
<h1 class='text-center'>View Existing User Information</h1>
<table class='table table-bordered'>

<?php 

echo "<tr>
    <th>S/N</th>
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

?>

</table>
<a href="adminCreateUser.php" class='btn btn-primary' role='button'>Create a new user account?</a>
<ul class='pager'>
<li class='previous'><a href='adminPage.php'>Back</a></li>
</ul>
</body>

</html>

