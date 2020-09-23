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
$id_pedido_actual = $_POST['id_pedido_actual'];


comprobarDatos($id_usuario, $token);

(int)$id_usuario = $id_usuario;

$conexion_db = conexion_db($id_usuario, $conexion);

if($id_pedido_actual == ""){
    $statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_usuario = :id_usuario AND guardado = 0 AND vendido = 0 LIMIT 1");
    $statement->execute(array(
        ":id_usuario" => $id_usuario
    ));
    $pedido = $statement->fetch(PDO::FETCH_ASSOC);
}else{
    $id_pedido_actual = (int)$id_pedido_actual;
    $statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido LIMIT 1");
    $statement->execute(array(
        ":id_pedido" => $id_pedido_actual
    ));
    $pedido = $statement->fetch(PDO::FETCH_ASSOC);
    if(!$pedido){
        $mensajes ["errores"][] = "Pedido no encontrado";
        $datosJson['data'][0] = "fail";
        $datosJson = array_merge($datosJson, $mensajes);
        echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
        die();
    }
}



if(!$pedido){
    $date = date('Y-m-d H:i:s');
    $statement = $conexion_db->prepare("INSERT INTO pedidos (id_pedido, id_cliente, id_usuario, total, vendido, guardado, fecha_creacion, fecha_modificacion, cerrado) 
    VALUES (null, 1, :id_usuario, 0, 0, 0, :fecha, :fecha, 0)");
    $statement->execute(array(
        ":id_usuario" => $id_usuario,
        ":fecha" => $date
    ));
    $statement = $conexion_db->prepare("SELECT * FROM pedidos WHERE id_usuario = :id_usuario AND guardado = 0 AND vendido = 0 LIMIT 1");
    $statement->execute(array(
        ":id_usuario" => $id_usuario
    ));
    $pedido = $statement->fetch(PDO::FETCH_ASSOC);
}

$statement = $conexion_db->prepare("SELECT id_ped_item, productos.id_producto, productos.descripcion, productos.codigo, productos.precio, cantidad FROM pedido_items, productos WHERE pedido_items.id_pedido = :id_pedido AND pedido_items.id_producto = productos.id_producto");
$statement->execute(array(
    ":id_pedido" => $pedido['id_pedido']
));
$pedido_items = $statement->fetchALL(PDO::FETCH_ASSOC);



$datosJson['data'][0] = $pedido;

$success = "true";

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}



$datosJson = array_merge($datosJson, $mensajes);

if($pedido_items){
    foreach($pedido_items as $producto){
        $productos['productos'][] = $producto;
    }
    $datosJson = array_merge($datosJson, $productos);
}



// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
