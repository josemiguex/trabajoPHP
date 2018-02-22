
<?php

$mensaje = "";
	session_start() ;

	if (isset($_POST['usr'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";
	
	$sql = "SELECT * FROM usuarios WHERE usuario=:user AND contraseña=:password" ;
	$sentencia = $lnk -> prepare($sql) ;
	
	$sentencia->execute(array(':user' => $_POST['usr'] ,':password'=> $_POST['pass'] )) ;
	$resultado = $sentencia->fetchAll();
	
	$data;
	//Si he obtenido un resultado correcto
	if ($resultado != false) {

		// Crear las variables de sessión necesarias

			$_SESSION["id"] = session_id();
			$_SESSION["usr"] = $_POST['usr'];
			$_SESSION["usuario_id"] = $resultado['0']['id'];
			$_SESSION['tiempo'] = time();
			$_SESSION['rol_id'] = $resultado['0']['rol_id'];
			
			echo $resultado['0']['id'];
			
	} else {
	echo false;
	}
}


?>
