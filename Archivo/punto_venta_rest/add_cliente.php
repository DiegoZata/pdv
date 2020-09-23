<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Credentials: true");
header('content-type: application/json; charset=utf-8');
require 'config.php';
require 'functions.php';

$conexion = conexion($bd_config);
$mensajes ["errores"][]= ""; //Guarda todo tipo de errores
$success = "false";

$id_usuario = $_POST['data'];
$token = $_POST['pruebaData'];
$doc = $_POST['doc'];
$nombre = $_POST['nombre'];
$tel = $_POST['tel'];
$direccion = $_POST['direccion'];

comprobarDatos($id_usuario, $token);

if($doc == ''){
    $mensajes ["errores"][] = "Falta el número de documento";
    $datosJson['data'][0] = "fail";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}if($nombre == ''){
    $mensajes ["errores"][] = "Falta el nombre del cliente";
    $datosJson['data'][0] = "fail";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}else{
    $conexion_db = conexion_db($id_usuario, $conexion);
    $statement = $conexion_db->prepare("SELECT * FROM clientes WHERE ci = :ci AND borrado = 0 LIMIT 1");
    $statement->execute(array(
      ":ci" => $doc
    ));
    $resultado = $statement->fetch();
    if($resultado){
      $mensajes ["errores"][] = "Ya existe un cliente con ese documento";
      $datosJson['data'][0] = "fail";
      $datosJson = array_merge($datosJson, $mensajes);
      echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
      die();
    }
    $statement = $conexion_db->prepare("INSERT INTO clientes (id_cliente, nombre, ci, telefono, direccion, borrado) 
    VALUES (null, :nombre, :ci, :telefono, :direccion, 0)");
    $statement->execute(array(
        ":nombre" => $nombre,
        ":ci" => $doc,
        ":telefono" => $tel,
        ":direccion" => $direccion
      ));

    $success = "true";
    $datosJson['data'][0] = "success";
    $datosJson['data'][1] = $nombre;
}

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
