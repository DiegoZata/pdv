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

// $user = "arsenio.d.g.g@gmail.com";
// $pass = "202cb962ac59075b964b07152d234b70";

$user = $_POST['user'];
$pass = $_POST['pass'];

if (!$user) {
  $mensajes ["errores"][] = "Complete el campo Usuario";
}elseif (!$pass) {
  $mensajes ["errores"][] = "Complete el campo Contraseña";
}else{
  $usuarioLogin = comprobarLogin($user, $pass, $conexion);
  if (!$usuarioLogin) {
    $mensajes ["errores"][] = "Usuario o contraseña incorrectos";
  }else{
    $success = "true";
    $datosJson['data'][0] = "success";
    $datosJson['data'][1] = $usuarioLogin;
    $datosJson['data'][2] = cifrarDato($usuarioLogin['id_usuario']);
  }
}

if ($success == "false") {
  $datosJson['data'][0] = "fail";
}


$datosJson = array_merge($datosJson, $mensajes);

// echo utf8_converter($datosJson);

echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);


?>
