<?php  
include("header.php");

$emailError="";
$passwordError="";

if (isset($_POST['submitForm'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = pg_query_params($db, 'SELECT password, is_admin FROM useraccount WHERE email= $1', array($email)); 
    $row = pg_fetch_array($result);

    $error = pg_last_error($db);
    if (!isset($row[0])) {
        $emailError = "There is no such email in use!";
    } else if(!($password == $row[0])){
        $passwordError = "Wrong password!";
    } else {
        if($row[1] == "t"){
			$_SESSION['user']= $email;
            $_SESSION['isAdmin']= 't';
            header("Location: adminPage.php");
        } else {
            $_SESSION['user']= $email;
			$_SESSION['isAdmin']= 'f';
            header("Location: userPage.php");
        }
    }
}
?>

<html>
	<body id="loginpage">
		<div class ="wrapper">
			<div class="col-lg-12" style="height:150px;"></div>
			<h2 class="text-center">Login</h2>
			<div id="divForm">
				<form method="post" name="form" action="login.php">
					<div class='form-group'>
						<label for="inputEmail">Email: </label>
						<input type="email" name="email" class="form-control" id="inputEmail" required>
						<span style="color:red"><?php echo $emailError;?></span>
					</div>
					<div class='form-group'>
						<label for="inputPassword">Password: </label>
						<input type="password" name="password" class="form-control" id="inputPassword" required>
						<span style="color:red"><?php echo $passwordError;?></span>
					</div>
					<button type="submit" name="submitForm" class="btn btn-primary">Submit</button>
				</form>
			</div>
			<div class="push"></div>
		</div>
		<footer class="footer">Copyright &copy; CS2102 Team 20</footer>
	</body>
</html>
