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
$doc = $_POST['doc'];
$nombre = $_POST['nombre'];
$tel = $_POST['tel'];
$dir = $_POST['dir'];
(int)$id_cliente = $_POST['id_cliente'];


// $id_usuario = '1';
// $token = "751e1aa84353cf185eb08435fe668a1f69761a27b9908798db24cab750e63d81bde2c7d7fab50560f5858f8f0f205fd8c1d315f532287f8b5c7190f61a531a17";
// $codigo = 'cod223';
// $descripcion = 'desc prueba editado';
// $stock = 20;
// $precioVenta = 200;
// (int)$id_producto = 98;
// $ilimitado = 1;

if($id_cliente==""){
    $datosJson['data'][0] = "fail";
    $mensajes ["errores"][]= "Cliente no encontrado";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}

comprobarDatos($id_usuario, $token);

if($doc == ""){
    $mensajes ["errores"][] = "Falta el número de documento";
    $datosJson['data'][0] = "fail";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}elseif($nombre == ""){
    $mensajes ["errores"][] = "Falta el nombre del cliente";
    $datosJson['data'][0] = "fail";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
}else{
    $conexion_db = conexion_db($id_usuario, $conexion);
    $statement = $conexion_db->prepare("SELECT * FROM clientes WHERE id_cliente != :id_cliente AND ci = :ci AND borrado = 0 LIMIT 1");
    $statement->execute(array(
        ":id_cliente" => $id_cliente,
        ":ci" => $doc
    ));
    $resultado = $statement->fetch();
    if($resultado){
        $mensajes ["errores"][] = "Ya existe otro cliente con el mismo documento";
        $datosJson['data'][0] = "fail";
        $datosJson = array_merge($datosJson, $mensajes);
        echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
        die();
    }
    $statement = $conexion_db->prepare("SELECT * FROM clientes WHERE id_cliente = :id_cliente AND borrado = 0 LIMIT 1");
    $statement->execute(array(
        ":id_cliente" => $id_cliente
    ));
    $cliente = $statement->fetch();
    if($cliente){
        $statement = $conexion_db->prepare("UPDATE clientes SET ci = :ci, 
        nombre = :nombre, telefono = :telefono, direccion = :direccion
        WHERE id_cliente = :id_cliente AND borrado = 0");
        $statement->execute(array(
            ":ci" => $doc,
            ":nombre" => $nombre,
            ":telefono" => $tel,
            ":direccion" => $dir,
            ":id_cliente" => $id_cliente
        ));
        $success = "true";
        $datosJson['data'][0] = "success";
    }else{
        $mensajes ["errores"][]= "Cliente no encontrado";
    }
    

    
}

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
