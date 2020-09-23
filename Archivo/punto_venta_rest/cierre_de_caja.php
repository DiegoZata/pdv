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

$id_usuario = 1;
$token = $_POST['pruebaData'];


comprobarDatos($id_usuario, $token);

$conexion_db = conexion_db($id_usuario, $conexion);

$statement = $conexion_db->prepare("SELECT * FROM apertura_caja WHERE id_usuario=:id_usuario AND abierto = 1 LIMIT 1");
$statement->execute(array(":id_usuario" => $id_usuario));
$apertura_caja = $statement->fetch(PDO::FETCH_ASSOC);

if(!$apertura_caja){
    $mensajes ["errores"][]= "No se ha abierto ninguna caja";
}else{
    $statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_usuario = :id_usuario AND vendido = 1 AND cerrado = 0");
    $statement->execute(array(":id_usuario" => $id_usuario));
    $pedidos = $statement->fetchALL(PDO::FETCH_ASSOC);
    $totalVenta = 0;
    foreach($pedidos as $pedido){
        $totalVenta = floatval($totalVenta) + floatval($pedido['total']); 
    }
    $statement = $conexion_db->prepare("UPDATE pedidos SET cerrado = 1 WHERE id_usuario = :id_usuario AND vendido = 1 AND cerrado = 0");
    $statement->execute(array(":id_usuario" => $id_usuario));
    $statement = $conexion_db->prepare("UPDATE apertura_caja SET abierto = 0 WHERE id_usuario = :id_usuario");
    $statement->execute(array(":id_usuario" => $id_usuario));
    $datosJson['data'][] = $totalVenta;
    $datosJson['data'][] = $apertura_caja['efectivo'];
    $success = "true";
}




if ($success == "false") {
    $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
