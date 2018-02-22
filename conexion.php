<?php

try {
	$lnk = new pdo("mysql:host=localhost:3306;dbname=trabajoPHP","root","usuario");
} catch (PDOException $e) {
      echo "No se ha podido establecer conexión con el servidor de bases de datos.<br>";
      die ("Error: " . $e->getMessage());
}

?>