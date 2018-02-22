<html>
<head>
<?php
session_start() ;
include "funciones.php" ;
if (isset($_POST['titulo']) && !isset($_GET["id"])) {
	//Establecemos una conexión con el servidor
	$lnk = new mysqli("localhost:3306","root","usuario","trabajoPHP");

	if ($lnk->connect_errno > 0) {
		die("**Error: $link->connect_errno: $link->connect_error");
	}

	// Comprobar si existe el usuario en la base de datos
	
	$sql = "INSERT INTO historiasDeTerror (`Título`, `Historia`, `usuario`) VALUES ('". $_POST['titulo']."','".$_POST['historia']."','". $_SESSION['usr']."') ;" ;

	$reg = $lnk -> query($sql) ;
	header("location: index.php") ;
}




if (!isset($_SESSION["id"])) {
	header("location: index.php") ;
}


if (isset($_POST['titulo']) && isset($_GET["id"])) {
	include "conexion.php";

	$sql = "UPDATE `historiasDeTerror` SET `Título`='".$_POST['titulo']."',`Historia`='".$_POST['historia']."' WHERE id=".$_GET["id"] ;

	$reg = $lnk -> query($sql) ;
	header("location: index.php?sql=" + $sql) ;
}

?>

<style>

#contenedor {
	position: relative;
	top: 2vw;
	border-radius: 1em;
	background-color: black;
	border: 5px solid lightgrey;
	width: 60vw;
	height: 35vw;
	color: white;
	margin: 0 auto;
	padding: 3%;

}

#button {
		margin-right: 3px;
		border-radius: 0.5em;
		font-size: 20px;
		border: 3px solid lightgrey;
		background-color: grey;
		color: white;
		transition: 0.5s background-color,0.5s border, 0.5s color;
	}

	#button:hover {
		border: 3px solid grey;
		background-color: lightgrey;
		color: grey;
	}

textarea {
	resize: none;
	width: 60vw;
	height: 21vw;
}

#titulo {
	width: 60vw;
}

#contenedor h1 {
	color: lightgrey;
	text-shadow: 1px 1px 1px grey;
}
body {
	background-image: url(img/fondo.jpg);
}

</style>

</head>
<body>
<div id="contenedor">
<?php 

if (isset($_GET['id'])) {
	include "conexion.php";

	$sql = "SELECT * FROM historiasDeTerror WHERE id=".$_GET['id']."" ;
	$sentencia = $lnk -> prepare($sql) ;
	$sentencia -> execute() ;
	$fla = $sentencia->fetch(PDO::FETCH_ASSOC);
	
	editar($fla['Título'], $fla['Historia']) ;
} else {
	añadir() ;
}

?>
	
	
</div>
</body>
</html>