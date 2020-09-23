<?php

$bd_config=array(
  'basedatos'=>'cim_users',
  'usuario' =>'root',
  'pass' => ''
);

function bd_user($db){
  $bd_user=array(
    'basedatos'=>$db,
    'usuario' =>'admin2',
    'pass' => 'admin'
  );

  return $bd_user;
}

?>
