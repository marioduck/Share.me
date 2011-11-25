<?php require_once("includes/session.php"); ?>
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
						$user_greeting = "Welcome back, "
							. "<a href=\"profile.php?u="
							. $_SESSION['username']
							. "\"> {$_SESSION['username']} </a>"; 
						$user_greeting.= "<p><a href=\"logout.php\"> Logout?</a></p>";
						echo $user_greeting;  
					}
				?>
			</div>
		</div>
		<div id="main">