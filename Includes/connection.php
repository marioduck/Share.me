<?php
		//require("constants.php");
		$connection = mysql_connect("localhost", "root", "tentomon");
        if(!$connection){
            die("Conexión a la base de datos falló: " . mysql_error());
        }
        
		$db_select = mysql_select_db("shareme", $connection);
        if(!$db_select){
            die("Selección de Base de Datos falló: " . mysql_error());
        }
        
        
?>