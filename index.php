<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
<?php 
include('login.php');
if(isset($_SESSION['login_id'])){
header("location: header.php#home");
}
?>
</head>
<body>
<h1>Login</h1>
<form name="login" action="" method="post" >
<input type="text" name="email"></input><br>
<input type="password" name="password"></input><br>
<input type="submit" name="login-submit" ></input>
<span><?php echo $error; ?></span>
</form>
<h1>Sign Up</h1>
<form name="signup" action="signup.php" method="post">
<label>First Name</label><input type="text" name="fname"></input><br>
<label>Last Name</label><input type="text" name="lname"></input><br>
<label>Email</label><input type="text" name="email"></input><br>
<label>Enter password</label><input type="password" name="password"></input><br>
<label>Date of Birth</label><input type="number" name="dd"></input><input type="number" name="mm"></input><input type="number" name="yy"></input><br>
<input type="submit"></input>
</form>
</body>
</html>
<form >
