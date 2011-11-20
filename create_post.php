<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php 
	$errors = array();
	$required_fields = array('post_title', 'post_content');
	foreach($required_fields as $fieldname){
		if(!isset($_POST[$fieldname]) || empty($_POST[$fieldname])){
			$errors[] = $fieldname;
		}//End IF
	}//End foreach
	
	$fields_max_lenght = array('post_title' => '25');
	foreach($fields_max_lenght as $fieldname => $maxlenght){
		if(strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlenght){
			$errors[] = $fieldname;
		 }//End IF
	}//End foreach
	
	if(!empty($errors)){
		redirect_to("new_post.php?cat={$_GET['cat']}");
	}
?>
<?php
	$post_date= date("Y-m-d");
	$post_title= mysql_prep($_POST['post_title']);
	$post_content= mysql_prep($_POST['post_content']);
	$cat_id= mysql_prep($_GET['cat']);
	
	$query = "INSERT INTO post (date, title, content, category_id) VALUES ('{$post_date}', '{$post_title}', '{$post_content}', '{$cat_id}')";
	$result = mysql_query($query, $connection);
	if ($result) {
		// Success!
		header("Location:content.php");
		exit;
	} else { 
		// Display error message.
		echo "<p>Post creation failed.</p>";
		echo "<p>" . mysql_error() . "</p>";
	}//End IF/Else
?>
