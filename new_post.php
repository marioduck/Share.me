<?php require_once("Includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	// START FORM PROCESSING
	// Only execute the form processing if the form has been submitted
	if (isset($_POST['save']) || isset($_POST['publish']) ){
		
		// !!! Initialize an array to hold our errors
		$errors = array();
	
		// !!! Need to check form data
		// !!! Need to check required fields
		// !!! Need to check fields with max lenghts
	
		
		// Clean up the form data before putting it in the database
		$post_title = trim(mysql_prep($_POST['post_title']));
		$post_content = mysql_prep($_POST['post_content']);
		$post_date = mysql_prep($_POST['p_date']);
		$post_slug = mysql_prep($_POST['post_slug']);
		$post_category = $_POST['category'];
		
		//Get blog info using 1 for user_id
		$blog_info = get_blog_info($_SESSION['user_id'], 1);
		$blog_id = $blog_info['blog_id'];
		
		// Save = Publish, otherwise will be saved as a draft
		if(isset($_POST['publish'])) {   $post_published = 1;   }
		else {   $post_published = 0;   }
	
		// !!! Database submission only proceeds if there were NO errors.
		if (empty($errors)) {
			$query = "INSERT INTO posts (
						content, title, date_created, published, slug, blog_id, cat_id
					) VALUES (
						'{$post_content}', '{$post_title}', '{$post_date}', {$post_published}, '{$post_slug}', '{$blog_id}', '{$post_category}'
					)";
			if ($result = mysql_query($query, $connection)) {
				// as is, $message will still be discarded on the redirect
				if($post_published == 1){ 
					$message = "The post was successfully published."; }
				else {
					$message = "The post was successfully saved as a draft."; }
			} else {
				$message = "The post could not be published.";
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
	<h2>Create your post</h2>
	<?php if (!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
	<?php if (!empty($errors)) { display_errors($errors); } ?>
	<div id="post_form">
		<form action="new_post.php" method="post">
			<p>Post Title:
				 <input type="text" name="post_title" value="" id="post_title"; />
			</p>
			<p>Slug
				 <input type="text" name="post_slug" value="" id="post_slug"; />
			</p>
			<p>Content:<br />
				<textarea rows="10" cols="55" name="post_content"; >What are we doing today?</textarea>
			</p>
			<p>Publish date: 
				<input type="text" name="p_date" value="<?php echo date("Y-n-j"); ?>" id="p_date"; /> 
			</p>
			<p>Category:
				<select name="category">
				<?php
					//Grab existing categories
					$categories = get_category();
					foreach($categories as $category){
						echo "<option value={$category['cat_id']}>";
						echo "{$category['cat_name']}";
						echo "</option>";
					}
				?>
				</select>
			</p>
			<input type="submit" name="publish" value="Publish Post" />
			<input type="submit" name="save" value="Save as Draft" />
		</form>
	</div>
<?php include("includes/footer.php"); ?>