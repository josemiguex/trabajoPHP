
<?php 

session_start();
if (isset($_POST['titulo'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";

	//Añadimos la historia a la base de datos
	$sql = "UPDATE historiasDeTerror set `Título`='".$_POST['titulo']."',`Historia`='".$_POST['historia']."' WHERE `id`='".$_POST['idhistoria']."'" ;
	$reg = $lnk -> query($sql) ;

}

if (isset($_POST['admin']) && $_POST['admin'] == "true") {
	include "../listaadmin.php";
} else {
	include "../lista.php";
}
?>
