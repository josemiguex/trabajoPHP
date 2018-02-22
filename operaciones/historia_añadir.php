
<?php 
session_start();
if (isset($_POST['titulo'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";


	//Añadimos la historia a la base de datos
	$sql = "INSERT INTO historiasDeTerror (`Título`, `Historia`,`usuario_id`,`categoria_id`,`fecha`) VALUES ('".$_POST['titulo']."','".$_POST['historia']."','".$_POST['usuario_id']."',".$_POST['categoria_id'].",'".$_POST['fecha']."')" ;

		$reg = $lnk -> query($sql) ;

}
if (isset($_POST['admin']) && $_POST['admin'] == "true") {
	include "../listaadmin.php";
} else {
	include "../lista.php";
}?>
