 <?php 
 if (isset($_POST['submit'])){
	 $target = "upload/"; 
	 $target = $target . basename( $_FILES['uploaded']['name']) ; 
	 $ok=1; 
	 
	 //Here we check that $ok was not set to 0 by an error 
	 if ($ok==0) 
	 { 
	 Echo "Sorry your file was not uploaded"; 
	 } 
	 
	 //If everything is ok we try to upload it 
	 else 
	 { 
	 if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) 
	 { 
	 echo "The file ". basename( $_FILES['uploaded']['name']). " has been uploaded"; 
	 } 
	 else 
	 { 
	 echo "Sorry, there was a problem uploading your file."; 
	 } 
	 } 
 }
 ?> 
 
 <form enctype="multipart/form-data" action="cosa.php" method="POST">
 Please choose a file: <input name="uploaded" type="file" /><br />
<input type="submit" name="submit" value="Save Settings" />
 </form> 