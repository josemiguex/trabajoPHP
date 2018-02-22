
<?php
session_start();
// Destruir variables de sesión
$_SESSION[] = null ;

		//Destruimos la sesión
session_destroy() ;

include "../lista.php";
?>
