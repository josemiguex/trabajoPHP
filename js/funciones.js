$(document).ready(function() {
$("#categoria").html("<?= $z ?>");
$("#categoriaañadir").html("<?= $z ?>");
$("#listadecategorias").html("<?= $c ?>")
var usr;
var ordenartipo;
<?php if (isset($_SESSION['id'])) {
?>

$("#infologin").html("Bienvenido " + "<?= $_SESSION['usr'] ?>");
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
				"idhistoria" : idhistoria
			};

			$.ajax({
                data:  parametros,
                url:   'historia_borrar.php',
                type:  'post',
                success:  function (data) {				
					$("#historia-" + idhistoria).fadeOut(1000);
					$("#" + idhistoria).fadeOut(1000);
					$("#contenedor").html(data);
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

			var parametros = {
				"usr" : $("#usr").val(),
				"pass" : $("#pass").val()
			};

			$.ajax({
                data:  parametros,
                url:   'login.php',
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
						$("#contenedor").html(data);
						usr = $("#usr").val();

						$( "<button id='botonanadir'>Añadir historia</button>" ).appendTo( "#botones" );
					
					} else {
						$("#infologin").html("Usuario/Contraseña incorrectos");
					}
				}, 1000);
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
	// REGISTRARSE
	$("#dialogoregistrer").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Registrarse": function() {

			var parametros = {
				"usr" : $("#usrregistrer").val(),
				"pass" : $("#passregistrer").val()
			};

			$.ajax({
                data:  parametros,
                url:   'registrer.php',
                type:  'post',
                dataType:'text',
                success:  function (data) {	
                	$("#infologin").html("Registrando usuario e iniciando sesión...");
                	setTimeout(function () {
						$("#infologin").html("Bienvenido " + $("#usrregistrer").val());
						$("#botonlogin").html("Cerrar sesión");
						$("#botonlogin").attr("id",'botonlogout');
						$("#contenedor").html(data);
						usr = $("#usrregistrer").val();

						$( "<button id='botonanadir'>Añadir historia</button>" ).appendTo( "#botones" );
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

    

	//CERRAR SESIÓN
	$("#dialogologout").dialog({
		autoOpen: false,
		resizable: false,
		modal: true,
		buttons: {
		"Cerrar sesión": function() {
			$.ajax({
                url:   'logout.php',
                success:  function (data) {	
                $("#infologin").html("Cerrando sesión...");
                	setTimeout(function () {			
					$("#infologin").html("No has iniciado sesión");
					$("#botonlogout").html("Iniciar sesión");
					$("#botonlogout").attr("id",'botonlogin');
					$("#contenedor").html(data);
					$("#botonanadir").remove();
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
			$.post("historia_modificar.php", {
				idhistoria : $("#idhistoria").val(),
				titulo : $("#titulomodificar").val(),
				historia : $("#historiamodificar").val()
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

	// Al hacer click en el botón de cerrar sesión
	$(document).on("click","#botonlogout",function(){
		 $("#dialogologout").dialog("open");		
		
	});

	// Al hacer click en el botón de registrarse
	$(document).on("click","#botonregistrer",function(){
		 $("#dialogoregistrer").dialog("open");		
		
	});

	//--- PAGINACION -----
$(document).on("click",".pagination li a",function(){
	var numpage = $(this).data("page");
	$.get("lista.php",{page:numpage},function(data){$("#contenedor").html(data);	
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
		var titulo = $("#titulo").val();
		var historia = $("#historia").val();
		var categoria = $("#categoriaañadir").val();		
			$.post("historia_añadir.php", {
				"titulo":titulo,
				"historia":historia,
				"usr" : usr,
				"categoria" : categoria
			},function(data){
				//Pinta de nuevo la tabla
				$("#contenedor").html(data);
			})
					
			$(this).dialog( "close" );												
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

$(document).on("click",".ordena",function(){
		
		//obtener el ordenapor
		ordenatipo=$(this).val();
		$.ajax({
			url: "lista.php",
			data:{ordenapor:ordenatipo},
			type: "post",
			//beforeSend: cargar,
			success: rellenar,
			cache: false
		});
});

$(document).on("keypress keyup", "#buscadorhistorias", function () {
	console.log($("#buscadorhistorias").val());
	$.ajax({
			url: "lista.php",
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

function mostrar(id) {
	if (document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
}
