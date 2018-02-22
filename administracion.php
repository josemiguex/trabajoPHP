<?php 
include "conexion.php";
	
	session_start() ;
	if (!isset($_SESSION['usr']) || $_SESSION['rol_id'] != 2) {
			
		header("location: historias") ;
		die();
	}

	$sql = "SELECT * FROM categorias";
	$sentencia = $lnk -> query($sql) ;
	
	$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
	$z = "";
	$c = "<li><a href='#' data-page=''>Todas</a></li>";
	foreach ($resultado as $resultado) {
		$z = $z."<option value='".$resultado['id']."'>".$resultado['nombre']."</option>";
		$c = $c."<li><a href='#' data-id='".$resultado['id']."'>Historias de ".$resultado['nombre']."</a></li>";
	}
	
?>


<html>

<head>

<link rel="stylesheet" type="text/css" href="ui-lightness/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" type="text/css" href="css/estilo.css"/>

<script src="js/jquery.js"></script>
<script src="js/core.js"></script>
<script src="js/jquery-ui-1.10.3.custom.js"></script>
<script>
var usuario_id = "<?= $_SESSION['usuario_id'] ?>";
//Variables que irán cambiando conforme navegamos en la página
var numpage; //Variable que indica en que página de la paginación estamos
var usuarioselect; //Variable que indica el usuario seleccionado en la lista de usuarios
var usr;
var ordenartipo; //Variable que indica cómo se ordena las historias en la tabla
var terminoabuscar; //Variable que indica el término introducido en el buscador
var catselect; //Variable que indica la categoría seleccionada

$(document).ready(function() {
// Validar form añadir
	$('#añadirform').validate({
		rules: {
			titulo: { required: true},
			historia: { required: true},
			fecha: { required: true}
		},
		messages: {

			titulo: "Ingrese un titulo.",
			
			historia: "Debe escribir una historia.",
			fecha: "Debe introducir una fecha."

		},
		submitHandler: function (form) {
		var titulo = $("#titulo").val();
		var historia = $("#historia").val();
		var categoria = $("#categoriaañadir").val();
		var fecha = $("#fecha").val();		
			$.post("operaciones/historia_añadir.php", {
				"titulo":titulo,
				"historia":historia,
				"usuario_id" : usuario_id,
				"fecha" : fecha,
				"categoria_id" : categoria,
				"admin" : true
			},function(data){
				//Pinta de nuevo la tabla
				$("#contenedor").html(data);
			});
			$("#dialogoañadir").dialog( "close" );
		}
	});

// Muestra la lista de categorias al añadir historia
$("#categoriaañadir").html("<?= $z ?>");
// Muestra la lista de categorias al buscar historia
$(".listadecategorias").html("<?= $c ?>")

<?php if (isset($_SESSION['id'])) {
?>

usr = "<?= $_SESSION['usr'] ?>";
<?php
}
?>
$("#añadir").hide();


var idhistoria;
//VENTANA DIALOGO DE BORRAR
	$("#dialogoborrar").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Borrar": function() {
			var parametros = {
				"idhistoria" : idhistoria,
				admin: true
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/historia_borrar.php',
                type:  'post',
                success:  function (data) {				
					$("#" + idhistoria).parent().fadeOut(1000);
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$(this).dialog( "close" );												
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});	
//Dialogo admin
	$("#dialogoadmin").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Si": function() {
			var parametros = {
				"usuario" : usuarioselect,
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/admin_añadir.php',
                type:  'post',
                success:  function (data) {				
					$("#listausuarios").html(data);
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$(this).dialog( "close" );	
		},
		"No": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});	


   

	//CERRAR SESIÓN
	$("#dialogologout").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Cerrar sesión": function() {
			$.ajax({
                url:   'operaciones/logout.php',
                success:  function (data) {	
                	window.location.href = "index.php";

				}
			});
			
			//get			
			//cierra ventana dialogo				
			$(this).dialog( "close" );												
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});



	
//MODIFICAR
$( "#dialogomodificar" ).dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		width: '700px',
		buttons: {
		"Guardar": function() {			
			$.post("operaciones/historia_modificar.php", {
				idhistoria : $("#idhistoria").val(),
				titulo : $("#titulomodificar").val(),
				historia : $("#historiamodificar").val(),
				admin : true,
				page: numpage
			},function(data,status){
				//**** TODO : Solo cambiar la fila afectada
				$("#contenedor").html(data);
			})//get			
					
			$(this).dialog( "close" );												
					},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});		


	
	//--- PAGINACION -----
$(document).on("click",".pagination li a",function(){
	numpage = $(this).data("page");
	var desc = false;
	if ($("#desc").is(":checked")) {
		desc = true;

	}
	$.post("listaadmin.php",{page:numpage, ordenapor: ordenartipo,busquedahistoria: terminoabuscar, desc:desc},function(data){$("#contenedor").html(data);	
	});

});

//Paginación lista de usuarios
$(document).on("click",".paginationUsuarios li a",function(){
	numpage = $(this).data("page");
	$.post("listausuarios.php",{page:numpage},function(data){$("#listausuarios").html(data);	
	});

});

// Buscador
$("#buscadorhistorias").on("keypress keyup", function () {
	terminoabuscar = $("#buscadorhistorias").val();
	
	$.ajax({
			url: "listaadmin.php",
			data:{busquedahistoria:$("#buscadorhistorias").val()},
			type: "post",
			//beforeSend: cargar,
			success: function(data) {
				$("#contenedor").html(data);
			},
			cache: false
		});
}
);

//Ordenar
$(".ordena").on("click",function(){
		
		//obtener el ordenapor
		ordenatipo=$(this).val();
		ordenartipo= $(this).val();
		var desc = false;
		if ($("#desc").is(":checked")) {
			desc = true;

		}
		$.ajax({
			url: "listaadmin.php",
			data:{ordenapor:ordenatipo, busquedahistoria:terminoabuscar, categoria:catselect, desc: desc},
			type: "post",
			//beforeSend: cargar,
			success: function(data) {
				$("#contenedor").html(data);

			},
			cache: false
		});
});
// Categorias
$(".listadecategorias li a").on("click",function(){
	catselect = $(this).data("id");
	var desc = false;
	if ($("#desc").is(":checked")) {
		desc = true;

	}
	$.post("listaadmin.php",{categoria:catselect, busquedahistoria:terminoabuscar, ordenapor: ordenartipo, desc:desc},function(data){$("#contenedor").html(data);	
	});


});


// AÑADIR
$( "#dialogoañadir" ).dialog({
		autoOpen: false,
		resizable: true,
		modal: true,
		width: '700px',
		buttons: {

		"Añadir Historia": function() {	
			$('#añadirform').submit();						
					},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		}//buttons
	});	

// Al hacer click en el botón de borrar	
	$(document).on("click",".borrar",function(){
		idhistoria = $(this).parent().siblings("td.id").html();
		 $("#dialogoborrar").dialog("open");		
		
	});

	// Al hacer click en el botón de añadir
	$(document).on("click","#botonanadir",function(){
		 $("#dialogoañadir").dialog("open");		
		
	});

	// Al hacer click en el botón de opciones avanzadas
	$(document).on("click","#advancedopbutton",function(){
		 $("#opavanzadas").fadeToggle();
	});

	// Al hacer click en el botón de mostrar usuarios
	$(document).on("click","#botonmostrarusuarios",function(){
		 $("#listausuarios").fadeToggle();
		 $("#contenedor").fadeToggle();
		 $("#advancedopbutton").fadeToggle();
		 $("#botonanadir").fadeToggle();
		 $("#opavanzadas").hide();
		 if ($("#botonmostrarusuarios").html() == "Mostrar usuarios") {
			 $("#botonmostrarusuarios").html("Mostrar historias");
		} else {
			$("#botonmostrarusuarios").html("Mostrar usuarios");
		}
	});



	// Al hacer click en el botón de cerrar sesión
	$(document).on("click","#botonlogout",function(){
		 $("#dialogologout").dialog("open");		
		
	});

	// Al hacer click en el botón de administrador
	$(document).on("click","#botonadmin",function(){
		 $("#dialogoadmin").dialog("open");
		 $("#nombreusuario").html($(this).parent().siblings(".usuario").attr('id'));		
		usuarioselect = $(this).parents().siblings(".id").attr('id')
	});

	// Al hacer click en el botón de volver a la página principal
	$("#goback").on("click",function(){
		 window.location.href = "index.php";
		
	});


});



//Al clickear en el botón modificar
$(document).on("click",".modificar",function(){
	idhistoria = $(this).parents().siblings("td.id").attr("id");
	//Para que ponga el campo direccion con su valor
	$("#titulomodificar").val($(this).parent().siblings("td.titulo").html());
	
	
	//historia
	var historia = $.trim($(this).parent().siblings("td.contenido").html());
	$("#historiamodificar").val(historia);
	$("#idhistoria").val(idhistoria);

$( "#dialogomodificar").dialog("open");
});

</script>

</head>
<body>
<h1>Página de administración</h1>



<div id="dialogoborrar" title="Eliminar historia">
  <p>¿Esta seguro que desea eliminar la historia?</p>
</div>



<!-- CAPA DE DIALOGO MODIFICAR HISTORIA -->
<div id="dialogomodificar" >
<?php include "formularios/form_modificar.php"; ?>
</div>

<div id="dialogoañadir" title="Añadir historia">
	<?php include "formularios/form_añadir.php"; ?>

</div>

<button id="goback">Volver a la página principal</button>
<button id="advancedopbutton">Opciones avanzadas</button>
<button id="botonanadir">Añadir historia</button>
<button id="botonmostrarusuarios">Mostrar usuarios</button>
<button id="botonlogout">Cerrar sesión</button>

<div id="opavanzadas" style="display: none">
<input type="text"  id="buscadorhistorias" placeholder="Buscador"></input><br>

<b>Ordenar por:</b> <br>
<button class="ordena" data-page="<?php echo $paginaactual ?>" value="título">Título</button>
<button class="ordena" data-page="<?php echo $paginaactual ?>" value="id">Creación</button>
<button class="ordena" data-page="<?php echo $paginaactual ?>" value="LENGTH(Historia)">Longitud</button>
<input type="checkbox" id="desc" value="desc">Descendiente

<ul class='listadecategorias'>

</ul>

</div>

<div id="listausuarios" style="display:none">
<?php include "listausuarios.php"; ?>
</div>

<div id="dialogoadmin" style="display:none">
<b>¿Quiere que <span id="nombreusuario"></span> sea administrador?</b>
</div>

<div id="dialogologout" title="¿Está seguro?">

</div>

<br>

<div id="contenedor">
<?php include "listaadmin.php"; ?>
</div>

 <!-- Trigger/Open The Modal -->
<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <span id="modalhistoria">Some text in the Modal..</p>
  </div>
<script>
//MODAL

// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
$(document).on("click",".titulo", function() {
    $("#myModal").css('display','block');
    $("#modalhistoria").html($(this).next().html());
});

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
} 
</script>
</div> 
</body>
</html>