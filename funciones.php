<?php

function añadir() {
	?>

	

	<?php
}

function editar($titulo, $historia) {
	?>

	<h1>Editar Historia</h1>
	<form method="POST">
	<b>Título</b>:<br> <input id="titulo" type="text" name="titulo" value=<?= $titulo ?> required></input><br>
	<b>Historia:</b><br> <textarea name="historia" required><?= $historia ?> </textarea><br><br>
	<input id="button" type="submit" value="Editar"></input>
	<button id="button" onclick="location.href='index.php'">Volver</button>
	</form>

	<?php
}

?>