<?php
		//require("constants.php");
		$connection = mysql_connect("localhost", "root", "191942ko");
        if(!$connection){
            die("Conexi�n a la base de datos fall�: " . mysql_error());
        }
        
		$db_select = mysql_select_db("shareme", $connection);
        if(!$db_select){
            die("Selecci�n de Base de Datos fall�: " . mysql_error());
        }
        
        
?>