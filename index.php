<?php require_once("Includes/connection.php"); ?>
<?php include("includes/header.php"); ?>

	<h2>Registrate!</h2>
		<form action="registration.php" method="post">
				<p>Username:
					 <input type="text" name="username" maxlength="40" value=""; />
				Email:
					 <input type="text" name="email" maxlength="100" value=""; />
				</p>
				<p>Password:
					 <input type="password" name="password" value=""; />
				</p>
				<input type="submit" name="submit" value="JOIN NOW" />
			</form>

 <?php   
	global $connection;
	
    echo "Esto es una prueba, Desplegar usuarios: <br /><br />";
	
	$query = "SELECT * FROM user";
    
    $result_set = mysql_query($query, $connection);
    
    while ($users = mysql_fetch_array($result_set)) {
        echo $users['user_name'] . "<br />";
        
        echo $users['email'];
    }
    
?>

<?php require("Includes/footer.php"); ?>