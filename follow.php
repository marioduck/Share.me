<?php require_once("Includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>

<?php
    
    global $connection;
    
    $date = date("Y-n-j");

    if(isset($_GET['u'])) {
        $query = "SELECT user_id FROM user WHERE user_name = '{$_GET['u']}'";
        $result = mysql_query($query, $connection);
        $subscribed_id = mysql_fetch_array($result); 
        
        $query = "SELECT user_id FROM user WHERE user_name = '{$_SESSION['username']}'";
        $result = mysql_query($query, $connection);
        $subscriber_id = mysql_fetch_array($result); 
        
        $query = "INSERT INTO subscription (fecha_sub, accepted_request, user_id, subs_user_id) VALUES ('{$date}', 'a', '{$subscriber_id[0]}', '{$subscribed_id[0]}')";
        $final_result = mysql_query($query, $connection);

        
        if($final_result) {
            echo "Succesfully followed {$_GET['u']}";            
        } else {
            echo "Error! " . mysql_error();
        }
        
    } else {
        echo "No user selected";
    }

?>
