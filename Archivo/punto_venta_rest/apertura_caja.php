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
$fondo_caja = $_POST['fondoCaja'];
// $parametro = "";


comprobarDatos($id_usuario, $token);

$conexion_db = conexion_db($id_usuario, $conexion);

$statement = $conexion_db->prepare("SELECT * FROM apertura_caja WHERE id_usuario=:id_usuario LIMIT 1");
$statement->execute(array(":id_usuario" => $id_usuario));
$apertura_caja = $statement->fetch(PDO::FETCH_ASSOC);

if(!$apertura_caja){
    $statement = $conexion_db->prepare("INSERT INTO apertura_caja (id_apertura, efectivo, id_usuario, abierto) VALUES (null, :efectivo, :id_usuario, 1)");
    $statement->execute(array(
        ":efectivo" => $fondo_caja,
        ":id_usuario" => $id_usuario
    ));
    $success = "true";
}else{
    if($apertura_caja['abierto'] == 0){
        $statement = $conexion_db->prepare("UPDATE apertura_caja SET efectivo = :efectivo, abierto = 1 WHERE id_usuario = :id_usuario");
        $statement->execute(array(
            ":efectivo" => $fondo_caja,
            ":id_usuario" => $id_usuario
        ));
        $success = "true";
    }else{
        $mensajes ["errores"][]= "La caja ya está abierta";
    }
}




if ($success == "false") {
    $datosJson['data'][0] = "fail";
}else{
    $datosJson['data'][0] = "success";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
