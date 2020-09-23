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
(int)$id_pedido = $_POST['id_pedido_actual'];
$total = $_POST['total'];


if($id_pedido==""){
    $datosJson['data'][0] = "fail";
    $mensajes ["errores"][]= "Pedido no encontrado";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}

comprobarDatos($id_usuario, $token);

$conexion_db = conexion_db($id_usuario, $conexion);
$statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido LIMIT 1");
$statement->execute(array(
    ":id_pedido" => $id_pedido
));
$pedido = $statement->fetch();
if($pedido){
    if($pedido['vendido']==0){
        $date = date('Y-m-d H:i:s');
        $statement = $conexion_db->prepare("UPDATE pedidos SET vendido = 1, fecha_modificacion = :fecha, total = :total WHERE id_pedido = :id_pedido");
        $statement->execute(array(
            ":id_pedido" => $id_pedido,
            ":total" => $total,
            ":fecha" => $date
        ));
        $success = "true";
        $datosJson['data'][0] = "success";
    }else{
        $mensajes ["errores"][]= "No pudo realizarse la acción. El pedido ya ha sido cerrado";
    }
    
}else{
    $mensajes ["errores"][]= "Pedido no encontrado";
}

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
