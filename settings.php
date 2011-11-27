<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php 
	if(!isset($_SESSION['username'])){
		redirect_to("index.php");
	}
?>
<?php
	// START FORM PROCESSING
	// Only execute the form processing if the form has been submitted
	if (isset($_POST['submit'])){
		
		// !!! Initialize an array to hold our errors
		$errors = array();
	
		// !!! Need to check form data
		// !!! Need to check required fields
		// !!! Need to check fields with max lenghts
	
		
		// Clean up the form data before putting it in the database
		$blog_title 	= trim(mysql_prep($_POST['blog_title']));
		$blog_about 	= trim(mysql_prep($_POST['blog_about']));
		$privacy 		= mysql_prep($_POST['privacy']);
		$comment_mod 	= mysql_prep($_POST['comment_mod']);
	
		// !!! Database submission only proceeds if there were NO errors.
		if (empty($errors)) {
			//If a blog hasn't been created, the query will INSERT a new record
			if(fist_time($_SESSION['user_id'])){
				$query = "INSERT INTO blog (
							name, about, privacy, comment_mod, user_id)
						VALUES (
							'{$blog_title}', '{$blog_about}', '{$privacy}', {$comment_mod}, '{$_SESSION['user_id']}')";
			}else{ 
			//If a blog exists, the query will UPDATE the existing records
				$query = "UPDATE blog SET
							name = '{$blog_title}', about = '{$blog_about}', privacy = {$privacy}, comment_mod = {$comment_mod}
						WHERE user_id = {$_SESSION['user_id']}";
			}
			if ($result = mysql_query($query, $connection)) {
				$message = "Settings saved successfully"; 
				$directory = 'images/user_profile/' . $_SESSION['user_id'] . '/';
				if(file_exists($directory)){ $message = "adsfadsfasdf"; }
				else { mkdir($directory, 0700); }
			} else {
				$message = "The was an error, please try again.";
				$message .= "<br />" . mysql_error();
			}
		} else {
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
		// END FORM PROCESSING
	}
?>
<?php include("includes/header.php"); ?>
	<div id="greeting">
		<?php 
			//If got here because a blog has not been created "new" will be in the url, so previous data will not be fetched and
			//...a blog will have to be created to continue using the site
			if(!get_blog_inf($_SESSION['user_id'])
			{
				$message = "It seems you're new here, we need to ask some questions about your blog first."; 
			}
			else {
			//A blog has been created, will confirm and try to fetch data to fill form
				if(!isset($message)) { $message = "This is the settings page, here you can modify things in your account.";  }
				// !!! Need to confirm that blog is created
				// !!! Need to fetch information from blog to fill form
			}
			echo $message;
		?>
		<form action="settings.php" method="post">
				<p>Blog Name:
					 <input type="text" name="blog_title" value="" id="post_title"; />
				</p>
				<p>About:<br />
					<textarea rows="10" cols="55" name="blog_about"; >What your blog is about and stuff...</textarea>
				</p>
				<p>Who will be able to see your blog?
					<input type="radio" name="privacy" value="1" checked> Everyone
					<input type="radio" name="privacy" value="2" > Only registered share.me users
					<input type="radio" name="privacy" value="3" > Only users that I accept 
				</p><p>Will your comments need moderation?
					<input type="radio" name="comment_mod" value="1" checked> Everyone can post right away
					<input type="radio" name="comment_mod" value="2" > Yes, I need to check every comment </p>
				<input type="submit" name="submit" value="Save Settings" />
		</form>
		
	</div>
<?php require("includes/footer.php");  ?>