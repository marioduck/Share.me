<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php

    //Check if user requested their Followers or Following list
    if(isset($_GET['subs_type'])) {
        if($_GET['subs_type'] == "user_id"){
            echo "<h2>Users you're following</h2>";
        } elseif ($_GET['subs_type'] == "subs_user_id") {
            echo "<h2>Users who follow you</h2>";
        }
        //Call function which will retrieve subscriptions list
        sort_subscriptions($_GET['subs_type'], $_SESSION['user_id']);
    } else {
        echo "No parameter selected";
    }
?>

<?php require("Includes/footer.php"); ?>