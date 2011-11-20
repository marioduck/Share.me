<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php
//Login and registration forms, only show up when there is not a session cookie
	if(!isset($_SESSION['username'])){
		$greeting = "<h2>Join share.me now!</h2>"; 
		$greeting.= "<form action=\"registration.php\" method=\"post\">";
		$greeting.= "<p>Username: <input type=\"text\" name=\"username\" maxlength=\"40\"; />";
		$greeting.= "Email: <input type=\"text\" name=\"email\" maxlength=\"100\"; /> </p> ";
		$greeting.= "<p>Password: <input type=\"password\" name=\"password\"; /> </p>";
		$greeting.= "<input type=\"submit\" name=\"submit\" value=\"Join\" /> </form>";
		
		$greeting.= "<h2>Login to your account</h2>"; 
		$greeting.= "<form action=\"login.php\" method=\"post\">";
		$greeting.= "<p> Email: <input type=\"text\" name=\"email\" maxlength=\"100\"; />";
		$greeting.= "Password: <input type=\"password\" name=\"password\"; /> </p>";
		$greeting.= "<input type=\"submit\" name=\"submit\" value=\"Join\" /> </form>";
		
		echo $greeting;
	}
	else redirect_to('dashboard.php');
?>

<?php require("Includes/footer.php"); ?>