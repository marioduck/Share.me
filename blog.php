<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php
	//Call function which will display current following option
	display_following_option($_GET['blog']);
?>

<?php
    global $connection;
	
	if(!isset($_GET['blog'])){
		redirect_to('index.php'); }
	else {
		$blog_name = $_GET['blog'];
		
		return_posts($blog_name);
	}
?>

<?php require("Includes/footer.php"); ?>