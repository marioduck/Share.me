<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php	
	echo "Following <a href=\"subscriptions.php?subs_type=user_id\">" . mysql_num_rows(get_subscriptions("user_id", $_SESSION['user_id'])) . " people</a>.<br/>";
	echo "Being followed by <a href=\"subscriptions.php?subs_type=subs_user_id\">" . mysql_num_rows(get_subscriptions("subs_user_id", $_SESSION['user_id'])) . " people</a>.";
?>


<div id="main_dash">
	<?php
		//Check if user has created the blog, if not redirect to settings so the blog can be created
		//User will not be able to pass trough here if his/her blog has not been created
		if(fist_time($_SESSION['user_id'])) { 
				redirect_to('settings.php?new=1'); 
			} 
	?>
	<p> <a href="new_post.php">New post</a> </p>
</div>

<?php require("Includes/footer.php"); ?>