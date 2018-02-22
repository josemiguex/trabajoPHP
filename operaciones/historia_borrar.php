<?php
session_start();
include "../conexion.php";
	if (isset($_POST["idhistoria"])){	
	$sql = "DELETE FROM historiasDeTerror WHERE id=".$_POST["idhistoria"] ;
		$reg = $lnk -> query($sql) ;
}

if (isset($_POST['admin']) && $_POST['admin'] == "true") {
	include "../listaadmin.php";
} else {
	include "../lista.php";
}
?>
