
<?php

$mensaje = "";
	session_start() ;
include "../conexion.php";
	
	$sql = "INSERT INTO usuarios (`nombre`,`apellidos`,`email`,`usuario`,`contraseña`,`rol_id`) values ('".$_POST['nombre']."','".$_POST['apellidos']."','".$_POST['email']."','".$_POST['usr']."','".$_POST['pass']."','1')" ;
	$sentencia = $lnk -> query($sql) ;
	if (!$sentencia) {
    if ($lnk->errorInfo()['1'] == 1062) {
	echo false;
	}
    die();
}

	
		// Crear las variables de sessión necesarias
			$_SESSION["id"] = session_id();
			$_SESSION["usr"] = $_POST['usr'];
			$_SESSION['tiempo'] = time();
			include "../lista.php";



?>
