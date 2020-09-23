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
(int)$id_pedido = $_POST['id_pedido'];
(int)$id_producto = $_POST['id_producto'];


// $id_usuario = '1';
// $token = "751e1aa84353cf185eb08435fe668a1f69761a27b9908798db24cab750e63d81bde2c7d7fab50560f5858f8f0f205fd8c1d315f532287f8b5c7190f61a531a17";
// $id_pedido = 6;
// $id_producto = 113;

if($id_pedido=="" || $id_producto==""){
    $datosJson['data'][0] = "fail";
    $mensajes ["errores"][]= "Producto o pedido no encontrado";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}

comprobarDatos($id_usuario, $token);

$conexion_db = conexion_db($id_usuario, $conexion);
$statement = $conexion_db->prepare("SELECT * FROM pedido_items WHERE id_pedido = :id_pedido AND id_producto = :id_producto LIMIT 1");
$statement->execute(array(
    ":id_producto" => $id_producto,
    ":id_pedido" => $id_pedido
));
$pedido_item = $statement->fetch();
if($pedido_item){

    $statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido LIMIT 1");
    $statement->execute(array(
        ":id_pedido" => $id_pedido
    ));
    $pedido = $statement->fetch();

    if($pedido){

        if($pedido['vendido'] == 0){
            $date = date('Y-m-d H:i:s');
            $statement = $conexion_db->prepare("SELECT * FROM productos WHERE id_producto = :id_producto LIMIT 1");
            $statement->execute(array(
                ":id_producto" => $id_producto
            ));
            $producto = $statement->fetch();
        
            if($producto){
                if($producto['borrado'] == 0 && $producto['ilimitado'] == 0){
                    $reStock = $producto['stock'] + $pedido_item['cantidad'];
                    $statement = $conexion_db->prepare("UPDATE productos SET stock = :stock WHERE id_producto = :id_producto");
                    $statement->execute(array(
                        ":stock" => $reStock,
                        ":id_producto" => $id_producto
                    ));
                }

                $statement = $conexion_db->prepare("DELETE FROM pedido_items WHERE id_ped_item = :id_ped_item");
                $statement->execute(array(
                    ":id_ped_item" => $pedido_item['id_ped_item']
                ));
                $statement = $conexion_db->prepare("UPDATE pedidos SET fecha_modificacion = :fecha WHERE id_pedido = :id_pedido");
                $statement->execute(array(
                    ":id_pedido" => $id_pedido,
                    ":fecha" => $date
                ));
                $totalItem = $producto['precio'] * $pedido_item['cantidad'];
                $success = "true";
                $datosJson['data'][0] = $producto['codigo'];
                $datosJson['data']['totalItem'] = $totalItem; 

            }
        }else{
            $mensajes ["errores"][]= "El pedido ya ha sido cerrado";
        }

        
    }else{
        $mensajes ["errores"][]= "Pedido no encontrado";
    }

}else{
    $mensajes ["errores"][]= "Item no encontrado o no pertenece a este pedido";
}

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
