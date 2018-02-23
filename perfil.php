<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['usr'])) {
			
		header("location: historias") ;
		die();
	}

$sql = "SELECT usuarios.nombre, usuarios.apellidos, usuarios.usuario, usuarios.email, roles.nombre as rol FROM usuarios INNER JOIN roles ON usuarios.rol_id=roles.id WHERE usuarios.id='".$_SESSION['usuario_id']."'" ;
$resultado = $lnk->query($sql);
$usuario = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/estilo.css"/>
<script src="js/jquery.js"></script>

<title>Perfil</title>
<script>
$(document).on("click","#goback",function(){
		 window.location.href = "historias";
	});
</script>
</head>
<body>
<div id="header">
	<span id="infologin">Ver perfil</span>

<div id="botones">

	<button id="goback">Volver</button>
		
	</div>
</div>
<img src="img/perfil.jpg" style="height:128px;border: 1px solid white"></img>
<ul>
<li><b>Nombre: </b><?= $usuario['0']['nombre'] ?></li>
<li><b>Apellidos: </b><?= $usuario['0']['apellidos'] ?></li>
<li><b>Nick: </b><?= $usuario['0']['usuario'] ?></li>
<li><b>Email: </b><?= $usuario['0']['email'] ?></li>
<li><b>Tipo de usuario: </b><?= $usuario['0']['rol'] ?></li>

</ul>
</body>
</html>