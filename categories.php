<?php require_once("includes/connection.php"); ?>
<?php include("includes/header.php"); ?>

<?php
    global $connection;
    $query = "SELECT * FROM post_categories ORDER BY RAND() LIMIT 10";
    $result = mysql_query($query, $connection);
    
    echo "<h2>Explora:</h2>";
    
    while ($categories = mysql_fetch_array($result)) {
        echo "<h3><a href=\"" .
            urlencode($categories['cat_name']) .
                "\"> {$categories['cat_name']}</a></h3>";
		
        
    }
?>

<?php require("Includes/footer.php"); ?>