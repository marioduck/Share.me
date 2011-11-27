<?php require_once("Includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php

    //Pending: users cannot follow themselves
    //Check if blog is already being followed, if it is, offer option to unfollow
    //Do not display 'follow' link if no user is logged in
    //Optional: if follow request is pending, display "request pending" instead of Follow
    
    global $connection;
    
    $date = date("Y-n-j");

    if(isset($_GET['blog'])) {
        $query = "SELECT user_id FROM blog WHERE name = '{$_GET['blog']}'";
        $result = mysql_query($query, $connection);
        $subscribed_id = mysql_fetch_array($result);
        
        $query = "INSERT INTO subscription (fecha_sub, accepted_request, user_id, subs_user_id) VALUES ('{$date}', 'a', '{$_SESSION['user_id']}', '{$subscribed_id[0]}')";
        $final_result = mysql_query($query, $connection);

        
        if($final_result) {
            echo "Succesfully followed {$_GET['blog']}";            
        } else {
            echo "Error! " . mysql_error();
        }
        
    } else {
        echo "No user selected";
    }

?>
