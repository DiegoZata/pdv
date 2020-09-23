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
$codigo_producto = $_POST['codigoProducto'];
$id_pedido = $_POST['id_pedido'];
$cantidad = $_POST['cantidad'];


comprobarDatos($id_usuario, $token);

// $id_usuario = 1;
// $token = $_POST['pruebaData'];
// $codigo_producto = "sku001";
// $id_pedido = 2;
// $cantidad = 5;

(int)$id_usuario = $id_usuario;

$conexion_db = conexion_db($id_usuario, $conexion);

$statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido LIMIT 1");
$statement->execute(array(
    ":id_pedido" => $id_pedido
));
$pedido = $statement->fetch();

if(!$pedido){
    $mensajes ["errores"][0] = "El pedido no existe";
    $datosJson['data'][0] = "fail";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}else{
    if($pedido['vendido'] == 1){
        $mensajes ["errores"][0] = "El pedido ya fue cerrado";
        $datosJson['data'][0] = "fail";
        $datosJson = array_merge($datosJson, $mensajes);
        echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
        die();
    }else{
        $statement = $conexion_db->prepare("SELECT * FROM productos WHERE codigo = :codigo_producto AND borrado = 0 LIMIT 1");
        $statement->execute(array(
            ":codigo_producto" => $codigo_producto
        ));
        $producto = $statement->fetch();

        if(!$producto){
            $mensajes ["errores"][0] = "Producto no encontrado";
            $datosJson['data'][0] = "fail";
            $datosJson = array_merge($datosJson, $mensajes);
            echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
            die();
        }else{
            add_item($producto, $id_pedido, $conexion_db, $cantidad);
            $datosJson['data'][0] = $producto;
        }
    }
}



$success = "true";

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
