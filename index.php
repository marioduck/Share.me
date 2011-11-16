<?php require_once("includes/connection.php"); ?>

<?php include("includes/header.php"); ?>

<h2>Iniciar sesión</h2>
<form action="login.php" method="post">
    <p>Usuario: <input type="text" name="user_name" value="" id="user_name" /></p>
    <p>Password: <input type="password" name="password" value="" id="password" /></p>
    <input type="submit" name="submit" value="Entrar!" />
</form>

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


<?php require("Includes/footer.php"); ?>