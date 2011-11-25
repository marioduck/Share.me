<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php 
    global $connection;
		
	//Need to check form data
	//Need to check required fields
	//Need to check fields with max lenghts
	
	//Prepare data for query
	$email = trim(mysql_prep($_POST['email']));
	$password = trim(mysql_prep($_POST['password']));
	$hashed_password = sha1($password);
	
	$query = "SELECT user_name, user_id ";
			$query .= "FROM user ";
			$query .= "WHERE email = '{$email}' ";
			$query .= "AND password = '{$hashed_password}' ";
			$result = mysql_query($query, $connection);
			confirm_query($result);
			
			if(mysql_num_rows($result) == 1){
				$found_user = mysql_fetch_array($result);
				$_SESSION['username'] = $found_user['user_name'];
				$_SESSION['user_id'] = $found_user['user_id'];
				redirect_to("dashboard.php");
			} 
			else{
				echo "Problem connecting";
			}
	
?>