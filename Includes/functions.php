<?php
	
	
	//Confirm that query completed successfully
	function confirm_query($q_result){
		if(!$q_result){
		 die("Database connection failed: " . mysql_error());
		}
	}//End

	//Sanitize queries
	function mysql_prep( $value ) {
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
		if( $new_enough_php ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysql_real_escape_string( $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}//End

	//Redirect
	function redirect_to($location = NULL){
		if($location != NULL){
			header("Location: {$location}");
			exit;
		}
	}//End
	
	//Check if user has completed the first-time setup (blog creation)
	function fist_time($user_id){
		global $connection;	
		
		//Try to fetch blog
		$query = "SELECT blog_id ";
			$query .= "FROM blog ";
			$query .= "WHERE user_id = '{$user_id}'";
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		//If no blog was found, redirect to settings
		if(mysql_num_rows($result) == 0){
			return true;
		}
		else {
			return false;
		}
	}
	
	function blog_exists($blog_name){
		global $connection;	
		
		//Try to fetch blog
		$query = "SELECT blog_id ";
			$query .= "FROM blog ";
			$query .= "WHERE name = '{$blog_name}'";
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		//If no blog was found, redirect to settings
		if(mysql_num_rows($result) == 0){
			return false;
		}
		else {
			return true;
		}
	}
	
	//Get information on blog using the user_id or blog name
	function get_blog_info($identifier, $identifier_type){
		//$identifier_type (1) = user_id
		//$identifier_type (2) = blog_name
		
		switch ($identifier_type) {
			case 1:
				$identifier_type = "user_id";
				break;
			case 2:
				$identifier_type = "blog_name";
				break;
		}
			
		global $connection;	
		
		//Try to fetch blog
		$query = "SELECT * ";
			$query .= "FROM blog ";
			$query .= "WHERE {$identifier_type} = {$identifier}";
			
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		$blog_info = mysql_fetch_array($result);
		
		if(mysql_num_rows($result) == 0){
			//If no blog exists, return false to know that a blog must be created before using the site.
			return false;
		}
		else {
			return $blog_info;
		}
	}
	
	function return_posts($blog_name)
	{
		global $connection;
		//Fetch all posts that are published (1) from the database
		$query = "SELECT * FROM posts ";
			$query .= "WHERE blog_id = (";
			$query .= "SELECT blog_id FROM blog WHERE user_id = (";
			$query .= "SELECT user_id FROM user WHERE user_name = '{$blog_name}') AND published=1)";
		
		$result = mysql_query($query, $connection);
		confirm_query($result);
			
		// !!! (for now) If there zero posts, will return to create blog
		if(mysql_num_rows($result) == 0){
			if(blog_exists($blog_name)){
					echo "<h3> The blog is here, but empty :( </h3>"; }
			else
					echo "<h3> Sorry, this blog does not exists :( </h3>";
		}
		
		//Print all posts in a lista
		while ($db_field = mysql_fetch_assoc($result)) {
			echo "<h3>" . $db_field['title'] . "</h3>";
			echo $db_field['date_created'] . "<BR>";
			echo "<p class=\"p_content\">" . $db_field['content'] . "</p>";
		}
	}
	
	function tags($post_tags)
	{

		strtolower();
		
	}
	

	//Get set of followers/subscriptions
	function get_subscriptions($subs_type, $userid){
		global $connection;
		
		$query = "SELECT * FROM subscription WHERE {$subs_type} = {$userid} AND accepted_request = 'a'";
		$subs = mysql_query($query, $connection);
		confirm_query($subs);
		
		return $subs;
	}
	
	
	//Determine wether user requested followers or subscriptions and display them
	function sort_subscriptions($subs_type, $userid) {
		global $connection;
		$subs = get_subscriptions($subs_type, $userid);
		
		if(mysql_num_rows($subs) == 0) {
			echo "No users to display";
		} else {			
			while($subs_field = mysql_fetch_assoc($subs)) {
				if ($subs_type == "user_id") {
					//User requested Following List
					$query = "SELECT * FROM user WHERE user_id = {$subs_field['subs_user_id']}";
				} elseif ($subs_type == "subs_user_id") {
					//User requested Followers List
					$query = "SELECT * FROM user WHERE user_id = {$subs_field['user_id']}";
				}
				$usernames = mysql_query($query, $connection);
				confirm_query($usernames);
				
				while($list = mysql_fetch_array($usernames)) {
					echo $list['user_name'] . "<br />";
				}
			}
		}
	}
	
	//Prints current following options depending on blog settings and user state
	function display_following_option($subscribed_to) {
		global $connection;
		
		//Checks if subscription already exists
		$query = "SELECT * FROM subscription WHERE user_id = {$_SESSION['user_id']} AND subs_user_id = (";
		$query .= "SELECT user_id FROM user WHERE user_name = '{$subscribed_to}')";
		$result = mysql_query($query, $connection);
		confirm_query($result);
		$status = mysql_fetch_array($result);
		
		if (mysql_num_rows($result) == 1) {
			//If relation already exists, check status
			if($status['accepted_request'] == 'a') {
				echo "<a href=\"unfollow.php?sub_id=" . urlencode($status['sub_id']) . "\">Unfollow {$_GET['blog']}</a>";
			} elseif ($status['accepted_request'] == 'p') {
				echo "Request pending. <a href=\"unfollow.php?sub_id=" . urlencode($status['sub_id']) . "\">Cancel Request</a>.";
			}
		} elseif(isset($_SESSION['user_id'])) {
			//If relation doesn't exist, check if user is logged in and prevent him from following himself
			if($_SESSION['username'] != $subscribed_to) {
			echo "<a href=\"follow.php?blog="  . urlencode($_GET['blog']) . "\">Follow {$_GET['blog']}</a>";
			}
		}
		
		
	}

	//Prints the image as html using a 50*50(px) format
	function get_user_pic($user_id)
	{
		$directory = "Images/user_profile/" . $user_id . "/";
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$user_pic = $file;
				}
			}
		closedir($handle);
		
		echo "<img src=\"Images/user_profile/{$user_id}/{$user_pic}\"  WIDTH=50 HEIGHT=50>";
		}
	}
	
	
?>