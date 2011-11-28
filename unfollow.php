<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php
    
    global $connection;
    //Unfollow a user
    $query = "DELETE FROM subscription WHERE sub_id = {$_GET['sub_id']}";
    $result = mysql_query($query, $connection);
	confirm_query($result);
    
    if(mysql_affected_rows() == 1){
        echo "Unfollowed user succesfully! :(";
    } else {
        echo "Relationship not found"; 
    }
?>

<?php require("Includes/footer.php"); ?>