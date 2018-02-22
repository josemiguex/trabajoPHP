
<?php 

session_start();
if (isset($_POST['usuario'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";

	//Añadimos la historia a la base de datos
	$sql = "UPDATE usuarios set `rol_id`='2' WHERE id='".$_POST['usuario']."'" ;
	$reg = $lnk -> query($sql) ;

}
	include "../listausuarios.php";

?>
