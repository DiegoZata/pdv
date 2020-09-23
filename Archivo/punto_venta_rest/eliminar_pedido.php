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
        $statement = $conexion_db->prepare("SELECT * FROM pedido_items WHERE id_pedido = :id_pedido");
        $statement->execute(array(
            ":id_pedido" => $id_pedido
        ));
        $pedido_items = $statement->fetchALL(PDO::FETCH_ASSOC);

        if($pedido_items){
            foreach($pedido_items as $pedido_item){
                $statement = $conexion_db->prepare("UPDATE productos SET stock = (stock + :stock) WHERE id_producto = :id_producto");
                $statement->execute(array(
                    ":stock" => $pedido_item['cantidad'],
                    ":id_producto" => $pedido_item['id_producto']
                ));
            }
            $statement = $conexion_db->prepare("DELETE FROM pedido_items WHERE id_pedido = :id_pedido");
        $statement->execute(array(
            ":id_pedido" => $id_pedido
        ));
        }
        
        
        $statement = $conexion_db->prepare("DELETE FROM pedidos WHERE id_pedido = :id_pedido");
        $statement->execute(array(
            ":id_pedido" => $id_pedido
        ));

        $success = "true";
        $datosJson['data'][0] = "success";
    }else{
        $mensajes ["errores"][]= "No puede eliminarse. El pedido ya ha sido finalizado";
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
