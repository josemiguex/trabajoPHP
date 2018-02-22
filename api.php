<?php

include "conexion.php";
  // Respuesta
  $ans = [] ;

  // Buscamos en la URL la API_KEY del usuario.
  $api = $_GET["api_key"] ;

  

  if (empty($api)) {  
    $ans["status_message"] = "Invalid API key: You must be granted a valid key." ;
    $ans["success"] = false ;
    
  } else {

    // FRAN: 75779f3c59d9d5ef85a0269d3fc75755f8b860e4
    // LUCAS: bca20d078079cf3a2ff56ffc7dcabb0070edf57d
    $sql = "SELECT historiasDeTerror.id, historiasDeTerror.título, historiasDeTerror.historia, categorias.nombre as categoria FROM historiasDeTerror INNER JOIN categorias ON categorias.id=historiasDeTerror.categoria_id WHERE SHA1(CONCAT(historiasDeTerror.id,historiasDeTerror.título)) = '$api' " ;

    $res = $lnk->query($sql) ;

    if ($res->rowCount() == 0) {      
      $ans["status_message"] = "No se encuentra la historia en la base de datos." ;
      $ans["success"] = false ;
    } else {
      $asg = [];
      $data = $res->fetchAll(PDO::FETCH_ASSOC); 

        
      $ans["success"] = true ;
      $ans["data"] = $data ;
    }
  }

  // Respondemos a la petición
  header("Content-Type: application/json;charset=utf-8") ;
  echo json_encode($ans) ;
