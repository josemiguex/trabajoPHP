<?php

if (!isset($_SESSION)) {
	session_start();
}
	//Establecemos una conexión con el servidor
	include "conexion.php";

$numregxpagina=8;
$paginaactual=1;

//En caso de que no me llegen parámetros de paginación
//Inicializamos valores de la paginación como página 1
if (empty($_POST["page"]) || ($_POST["page"]==1) ) {
	$regcomienzo = 0;
} else {
	$regcomienzo = (($_POST["page"]-1) * $numregxpagina);
	$paginaactual= $_POST["page"];
}
//LIMIT PARA PAGINACION
$limit = " LIMIT ". $regcomienzo . "," . $numregxpagina;


$sql = "SELECT historiasDeTerror.Título, historiasDeTerror.Historia, historiasDeTerror.id, usuarios.usuario, categorias.nombre  FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id INNER JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id" ;
//Según los parámetros que reciba se cambia la sentencia
if (isset($_POST['busquedahistoria']) || isset($_POST['categoria'])) {
	$sql .= " WHERE";
}

if (isset($_POST['busquedahistoria'])) {
		$sql .= " `Título` LIKE '%".$_POST['busquedahistoria']."%' ";
	}

if (isset($_POST['busquedahistoria']) && isset($_POST['categoria'])) {
	$sql .= " AND";
}

	if (isset($_POST['categoria'])) {
		$sql .= " categoria_id='".$_POST['categoria']."' ";
	}

	if (isset($_POST['ordenapor'])) {
		$sql .= " ORDER BY ".$_POST['ordenapor'];

		if ($_POST['desc'] == 'true') {
			$sql .= " DESC ";
		}
	}
	try {
    $resultado = $lnk -> query($sql.$limit) ;
    $resultado2 = $lnk->query($sql);
 

}
catch (PDOException $e)
{
    echo $e->getMessage();
    die();
}
?>
<b>Pulse en el título de la historia para leer la historia</b>
<?php

	$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);


echo "<table id='administraciontabla'>";
echo "<tr>";
echo "<td>ID</td>";
echo "<td>Título</td>";
echo "<td>Categoría</td>";
echo "<td>Usuario</td>";
echo "</tr>";

	foreach ($fla as $historia) {
		echo "<tr>";
		echo "<td class='id' id='".$historia['id']."'>".$historia['id']."</td>";
		echo "<td class='titulo' id='".$historia['Título']."'>".$historia['Título']."</td>";
		echo "<td style='display:none' class='contenido'>".$historia['Historia']."</td>";
		echo "<td>".$historia['nombre']."</td>";
		echo "<td>".$historia['usuario']."</td>";
		echo "<td><button class='borrar'>Eliminar</button></td>";
		echo "<td><button class='modificar'>Modificar</button>";
		echo "<tr>";
	}
	echo "</table>";



?>

<ul class="pagination">
<?php 
if ($paginaactual!=1){?>
  <li><a href="#" data-page="1">Primero</a></li>
  <li><a href="#" data-page="<?php echo ($paginaactual-1)?>"><<</a></li>
<?php
}?>
<?php
//Cuantas páginas

$totalregistros = $resultado2-> rowCount();
$numpaginas=ceil($totalregistros/ $numregxpagina);
for ($i=1;$i<=$numpaginas;$i++){ ?>  
  <li><a href="#" data-page="<?php echo $i?>" 
  <?php
if ($i==$paginaactual){?> class="actual" <?php }?>
  ><?php echo $i?></a></li>
<?php } ?>
<?php 
if ($paginaactual!=$numpaginas){?>

  <li><a href="#" data-page="<?php echo ($paginaactual+1)?>">>></a></li>
  <li><a href="#" data-page="<?php echo $numpaginas?>">Ultimo</a></li>
<?php }?>
</ul>