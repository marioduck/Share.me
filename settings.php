<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php 
	if(!isset($_SESSION['username'])){
		redirect_to("index.php");
	}
?>
<?php include("includes/header.php"); ?>
	<div id="greeting">
		<?php 
			if(isset($_GET['new'])){
				echo "It seems you're new here, we need to ask some questions about your blog first."; }
			else
				echo "This is the settings page, here you modify things in your account.";
		?>
		
		<form action="settings.php" method="post">
				<p>Blog Name:
					 <input type="text" name="post_title" value="" id="post_title"; />
				</p>
				<p>About:<br />
					<textarea rows="10" cols="55" name="post_content"; >What your blog is about and stuff...</textarea>
				</p>
				<p>Who will be able to see your blog?
					<input type="radio" name="privacy" value="1" checked> Everyone
					<input type="radio" name="privacy" value="2" > Only registered share.me users
					<input type="radio" name="privacy" value="3" > Only users that I accept 
				</p><p>Will your comments need moderation?
					<input type="radio" name="comment_mod" value="1" checked> Everyone can post right away
					<input type="radio" name="comment_mod" value="2" > Yes, I need to check every comment </p>
				<input type="submit" value="Save Settings" />
		</form>
		
	</div>
<?php require("includes/footer.php");  ?>