<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php
    global $connection;
	
	if(!isset($_GET['u'])){
		redirect_to('index.php'); }
	else {
		$user_profile = $_GET['u'];
		
		return_posts($user_profile);
	}
?>

<?php require("Includes/footer.php"); ?>