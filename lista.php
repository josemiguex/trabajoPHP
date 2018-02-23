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


	$sql = "SELECT historiasDeTerror.Título, historiasDeTerror.Historia, historiasDeTerror.id, historiasDeTerror.fecha,usuarios.usuario, categorias.nombre  FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id INNER JOIN usuarios ON usuarios.id=historiasDeTerror.usuario_id" ;

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

	$fla = $resultado->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2) {
	echo "<button id='administracion'>Administración</button>";
}

$numHistoria = 1;

if (isset($fla['0'])){
echo "<div id='historias'>";
foreach ($fla as $historia) {
	echo "<div id='historias'>";
	echo "<h2 class='titulo' id='".$historia['id']."' onclick='mostrar(\"historia-".$historia['id']."\")'>".$historia['Título']."</h2>" ;
	echo "<div class='historia' id='historia-".$historia['id']."' data-idhistoria=".$historia['id']." style='display:none'>" ;
	echo "<p>".str_replace("\n", "<br>", $historia['Historia'])."</p>" ;
	echo "<b>Escrito por: </b>".$historia['usuario']."<br>" ;
	echo "<b>Categoria: </b>".$historia['nombre']."<br>" ;
	echo "<b>Fecha: </b>".$historia['fecha']."<br>" ;

	if (isset($_SESSION['usr']) && $_SESSION['usr'] == $historia['usuario']) {
		echo "<button class='borrar'>Eliminar</button>";
		echo "<button class='modificar'>Modificar</button>";
	}
	echo "</div>" ;
	echo "</div>";
	$numHistoria++;
}
echo "</div>";
} else {
	echo "No se ha encontrado ninguna historia<br>";
}

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