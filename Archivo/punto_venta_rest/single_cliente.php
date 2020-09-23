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
(int)$id_cliente = $_POST['id_cliente'];



comprobarDatos($id_usuario, $token);

if($id_cliente==""){
    $datosJson['data'][0] = "fail";
    $mensajes ["errores"][]= "Cliente no encontrado";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}


$conexion_db = conexion_db($id_usuario, $conexion);

$statement = $conexion_db->prepare("SELECT * FROM clientes WHERE id_cliente = :id_cliente AND borrado = 0 LIMIT 1");
$statement->execute(array(
    ":id_cliente" => $id_cliente
));
$cliente = $statement->fetch();

if($cliente){
    $datosJson['data'][] = $cliente;
    $success = "true";
}else{
    $mensajes ["errores"][]= "Producto no encontrado";
}



if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
