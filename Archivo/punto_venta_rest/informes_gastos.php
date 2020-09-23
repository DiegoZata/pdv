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
//$codigo = $_POST['codigo'];
//(int)$id_pedido = $_POST['id_pedido'];
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];


comprobarDatos($id_usuario, $token);

$conexion_db = conexion_db($id_usuario, $conexion);

//$sentencia_sql = "SELECT productos.codigo, pedidos.id_pedido, productos.descripcion, pedido_items.cantidad, pedidos.fecha_modificacion FROM pedidos, productos, pedido_items WHERE pedidos.id_pedido = pedido_items.id_pedido AND pedido_items.id_producto = productos.id_producto";
$sentencia_sql = "SELECT * FROM gastos";
$array_execute = array();

if($fecha_desde == "" && $fecha_hasta == ""){
   // $statement = $conexion_db->prepare("SELECT productos.codigo, pedidos.id_pedido, productos.descripcion, pedido_items.cantidad, pedidos.fecha_modificacion FROM pedidos, productos, pedido_items WHERE pedidos.id_pedido = pedido_items.id_pedido AND pedido_items.id_producto = productos.id_producto ORDER BY pedidos.id_pedido DESC");
   $statement = $conexion_db->prepare("SELECT * FROM gastos");
    $statement->execute();
    $gastos = $statement->fetchALL(PDO::FETCH_ASSOC);
}else{
    if($codigo != ""){
        $sentencia_sql = $sentencia_sql." AND productos.codigo = '".$codigo."'";
    }
    
    if($id_pedido != ""){
        $sentencia_sql = $sentencia_sql." AND pedidos.id_pedido = $id_pedido";
    }

    if($fecha_desde != "" && $fecha_hasta != ""){
        $sentencia_sql = $sentencia_sql." AND pedidos.fecha_modificacion BETWEEN '$fecha_desde 00:00:00' AND '$fecha_hasta 23:59:59'";
    }

    if($fecha_desde != ""){
        $sentencia_sql = $sentencia_sql." AND pedidos.fecha_modificacion > '$fecha_desde 00:00:00'";
    }

    if($fecha_hasta != ""){
        $sentencia_sql = $sentencia_sql." AND pedidos.fecha_modificacion < '$fecha_hasta 23:59:59'";
    }

    $sentencia_sql = $sentencia_sql." ORDER BY pedidos.id_pedido DESC";

    $statement = $conexion_db->prepare($sentencia_sql);
    $statement->execute();
    $gastos = $statement->fetchALL(PDO::FETCH_ASSOC);
}





if($gastos){
    foreach($gastos as $gasto){
        $datosJson['data'][] = $informe;
    }
    $success = "true";
}else{
    $mensajes ["errores"][]= "No se han encontrado resultados";
}



if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
