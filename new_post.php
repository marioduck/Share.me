<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php 
	if(!isset($_SESSION['username'])){
		redirect_to("index.php");
	}
?>
<?php include("includes/header.php"); ?>
	<div id="main">
		<div id="navigation">
		</div>
		<div id="page">
			<h2>Add Post</h2>  
			<form action="create_post.php?cat=<?php echo urlencode($sel_cat['id']); ?>" method="post">
				<p>Title:
					 <input type="text" name="post_title" value="" id="post_title"; />
				</p>
				<p>Content:<br />
					<textarea rows="10" cols="55" name="post_content"></textarea>
				</p>
				<input type="submit" value="Create Post" />
			</form>
			<br />
			<a href="index.php">Cancel</a>
		</div>
	</div>
<?php require("includes/footer.php");  ?>