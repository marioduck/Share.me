<?php require_once("includes/session.php"); ?>
<?php require_once("Includes/connection.php"); ?>
<?php require_once("Includes/functions.php"); ?>
<?php include("includes/header.php"); ?>

<?php
    if(isset($_GET['subs_type'])) {
        sort_subscriptions($_GET['subs_type'], $_SESSION['user_id']);
    } else {
        echo "No parameter selected";
    }
?>

<?php require("Includes/footer.php"); ?>