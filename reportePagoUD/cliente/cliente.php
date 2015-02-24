<?php
require_once 'TransaccionWSService.php';

$miServicio = new TransaccionWSService();

$transaccion=new transaccion();

$transaccion->banco="banco";
$transaccion->cus="cus";
$transaccion->descripcion="descripcion";
$transaccion->estado="estado";
$transaccion->fechaFin="fechaFin";
$transaccion->fechaInicio="fechaInicio";
$transaccion->identificacionUsuario="identificacionUsuario";
$transaccion->referencia="referencia"; 
$transaccion->tipoUsuario="tipoUsuario";
$transaccion->valor="valor";

$creaTransaccion=new creaTransaccion();
$creaTransaccion->objTransaccion=$transaccion;


$resultado=$miServicio->creaTransaccion($creaTransaccion);

var_dump($resultado->return);

?>
