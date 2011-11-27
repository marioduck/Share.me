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
			$query .= "SELECT blog_id FROM blog WHERE name = '{$blog_name}') AND published=1";
		
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