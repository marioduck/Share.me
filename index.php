<?php require_once("Includes/connection.php"); ?>

<?php include("includes/header.php"); ?>

<?php
    echo "Esto es una prueba, Desplegar usuarios: <br /><br />";

    global $connection;
    $query = "SELECT * FROM user";
    
    $result_set = mysql_query($query, $connection);
    
    while ($users = mysql_fetch_array($result_set)) {
        echo $users['name'] . "<br />";
        
        echo $users['email'];
    }
    
?>

<?php require("Includes/footer.php"); ?>