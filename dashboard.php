<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<div id="main_dash">
	<?php
		//Check if user has created the blog, if not redirect to settings so the blog can be created
		//User will not be able to pass trough here if his/her blog has not been created
		if(!get_blog_info($_SESSION['user_id'], 1)){
			redirect_to('settings.php?new=1'); 
		} 
	?>
	<p> <a href="new_post.php">New post</a> </p>
	<p> <a href="settings.php">Check and modify your settings</a> </p>

	<?php
		if(!get_subscriptions('user_id',3)){
			$following = 0;
		}else{
			$following = count(get_subscriptions('user_id',3));
		}
		$followers = count(get_subscriptions('subs_user_id',3));
		echo  "<p> <a href=\"subscriptions.php?subs_type=subs_user_id\">You have {$followers} followers</a> </p>";
		echo  "<p> <a href=\"subscriptions.php?subs_type=user_id\">You have {$following} subscriptions</a> </p>";
	?>
</div>

<?php require("Includes/footer.php"); ?>