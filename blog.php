<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php
    global $connection;
	
	if(!isset($_GET['user'])){
		redirect_to('index.php'); }
	else {
		//Get blog info and print blog title
		$blog = get_blog_info($_GET['user'],3);
		$blog_user_id = $blog['user_id'];
		$blog_title = $blog['name'];
		echo "<span id=\"blog_title\"> <h1> {$blog_title} </h1> </span>";
		
		//Check if the user is logged in to display following options
		if(isset($_SESSION['user_id'])) {
		    $visitor = $_SESSION['user_id'];
		    display_following_option($_GET['user']);
		}
		else {
		    $visitor = 0;
		}
		
		//Check if the visitor can view the contents of the blog
		// !!! Need to implement some kind of message about not beign able to see the blog
		if(view_blog($blog['blog_id'], $visitor)){
		    if(isset($_GET['post'])){
			single_post($_GET['post'],$blog_user_id);
		    }
		    else {
			return_posts($_GET['user']);
		    }   
		}
	}
?>

<?php require("Includes/footer.php"); ?>