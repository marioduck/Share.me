<?php require_once("Includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php
    
    global $connection;
    
    $date = date("Y-n-j");

    if(isset($_GET['blog'])) {
        //Get the id of user to be followed
        $query = "SELECT user_id FROM user WHERE user_name = '{$_GET['blog']}'";
        $result = mysql_query($query, $connection);
        $subscribed_id = mysql_fetch_array($result);

        //check blog privacy settings before creating subscription
        $query = "SELECT privacy FROM blog WHERE user_id = {$subscribed_id[0]}";
        $result = mysql_query($query, $connection);
        $blog_setting = mysql_fetch_array($result);
                
        if ($blog_setting[0] == 1 || $blog_setting[0] == 2){
            $status = "a";
        } elseif ($blog_setting[0] == 3) {
            $status = "p";
        }
        
        $query = "INSERT INTO subscription (sub_id, sub_date, accepted_request, user_id, subs_user_id) VALUES (NULL, '{$date}', '{$status}', '{$_SESSION['user_id']}', '{$subscribed_id[0]}')";
        $final_result = mysql_query($query, $connection);
        
        if(mysql_affected_rows() == 1) {
            if ($status == "a") {
                echo "Succesfully followed {$_GET['blog']}"; 
            } elseif ($status == "p") {
                echo "Request sent to user";   
            }
            
        } else {
            echo "Error! " . mysql_error();
        }
        
        //If neccesary, a notification will be created to notify user about pending request
        if($status = "p") {
            $query = "INSERT INTO notifications (user_id, sub_id) VALUES ('{$subscribed_id[0]}', LAST_INSERT_ID())";
            $result = mysql_query($query, $connection);
            
            if(mysql_affected_rows() != 1) {
                echo "Unable to create notification " . mysql_error();
            }
        }
        
    } else {
        echo "No user selected";
    }
    
    //redirect_to("subscriptions.php?subs_type=user_id");
?>

<?php require("Includes/footer.php"); ?>