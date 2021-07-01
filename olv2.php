<?php

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("libraries/password_compatibility_library.php");
}

require_once("config/db.php");

require_once("classes/Login.php");

$login = new Login();
if ($login->isUserLoggedIn() == true) {
   header("location: stock.php");

} else {
    ?>
	<!DOCTYPE html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
  <title>Simple Stock | Login</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <!-- CSS  -->
   <link href="css/login.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>

<div class="container">
	 
        <div class="card card-container">
			
        <label for="user_password_new3" class="">Correo electronico</label>
            <p id="profile-name" class="profile-name-card"> </p>
            <form method="post" accept-charset="utf-8" name="loginform" autocomplete="off" role="form" class="form-signin">
			<?php
				if (isset($login)) {
					if ($login->errors) {
						?>
						<div class="alert alert-danger alert-dismissible" role="alert">
						    <strong>Error!</strong> 
						
						<?php 
						foreach ($login->errors as $error) {
							echo $error;
						}
						?>
						</div>
						<?php
					}
					if ($login->messages) {
						?>
						<div class="alert alert-success alert-dismissible" role="alert">
						    <strong>Aviso!</strong>
						<?php
						foreach ($login->messages as $message) {
							echo $message;
						}
						?>
						</div> 
						<?php 
					}
				}
				?>
                <span id="reauth-email" class="reauth-email"></span>
                <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Correo electrónico" required>
                <button type="submit" class="btn btn-lg btn-success btn-block btn-signin" name="ol" id="submit">Enviar</button>
			


            </form><!-- /form -->
			<hr>
			<a href="login.php" >Regresar e iniciar secion </a><br>
			<a href="olv.php" >Verificar con el nombre de mi mascota </a>
        </div><!-- /card-container -->
		
    </div><!-- /container -->
	<script type="text/javascript" src="js/recuperarC.js"></script>
  </body>
</html>

	<?php


}


?>
      