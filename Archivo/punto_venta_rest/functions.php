<?php
date_default_timezone_set("America/Asuncion");
// Conexion a la base de datos
function conexion($bd_config){
  try {
    $conexion=new PDO('mysql:host=localhost;dbname='.$bd_config['basedatos'],$bd_config['usuario'],$bd_config['pass']);
    return $conexion;
  } catch (PDOException $e) {
    $mensajes ["errores"][]= "";
    $mensajes ["errores"][]= "No hay conexión con la bd";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
    return false;
  }
}

function conexion_db($id_user, $conexion){
  $statement = $conexion->prepare("SELECT id_cliente FROM usuarios WHERE id_usuario = $id_user LIMIT 1");
  $statement->execute();
  $resultado = $statement->fetch();
  $id_cliente = $resultado['id_cliente'];
  $statement = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente = $id_cliente LIMIT 1");
  $statement->execute();
  $resultado = $statement->fetch();
  $cliente = $resultado;
  try {
    $conexion_db=new PDO('mysql:host=localhost;dbname='.$cliente['db_name'],$cliente['db_user'],$cliente['db_pass']);
    return $conexion_db;
  } catch (PDOException $e) {
    $mensajes ["errores"][]= "";
    $mensajes ["errores"][]= "No hay conexión con la bd";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
    return false;
  }
}

// Funcion convertidor de json
function utf8_converter($array){
      array_walk_recursive($array, function(&$item){
          $item = utf8_encode( $item );
      });
      return json_encode( $array , JSON_UNESCAPED_UNICODE);
}

// Funcion para comprobar contraseña y correo LOGIN

function comprobarLogin($user, $pass, $conexion){
  $statement = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :user AND pass = md5(:pass) LIMIT 1");
  $statement->execute(array(
    ":user" => $user,
    ":pass" => $pass
  ));
  $resultado = $statement->fetch();
  return ($resultado) ? $resultado : false;
}

// Funcion para cifrar Dato
function cifrarDato($dato){
  $firstMd5 = md5($dato);
  $secondMd5 = md5 ($firstMd5);
  $firstSha512 = hash("sha512", $secondMd5);
  $secondSha512 = hash("sha512", $firstSha512);
  return $secondSha512;
}

// COMPROBAR VERACIDAD DE ID DE USUARIO Y TOKEN
function comprobarDatos($id_user, $token){
  $id_cifrado = cifrarDato($id_user);
  if($id_cifrado != $token){
    $datosJson['data'][0] = "fail";
    $mensajes ["errores"][]= "Fatal error";
    $datosJson = array_merge($datosJson, $mensajes);
    echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
    die();
  }
}

// AGREGAR ITEM AL PEDIDO
function add_item($producto, $id_pedido, $conexion_db, $cantidad){
  $statement = $conexion_db->prepare("SELECT * FROM pedido_items WHERE id_producto = :id_producto AND id_pedido = :id_pedido LIMIT 1");
  $statement->execute(array(
    ":id_producto" => $producto['id_producto'],
    ":id_pedido" => $id_pedido
  ));
  $resultado = $statement->fetch();
  $date = date('Y-m-d H:i:s');
  if(!$resultado){
    if($producto['ilimitado'] == 0){
      $restante = $producto['stock'] - $cantidad;
      if($restante<0){
        $mensajes ["errores"][0] = "Stock insuficiente, quedan ".$producto['stock']." unidades";
        $datosJson['data'][0] = "fail";
        $datosJson = array_merge($datosJson, $mensajes);
        echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
        die();
      }else{
        $statement = $conexion_db->prepare("INSERT INTO pedido_items (id_ped_item, id_producto, id_pedido, cantidad) 
        VALUES (null, :id_producto, :id_pedido, :cantidad)");
        $statement->execute(array(
            ":id_producto" => $producto['id_producto'],
            ":id_pedido" => $id_pedido,
            ":cantidad" => $cantidad
        ));
        $statement = $conexion_db->prepare("UPDATE productos SET stock = (stock-:cantidad) WHERE id_producto = :id_producto");
        $statement->execute(array(
          ":cantidad" => $cantidad,
          ":id_producto" => $producto['id_producto']
        ));
        $statement = $conexion_db->prepare("UPDATE pedidos SET fecha_modificacion = :fecha WHERE id_pedido = :id_pedido");
        $statement->execute(array(
          ":fecha" => $date,
          ":id_pedido" => $id_pedido
        ));
      }
    }else{
      $statement = $conexion_db->prepare("INSERT INTO pedido_items (id_ped_item, id_producto, id_pedido, cantidad) 
      VALUES (null, :id_producto, :id_pedido, :cantidad)");
      $statement->execute(array(
          ":id_producto" => $producto['id_producto'],
          ":id_pedido" => $id_pedido,
          ":cantidad" => $cantidad
      ));
      $statement = $conexion_db->prepare("UPDATE pedidos SET fecha_modificacion = :fecha WHERE id_pedido = :id_pedido");
      $statement->execute(array(
        ":fecha" => $date,
        ":id_pedido" => $id_pedido
      ));
    }
    
  }else{
    if($producto['ilimitado'] == 0){
      $restante = $producto['stock'] - $cantidad;
      if($restante<0){
        $mensajes ["errores"][0] = "Stock insuficiente, quedan ".$producto['stock']." unidades";
        $datosJson['data'][0] = "fail";
        $datosJson = array_merge($datosJson, $mensajes);
        echo json_encode( $datosJson , JSON_UNESCAPED_UNICODE);
        die();
      }else{
        $nuevaCantidad = $resultado['cantidad'] + $cantidad;
        $statement = $conexion_db->prepare("UPDATE pedido_items SET cantidad = :cantidad WHERE id_ped_item = :id_ped_item");
        $statement->execute(array(
            ":id_ped_item" => $resultado['id_ped_item'],
            ":cantidad" => $nuevaCantidad
        ));
        $statement = $conexion_db->prepare("UPDATE productos SET stock = (stock-:cantidad) WHERE id_producto = :id_producto");
        $statement->execute(array(
          ":cantidad" => $cantidad,
          ":id_producto" => $producto['id_producto']
        ));
        $statement = $conexion_db->prepare("UPDATE pedidos SET fecha_modificacion = :fecha WHERE id_pedido = :id_pedido");
        $statement->execute(array(
          ":fecha" => $date,
          ":id_pedido" => $id_pedido
        ));
      }
    }else{

      $nuevaCantidad = $resultado['cantidad'] + $cantidad;

      $statement = $conexion_db->prepare("UPDATE pedido_items SET cantidad = :cantidad WHERE id_ped_item = :id_ped_item");
      $statement->execute(array(
          ":id_ped_item" => $resultado['id_ped_item'],
          ":cantidad" => $nuevaCantidad
      ));
      $statement = $conexion_db->prepare("UPDATE pedidos SET fecha_modificacion = :fecha WHERE id_pedido = :id_pedido");
      $statement->execute(array(
        ":fecha" => $date,
        ":id_pedido" => $id_pedido
      ));
    }
  }

}



?>
