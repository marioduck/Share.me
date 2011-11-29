<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php 
	if(!isset($_SESSION['username'])){
		redirect_to("index.php");
	}
?>
<?php
	define('UPLOAD_DIR','images/user_profile/');
	// START FORM PROCESSING
	// Only execute the form processing if the form has been submitted
	if (isset($_POST['submit'])){
		
		// !!! Initialize an array to hold our errors
		// $errors = array();
		
		$errors = 0;
	
		// !!! Need to check form data
		// !!! Need to check required fields
		// !!! Need to check fields with max lenghts
		// !!! Need to capture and insert/update name on table USER
	
		
		// Clean up the form data before putting it in the database
		$blog_title 	= trim(mysql_prep($_POST['blog_title']));
		$blog_about 	= trim(mysql_prep($_POST['blog_about']));
		$privacy 	= mysql_prep($_POST['privacy']);
		$comment_mod 	= mysql_prep($_POST['comment_mod']);
		$real_name 	= trim(mysql_prep($_POST['real_name']));
		
				
		//If user uploaded an image, check for any errors.
		if($_FILES['image']['size'] > 0) { 
			
			//Flags for erros, assumes the upload is unnaceptable until proven right.
			$sizeOK = false;
			$typeOK = false;
			
			//Replace spaces and dashes with underscores and assign to a variable, and prepares the filename with stripslashes
			$search = array(' ', '-');
			$file = strtolower(str_replace($search, '_', $_FILES['image']['name']));
			$file = stripslashes($file);
			
			// !!! Need to place define ('MAX_FILE_SIZE',9000000); in a constants.php
			
			//Create an array of permitted types
			$permitted = array('image/gif','image/jpeg','image/pjpeg','image/png');
			
			//Check that file is within the permitted size
			if ($_FILES['image']['size'] >0 && $_FILES['image']['size'] <= 9000000) {
				$sizeOK=true;
			}
	
			//Check the file type using the $type array
			foreach ($permitted as $type) {
				if ($type == $_FILES['image']['type']) {
						$typeOK = true;
						break;
				}
			}
						
			if(!$sizeOK || !$typeOK) {
				$errors++;
				
			} 
		
		}
		
	
		// !!! Database submission only proceeds if there were NO errors.
		if ($errors == 0) {
			//If a blog hasn't been created, the query will INSERT a new record
			if(!get_blog_info($_SESSION['user_id'],1)){
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
				//Will now INSERT or UPDATE the selected profile picture in the database
				//Check if an image was uploaded first
				if($_FILES['image']['size'] > 0) { 
					if(!get_user_pic($_SESSION['user_id'],true)){
						//Insert new record, and move the new image to the created directory
						$query2 = "INSERT INTO images (image_url, user_id) VALUES ('{$file}', {$_SESSION['user_id']})";
						mkdir(UPLOAD_DIR.$_SESSION['user_id']);
						$success = move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $_SESSION['user_id'] . '/' . $file);
					} else {
						//Update record -> delete old image -> move new image to directory
						$old_image = get_user_pic($_SESSION['user_id']);
						$query2 = "UPDATE images SET image_url = '{$file}' WHERE user_id = {$_SESSION['user_id']}";
						unlink($old_image);
						$success = move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $_SESSION['user_id'] . '/' . $file);
					}
					
					if($result = mysql_query($query2, $connection)){
						$message = "Settings saved successfully";
					}
				}
			} else {
				$message = "The was an error, please try again.";
			}
		} else {
			$message = "there was an error";
		}
		// END FORM PROCESSING
	}
?>
<?php include("includes/header.php"); ?>
	<div id="greeting">
		<?php 
			//Will use get_blog_info to check if a blog is linked to this account.
			//...a blog will have to be created to continue using the site
			if(!get_blog_info($_SESSION['user_id'],1)){
				$message = "It seems you're new here, we need to ask some questions about your blog first."; 
			}
			else {
				//A blog has been created, will confirm and try to fetch data to fill form
				if(!isset($message)) {
					$message = "This is the settings page, here you can modify things in your account.";  
					$blog_info = get_blog_info($_SESSION['user_id'],1); //Save info to fill form
				}
			}
			echo $message;
		?>
	</div>
	<div id="settings_form">
		<form action="settings.php" enctype="multipart/form-data" method="post">
				<p>Blog Title:
					 <input type="text" name="blog_title" value="<?php if(isset($blog_info)){echo $blog_info['name'];}?>" id="post_title"; />
				</p>
				<p>Your name (will only be used in your posts):
					<input type="text" name="real_name" value="" id="real_name"; />
				</p>
				<p>Profile Picture:
					<input type="file" name="image">
				</p>
				<p>About:<br />
					<textarea rows="10" cols="55" name="blog_about"; ><?php if(isset($blog_info)){echo $blog_info['about'];}?></textarea>
				</p>
				<!-- If $blog_info is set, the information will fill the form -->
				<p>Who will be able to see your blog?
					<input type="radio" name="privacy" value="1" <?php if(isset($blog_info)){if($blog_info['privacy'] == 1) {echo "checked";}}?> > Everyone
					<input type="radio" name="privacy" value="2" <?php if(isset($blog_info)){if($blog_info['privacy'] == 2) {echo "checked";}}?> > Only registered share.me users
					<input type="radio" name="privacy" value="3" <?php if(isset($blog_info)){if($blog_info['privacy'] == 3) {echo "checked";}}?> > Only users that I accept 
				</p><p>Will your comments need moderation?
					<input type="radio" name="comment_mod" value="0" <?php if(isset($blog_info)){if($blog_info['comment_mod'] == 0) {echo "checked";}}?> > Everyone can post right away
					<input type="radio" name="comment_mod" value="1" <?php if(isset($blog_info)){if($blog_info['comment_mod'] == 1) {echo "checked";}}?> > Yes, I need to check every comment </p>
				<input type="submit" name="submit" value="Save Settings" />
		</form>
	</div>
	</div>
<?php require("includes/footer.php");  ?>