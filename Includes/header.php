<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<html>
	<head>
		<title>Share.me</title>
	</head>
	<body>
		<div>
			<h1><a href="index.php">Share.me</a></h1>
			<div id="header_gretting">
				<?php 
					if(isset($_SESSION['username'])){
						if(fist_time($_SESSION['user_id'])){
							$user_greeting = "Welcome back, " . $_SESSION['username']; }
						else{
							$blog_info = get_blog_info($_SESSION['user_id'], 1);
							$user_greeting = "Welcome back, " . "<a href=\"blog.php?blog=" . $blog_info['name'] . "\">" . $_SESSION['username'] . "</a>"; }
						$user_greeting.= "<p><a href=\"logout.php\"> Logout?</a></p>";
						echo $user_greeting;  
					}
				?>
			</div>
		</div>
		<div id="main">