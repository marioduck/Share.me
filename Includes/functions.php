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
	
	
	//Get information on blog using (1)user_id, (2)blog_name, (3)user_name or (4)blog_id
	function get_blog_info($identifier, $identifier_type){
		
		switch ($identifier_type) {
			case 1:
				$identifier_type = "user_id";
				break;
			case 2:
				$identifier_type = "blog_name";
				break;
			case 3:
				$identifier_type = "user_id";
				$identifier = get_user_info($identifier, 2);
				$identifier = $identifier['user_id'];
				break;
			case 4:
				$identifier_type = "blog_id";
				break;
		}
			
		global $connection;	
		
		//Try to fetch blog
		$query = "SELECT * ";
			$query .= "FROM blog ";
			$query .= "WHERE {$identifier_type} = '{$identifier}'";
			
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
	
	//Default value, will search with user_id
	//$identifier_type (2) = user_name
	function get_user_info($identifier, $identifier_type = 1){
		switch ($identifier_type) {
			case 1:
				$identifier_type = "user_id";
				break;
			case 2:
				$identifier_type = "user_name";
				break;
		}
		
		global $connection;
		
		$query = "SELECT * ";
			$query .= "FROM user ";
			$query .= "WHERE {$identifier_type} = '{$identifier}'";
			
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		$user_info = mysql_fetch_array($result);
		
		if(mysql_num_rows($result) == 0){
			//If no user exists, return false 
			return false;
		}
		else {
			return $user_info;
		}
	}
	
	function return_posts($user_name)
	{
		//Get user_id using the user_name
		$user_id = get_user_info($user_name, 2);
		$user_id = $user_id['user_id'];
		
		
		global $connection;
		//Fetch all posts that are published (1) from the database
		$query = "SELECT * FROM posts ";
			$query .= "WHERE blog_id = (";
			$query .= "SELECT blog_id FROM blog WHERE user_id = {$user_id}) AND published=1";
		
		$result = mysql_query($query, $connection);
		confirm_query($result);
			
		if(mysql_num_rows($result) == 0){
			if(get_blog_info($user_id,1)){
				echo "<h3> The blog is here, but empty :( </h3>";
				return;
			}
			else{
				/// !!! Need to implement 404
				echo "<h3> Sorry, this blog does not exists :( </h3>";
				return;
			}
		}
		
		//Print all posts in a list
		echo "<div id=\"post_list\">";
		while ($post = mysql_fetch_assoc($result)) {
			echo "<span class=\"single_post\"><h3> <a href=\"blog.php?user={$user_name}&post={$post['post_id']}\"> {$post['title']} </a> </h3>";
			echo $post['date_created'] . "<BR>";
			echo "<p class=\"post_content\">" . $post['content'] . "</p>";
			echo "</span>";
		}
		echo "</div>";
	}
	
	//Needs post_id to get post info, user_id to get the author's name (or username)
	function single_post($post_id, $user_id)
	{
		global $connection;
		
		//Fetch all fields regarding the selected post
		$query = "SELECT * ";
			$query .= "FROM posts ";
			$query .= "WHERE post_id = {$post_id} and published = 1";
			
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		$post_info = mysql_fetch_array($result);
		
		if(mysql_num_rows($result) > 0){
			//Check if author created a name
			$user = get_user_info($user_id);
			
			//If author did not save a name, the username will be displayed
			if($user['name'] == null){
				$author = $user['user_name'];
			} else {
				$author = $user['name'];
			}
			
			$category_name = get_category($post_info['cat_id']);
			$category_name = $category_name['cat_name'];
			
			//The post
			$post  = "<h2> {$post_info['title']} </h2>";
			$post .= "Posted on: {$post_info['date_created']}";
			$post .= " by {$author}";
			$post .= "<br /> <br /> <span id=\"post_content\"> {$post_info['content']} </span> <br /> <br />";
			$post .= "<span id=\"post_cat\"> Category: {$category_name} </span>";
			echo $post;
		}
		else {
			return false;
		}	
		
	}
	
	//Returns the file path of the image
	//If function is called using the extra argument, will return false or true 
	function get_user_pic($user_id, $check_if_exists = false)
	{
		global $connection;
		//Try to fetch the image name from the db using the user_id
		$query = "SELECT image_url FROM images ";
			$query .= "WHERE user_id = {$user_id}";
		
		//Run the query
		$result = mysql_query($query, $connection);
		confirm_query($result);
			
		// If a result was found, return the url of the image, otherwise return the default image. 
		if(mysql_num_rows($result) == 0){
			if($check_if_exists) {
				return false;
			}
			else {
				return "Images/user_profile/user.png";
			}
		}
		else {
			//Grab the name of the file
			$result = mysql_fetch_array($result);
			$user_pic = $result['image_url'];
			if($check_if_exists) {
				return true;
			}
			else {
				return "Images/user_profile/" . $user_id . "/" . $user_pic;
			}
		}
	}
	
	//If a cat_id is sent, will return the name. Otherwise will return an array with all categories
	function get_category($cat_id = 0){
		global $connection;
		
		//Prepare default query
		$query = "SELECT * FROM categories";
		
		if($cat_id != 0){
			$query .= " WHERE cat_id = {$cat_id}";
		}
		
		//Run the query
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		
		//Return the array or just one variable (depending of $cat_id)
		if(mysql_num_rows($result) > 0){
			//Save results in an array
			if($cat_id != 0){
				$result = mysql_fetch_array($result);
				return $result;
			}
			else {
				$array = array();
				while ($category = mysql_fetch_assoc($result)) {
					$array[] = $category;
				}
				return $array;
			}
		}
		else {
			return false;
		}
			
	}
	
	//Returns true/false to check if a blog can be viewed
	//blog_id = blog you want to see
	//user_id = your own user_id (the visitor's id)
	function view_blog($blog_id, $user_id){
		$blog = get_blog_info($blog_id, 4);

		switch($blog['privacy']){
			//Everyone can view the blog
			case 1:
				return true;
				break;
			
			//Only active users can view the blog
			case 2:
				if(isset($_SESSION['user_id'])){
					return true;	}
				else{
					return false;	}
				break;
			
			//Need to "ask permission" to view the blog
			case 3:
				if(!isset($_SESSION['user_id'])){
					return false;	}
				else{
					global $connection;
					
					$query  = "SELECT * FROM subscription ";
					$query .= "WHERE user_id = {$user_id} AND ";
					$query .= "subs_user_id = {$blog['user_id']}";
					
					//Run the query
					$result = mysql_query($query, $connection);
					confirm_query($result);
					
					if(mysql_num_rows($result) > 0){
						return true;	}
					else{
						return false;	}
				}
				break;
		}		
		
	}
	
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
				echo "<a href=\"unfollow.php?sub_id=" . urlencode($status['sub_id']) . "\">Unfollow {$_GET['user']}</a>";
			} elseif ($status['accepted_request'] == 'p') {
				echo "Request pending. <a href=\"unfollow.php?sub_id=" . urlencode($status['sub_id']) . "\">Cancel Request</a>.";
			}
		} elseif(isset($_SESSION['user_id'])) {
			//If relation doesn't exist, check if user is logged in and prevent him from following himself
			if($_SESSION['username'] != $subscribed_to) {
			echo "<a href=\"follow.php?blog="  . urlencode($_GET['user']) . "\">Follow {$_GET['user']}</a>";
			}
		}
		
		
	}
	
	//Get set of followers/subscriptions
	function get_subscriptions($subs_type, $userid){
		global $connection;
		
		$query = "SELECT * FROM subscription WHERE {$subs_type} = {$userid} AND accepted_request = 'a'";
		$subs = mysql_query($query, $connection);
		confirm_query($subs);

		return $subs;
	}
	
	//Display "following" or "followers" lists
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
					echo "<a href=\"blog.php?user={$list['user_name']}\">{$list['user_name']}</a>";
				}
			}
		}
	}
	
	function get_number_notifications($user_id)
	{
		global $connection;
		
		$query  = "SELECT notification_id FROM notifications ";
		$query .= "WHERE user_id = {$user_id}";
		
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		return mysql_num_rows($result);
	}
?>