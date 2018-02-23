<?php 
include "conexion.php";
	
	session_start() ;

	$sql = "SELECT * FROM categorias";
	$sentencia = $lnk -> query($sql) ;
	
	$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
	$z = "<option value=''>Seleccione categoría</option>";
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
var usr;
var usuario_id;
var ordenartipo;
var terminoabuscar;
var catselect;
var idhistoria;
var numpage;

$(document).ready(function() {

	//Validación formulario login
	$('#loginform').validate({
		rules: {
			usr: { required: true},
			pass: { required: true}
		},
		messages: {

			usr: "Ingrese un nombre de usuario.",
			
			pass: "Ingrese una contraseña."

		},
		submitHandler: function (form) {

			var parametros = {
				usr : $("#usr").val(),
				pass : $("#pass").val()
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/login.php',
                type:  'post',
                dataType:'text',
                success:  function (data) {
                	$("#infologin").html("Iniciando sesión...");
                	setTimeout(function () {
                	if (data != false) {
    					$("#botonregistrer").remove();
						$("#infologin").html("Bienvenido " + $("#usr").val());
						$("#botonlogin").html("Cerrar sesión");
						$("#botonlogin").attr("id",'botonlogout');
						$("#contenedor").load("lista.php");
						usuario_id = data.trim().length;;
						
						$( "<button id='botonanadir'>Añadir historia</button>" ).appendTo( "#botones" );
						$( "<button id='botonperfil'>Ver perfil</button>" ).appendTo( "#botones" );
					
					} else {
						$("#infologin").html("Usuario/Contraseña incorrectos");
					}

				}, 1000);
				}

			});
			
			//get			
			//cierra ventana dialogo				
			$("#dialogologin").dialog( "close" );	
		}
	});
// Validación formulario añadir
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
				"categoria_id" : categoria
			},function(data){
				//Pinta de nuevo la tabla
				$("#contenedor").html(data);
				$("#añadirform")[0].reset();
				

			});
			$("#dialogoañadir").dialog( "close" );
		}
	});
// Validación formulario registro
	$('#registrerform').validate({
		rules: {
			name: { required: true},
			email: { required: true},
			usr: { required: true},
			pass: { required: true}
			

		},
		messages: {

			usr: "Ingrese un nombre de usuario.",
			name: "Pon tu nombre.",
			email: "Pon un email correcto.",
			pass: "Ingrese una contraseña."

		},
		submitHandler: function (form) {
			var parametros = {
				"usr" : $("#usrregistrer").val(),
				"pass" : $("#passregistrer").val(),
				"nombre" : $("#name").val(),
				"apellidos" : $("#surname").val(),
				"email" : $("#email").val()

			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/registrer.php',
                type:  'post',
                dataType:'text',
                success:  function (data) {	
                	if (data != false) {
                	$("#infologin").html("Registrando usuario e iniciando sesión...");
                	setTimeout(function () {
                		$("#botonregistrer").remove();
						$("#infologin").html("Bienvenido " + $("#usrregistrer").val());
						$("#botonlogin").html("Cerrar sesión");
						$("#botonlogin").attr("id",'botonlogout');
						$("#contenedor").html(data);
						usr = $("#usrregistrer").val();

						$( "<button id='botonanadir'>Añadir historia</button>" ).appendTo( "#botones" );
					    $( "<button id='botonperfil'>Ver perfil</button>" ).appendTo( "#botones" );

					},1000);
                } else {
                	$("#infologin").html("El usuario introducido ya existe en la base de datos");
                }
				}
			});
			
			//get			
			//cierra ventana dialogo				
			$("#dialogoregistrer").dialog( "close" );	
		}
	});

$("#categoriaañadir").html("<?= $z ?>");
$(".listadecategorias").html("<?= $c ?>")

//Si hay una sesión iniciada cambiar el mensaje del header
<?php if (isset($_SESSION['id'])) {
?>

$("#infologin").html("Bienvenido " + "<?= $_SESSION['usr'] ?>");
usr = "<?= $_SESSION['usr'] ?>";
usuario_id = "<?= $_SESSION['usuario_id'] ?>";
<?php
}
?>
$("#añadir").hide();


//VENTANA DIALOGO DE BORRAR
	$("#dialogoborrar").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Borrar": function() {

			var parametros = {
				"idhistoria" : idhistoria
			};

			$.ajax({
                data:  parametros,
                url:   'operaciones/historia_borrar.php',
                type:  'post',
                success:  function (data) {				
					$("#historia-" + idhistoria).fadeOut(1000);
					$("#" + idhistoria).fadeOut(1000);
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

	// LOGIN
	$("#dialogologin").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Iniciar sesión": function() {
			$("#loginform").submit();

			
			}											
		},
		"Cancelar": function() {
				$(this).dialog( "close" );
		}
		//buttons
	});	
	// REGISTRARSE
	$("#dialogoregistrer").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Registrarse": function() {
			$("#registrerform").submit();
														
		},
		"Cancelar": function() {
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
                $("#infologin").html("Cerrando sesión...");
                	setTimeout(function () {			
					$("#infologin").html("No has iniciado sesión");
					$("#botonlogout").html("Iniciar sesión");
					$("#botonlogout").attr("id",'botonlogin');
					$("#contenedor").html(data);
					$("#botonanadir").remove();
					$("#administracion").remove();
					$("#botonperfil").remove();
					$( "<button id='botonregistrer'>Registrarse</button>" ).appendTo( "#botones" );
					$(".borrar").remove();
					$(".modificar").remove();
				},1000);
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


	// Al hacer click en el botón de borrar	
	$(document).on("click",".borrar",function(){
		idhistoria = $(this).parents("div").attr("data-idhistoria");
		 $("#dialogoborrar").dialog("open");		
		
	});

	// Al hacer click en el botón de añadir
	$(document).on("click","#botonanadir",function(){
		 $("#dialogoañadir").dialog("open");		
		
	});

	// Al hacer click en el botón de iniciar sesión
	$(document).on("click","#botonlogin",function(){
		 $("#dialogologin").dialog("open");		
		
	});

	$(document).on("click","#advancedopbutton",function(){
		 $("#opavanzadas").fadeToggle();		
		
	});


	// Al hacer click en el botón de cerrar sesión
	$(document).on("click","#botonlogout",function(){
		 $("#dialogologout").dialog("open");		
		
	});

	// Al hacer click en el botón de registrarse
	$(document).on("click","#botonregistrer",function(){
		 $("#dialogoregistrer").dialog("open");		
		
	});
	//Al hacer click en el botón de administración
	$(document).on("click","#administracion",function(){
		 window.location.href = "administracion";
		
	});

	$(document).on("click","#botonperfil",function(){
		 window.location.href = "perfil";
		
	});



// Buscador
$("#buscadorhistorias").on("keypress keyup", function () {
	terminoabuscar = $("#buscadorhistorias").val();
	sendajax(catselect, terminoabuscar, ordenartipo);
	
});

//Ordenar
$(".ordena").on("click",function(){
		
		//obtener el ordenapor
		ordenatipo=$(this).val();
		ordenartipo= $(this).val();
		sendajax(catselect, terminoabuscar, ordenartipo);
});

// Categorias
$(".listadecategorias li a").on("click",function(){

	

	catselect = $(this).data("id");
	sendajax(catselect, terminoabuscar,ordenartipo);


});

//Paginación
$(document).on("click",".pagination li a",function(){
	 numpage = $(this).data("page");
	var desc = false;
		if ($("#desc").is(":checked")) {
			desc = true;
		}

	$.post("lista.php",{page:numpage, ordenapor: ordenartipo, busquedahistoria:terminoabuscar, desc: desc},function(data){$("#contenedor").html(data);	
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

});

//Al clickear en el botón modificar
$(document).on("click",".modificar",function(){
	idhistoria = $(this).parents().siblings("h2.titulo").attr("id");
	//Para que ponga el campo direccion con su valor
	$("#titulomodificar").val($(this).parent().siblings("h2.titulo").html());
	
	
	//historia
	var historia = $.trim($(this).siblings("p").html());
	$("#historiamodificar").val(historia);
	$("#idhistoria").val(idhistoria);

$( "#dialogomodificar").dialog("open");
});

function rellenar(data) {
	$("#contenedor").html(data);
}

function sendajax(catselect, terminoabuscar, ordenartipo) {

	var desc = false;
		if ($("#desc").is(":checked")) {
			desc = true;

		}
	$.post("lista.php",{categoria:catselect, busquedahistoria:terminoabuscar, ordenapor: ordenartipo, desc:desc},function(data){$("#contenedor").html(data);	
	});
}


function mostrar(id) {
	if (document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
}
</script>

</head>
<body>
<h1>Página de historias de terror</h1>

<div id="header">

	<span id="infologin">No has iniciado sesión</span>
<div id="botones">

		<?php if (isset($_SESSION['id'])) {
		?>
		<button id="botonanadir">Añadir historia</button>
		<button id="botonlogout">Cerrar sesión</button>
		<button id='botonperfil'>Ver perfil</button>
		<?php
		} else {
			?>
			<button id="botonlogin">Iniciar sesión</button>
			<button id="botonregistrer">Registrarse</button>
			<?php
		}
		?>
		
		
	</div>
</div>

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

<div id="dialogologin" title="Iniciar sesión">
	<?php include "formularios/form_login.php"; ?>

</div>

<div id="dialogoregistrer" title="Registrarse">
	<?php include "formularios/form_registrer.php"; ?>

</div>

<div id="dialogologout" title="¿Está seguro?">

</div>
<button id="advancedopbutton">Opciones avanzadas</button>
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

<div id="contenedor">
<?php include "lista.php"; ?>
</div>


</body>
</html>