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
$ilimitado = $_POST['ilimitado'];
$precioCosto = $_POST['precioCosto'];
$precioMayorista = $_POST['precioMayorista'];


// $id_usuario = '1';
// $token = "751e1aa84353cf185eb08435fe668a1f69761a27b9908798db24cab750e63d81bde2c7d7fab50560f5858f8f0f205fd8c1d315f532287f8b5c7190f61a531a17";
// $codigo = 'cod223';
// $descripcion = 'desc prueba';
// $stock = 20;
// $precioVenta = 200;

comprobarDatos($id_usuario, $token);

if($codigo == '' || $descripcion == '' || $precioVenta =='' || $stock == ''){
    $mensajes ["errores"][] = "Complete los campos correctamente";
    $datosJson['data'][0] = "fail";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}else{
    $conexion_db = conexion_db($id_usuario, $conexion);
    $statement = $conexion_db->prepare("SELECT * FROM productos WHERE codigo = :codigo AND borrado = 0 LIMIT 1");
    $statement->execute(array(
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
    $statement = $conexion_db->prepare("INSERT INTO productos (id_producto, id_categoria, id_proveedor, codigo, descripcion, stock, precio, borrado, ilimitado, precio_costo, precio_mayorista) 
    VALUES (null, 1, 1, :codigo, :descripcion, :stock, :precioVenta, 0, :ilimitado, :precioCosto, :precioMayorista)");
    $statement->execute(array(
        ":stock" => $stock,
        ":codigo" => $codigo,
        ":descripcion" => $descripcion,        
        ":precioVenta" => $precioVenta,
        ":ilimitado" => $ilimitado,
        ":precioCosto" => $precioCosto,
        ":precioMayorista" => $precioMayorista
      ));
      
    $success = "true";
    $datosJson['data'][0] = "success";
}

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
