<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<div id="main_dash">
	<?php fist_time($_SESSION['username']); ?>
	<p> <a href="new_post.php">New post</a> </p>
</div>

<?php require("Includes/footer.php"); ?>