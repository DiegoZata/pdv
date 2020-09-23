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
$parametro = $_POST['parametro'];
// $parametro = "";


comprobarDatos($id_usuario, $token);

$conexion_db = conexion_db($id_usuario, $conexion);

$statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_pedido LIKE '$parametro%' ORDER BY id_pedido DESC");
$statement->execute();
$pedidos = $statement->fetchALL(PDO::FETCH_ASSOC);

foreach($pedidos as $pedido){
    $datosJson['data'][] = $pedido;
}


$success = "true";

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
