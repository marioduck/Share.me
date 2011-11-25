<?php 
	session_start();
	
	function logged_in() {
		return isset($_SESSION['username']);
	}
	
	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("index.php?m=3");
		}
	}
?>