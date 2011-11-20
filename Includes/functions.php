<?php

//Sanitize queries

	//Confirm that query completed successfully
	function confirm_query($q_result){
		if(!$q_result){
			die("Database connection failed: " . mysql_error());
		}
	}
	
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
	
	function redirect_to($location = NULL){
		if($location != NULL){
			header("Location: {$location}");
			exit;
		}
	}
	
	//Check if user has completed the first-time setup
	function fist_time($username){
		global $connection;	
		$query = "SELECT user_id ";
			$query .= "FROM user ";
			$query .= "WHERE user_name = '{$username}'";
		$result = mysql_query($query, $connection);
		confirm_query($result);
			
		$found_user = mysql_fetch_array($result);
		$found_user = $found_user['user_id'];
		
		$query = "SELECT blog_id ";
			$query .= "FROM blog ";
			$query .= "WHERE user_id = '{$found_user}'";
		$result = mysql_query($query, $connection);
		confirm_query($result);
		
		if(mysql_num_rows($result) != 1){
			redirect_to('settings.php?new=1');
		}	
	}
?>