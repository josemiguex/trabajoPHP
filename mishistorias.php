<?php 

	session_start() ;

	if (!isset($_SESSION["id"])) {
	header("location: index.php") ;
}

		if (isset($_GET["destroy"])) {
			
		// Destruir variables de sesión
		$_SESSION[] = array() ;

		//Destruimos la sesión
		session_destroy() ;

		header("location: index.php") ;
		}


	//Establecemos una conexión con el servidor
	include "conexion.php";

	if (isset($_GET["id"])) {
		
		$sql = "DELETE FROM historiasDeTerror WHERE id=".$_GET["id"] ;
		$reg = $lnk -> query($sql) ;
	}



	$sql = "SELECT * FROM historiasDeTerror WHERE usuario='".$_SESSION['usr']."'" ;
	$sentencia = $lnk -> prepare($sql) ;
	$sentencia -> execute() ;
	$fla = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>
<html>
<head>
<style>


	h1 {
		text-align: center;
	}

	h2 {
		color: lightgrey;
		text-shadow: 1px 1px 1px white;
	}

	button {
		border-radius: 1em;

	}

	body {
		font-family: Anton, sans-serif;
		background: url(img/fondo.jpg);
		color: white;
	}

	#header {
		width: 100%;
		height: 2vw;
		background-color: #3a3a3a;
		padding: 1vw 0 1vw 0;

	}

	#header #botones {
		float: right;
	}

	button {
		margin-right: 1vw;
		border-radius: 0.5em;
		font-size: 20px;
		border: 3px solid lightgrey;
		background-color: #3a3a3a;
		color: white;
		transition: 0.5s background-color,0.5s border, 0.5s color;

	}

	button:hover {
		border: 3px solid #3a3a3a;
		background-color: lightgrey;
		color: #3a3a3a;
	}

	#botoneliminar {
		text-decoration:none;   

		margin-right: 1vw;
		border-radius: 0.5em;
		font-size: 20px;
		border: 3px solid lightgrey;
		background-color: red;
		color: white;
		transition: 0.5s background-color,0.5s border, 0.5s color;
	}

	#botoneditar {
		text-decoration:none;   

		margin-right: 1vw;
		border-radius: 0.5em;
		font-size: 20px;
		border: 3px solid lightgrey;
		background-color: blue;
		color: white;
		transition: 0.5s background-color,0.5s border, 0.5s color;
	}
</style>
</head>
<body>
<h1>Mis historias</h1>

<div id="header">
	<div id="botones">
	<?php
		echo "<button onclick=\"location.href='añadir.php'\">Añadir historia</button>" ;
		echo "<button onclick=\"location.href='index.php?destroy'\">Cerrar sesión</button>" ;

	?>
	</div>
</div>


</div>
<?php

if (sizeof($fla) > 0) {
	foreach ($fla as $fla) {
		echo "<h2>".$fla['Título']."</h2>" ;
		echo "<p>".str_replace("\n", "<br>", $fla['Historia'])."</p>" ;
		echo "<a id='botoneliminar' href=\"mishistorias.php?id='".$fla['id']."'\">Eliminar</button><a>";
		echo "<a id='botoneditar' href=\"añadir.php?id='".$fla['id']."'\">Editar</button><a>";
		echo "<br>";
	}
} else {
	echo "No has escrito ninguna historia" ;
}
?>
<br>
<button onclick="location.href='index.php'">Volver</button>


</body>
</html>