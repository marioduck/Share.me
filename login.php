<?php require_once("Includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php require("Includes/header.php"); ?>
<?php
    global $connection;
    
    
    
    $username = trim(mysql_prep($_POST['user_name']));
    $password = trim(mysql_prep($_POST['password']));
    $hashed_password = sha1($password);
    
    $query = "SELECT * 
                FROM user 
                WHERE user_name = '{$username}' 
                AND password = '{$hashed_password}'";
    
    $result = mysql_query($query, $connection);
    
    if($result)
    {
        $found_user = mysql_fetch_array($result);
        $_SESSION['user_name'] = $found_user['user_name'];
        
        echo "Welcome " . $found_user['user_name'];
    } else {
        echo "FAILED " . mysql_error();
    }
?>

<?php require("Includes/footer.php");