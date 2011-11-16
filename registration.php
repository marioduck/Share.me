<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php 
    global $connection;
	
	$errors = array();
		
	//Need to check form data
	//Need to check required fields
	//Need to check fields with max lenghts
	
	$username = trim(mysql_prep($_POST['username']));
	$password = trim(mysql_prep($_POST['password']));
	$mail 	  = trim(mysql_prep($_POST['email'])); 
	
	$date	  = date("Y-n-j"); 
	$hashed_password = sha1($password);
		
	$query = "INSERT INTO user (user_name, password, email, reg_date) VALUES ('{$username}', '{$hashed_password}', '{$mail}', '{$date}')";
	$result = mysql_query($query, $connection);
	
	if($result){
		$message = "The user was created";
	} 
	else{
		$message = "The user could not be created";
		$message .= "<br />" . mysql_error();
	}
	
	echo $message;
	
?>