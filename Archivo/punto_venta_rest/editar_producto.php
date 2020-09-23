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
$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];
$stock = $_POST['stock'];
$precioVenta = $_POST['precioVenta'];
$precioCosto = $_POST['precioCosto'];
$precioMayorista = $_POST['precioMayorista'];
(int)$id_producto = $_POST['id_producto'];
$ilimitado = $_POST['ilimitado'];


// $id_usuario = '1';
// $token = "751e1aa84353cf185eb08435fe668a1f69761a27b9908798db24cab750e63d81bde2c7d7fab50560f5858f8f0f205fd8c1d315f532287f8b5c7190f61a531a17";
// $codigo = 'cod223';
// $descripcion = 'desc prueba editado';
// $stock = 20;
// $precioVenta = 200;
// (int)$id_producto = 98;
// $ilimitado = 1;

if($id_producto==""){
    $datosJson['data'][0] = "fail";
    $mensajes ["errores"][]= "Producto no encontrado";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}

comprobarDatos($id_usuario, $token);

if($codigo == '' || $descripcion == '' || $precioVenta =='' || $stock == ''){
    $mensajes ["errores"][] = "Complete los campos correctamente";
    $datosJson['data'][0] = "fail";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}else{
    $conexion_db = conexion_db($id_usuario, $conexion);
    $statement = $conexion_db->prepare("SELECT * FROM productos WHERE id_producto != :id_producto AND codigo = :codigo AND borrado = 0 LIMIT 1");
    $statement->execute(array(
        ":id_producto" => $id_producto,
        ":codigo" => $codigo
    ));
    $resultado = $statement->fetch();
    if($resultado){
        $mensajes ["errores"][] = "Ya existe otro producto con el mismo código";
        $datosJson['data'][0] = "fail";
        $datosJson = array_merge($datosJson, $mensajes);
        echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
        die();
    }
    $statement = $conexion_db->prepare("SELECT * FROM productos WHERE id_producto = :id_producto AND borrado = 0 LIMIT 1");
    $statement->execute(array(
        ":id_producto" => $id_producto
    ));
    $producto = $statement->fetch();
    if($producto){
        $statement = $conexion_db->prepare("UPDATE productos SET codigo = :codigo, 
        descripcion = :descripcion, stock = :stock, ilimitado = :ilimitado, 
        precio = :precioVenta, precio_costo = :precioCosto, precio_mayorista = :precioMayorista WHERE id_producto = :id_producto AND borrado = 0");
        $statement->execute(array(
            ":stock" => $stock,
            ":codigo" => $codigo,
            ":descripcion" => $descripcion,
            ":precioVenta" => $precioVenta,
            ":precioCosto" => $precioCosto,
            ":precioMayorista" => $precioMayorista,
            ":id_producto" => $id_producto,
            ":ilimitado" => $ilimitado
        ));
        $success = "true";
        $datosJson['data'][0] = "success";
    }else{
        $mensajes ["errores"][]= "Producto no encontrado";
    }
    

    
}

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
