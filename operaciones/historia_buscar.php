
<?php 
session_start();
if (isset($_POST['titulo'])) {
	//Establecemos una conexión con el servidor
	include "../conexion.php";


	//Añadimos la historia a la base de datos
	$sql = "INSERT INTO historiasDeTerror (`Título`, `Historia`,`usuario`,`categoria_id`) VALUES ('".$_POST['titulo']."','".$_POST['historia']."','".$_POST['usr']."',".$_POST['categoria'].")" ;
	$reg = $lnk -> query($sql) ;

}
echo $sql;
include "../lista.php";
?>
