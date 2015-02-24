<?php 

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
/**
 * Este script está incluido en el método html de la clase Frontera.class.php.
 * 
 *  La ruta absoluta del bloque está definida en $this->ruta
 */


$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario=$esteBloque["nombre"];

if($_REQUEST["opcion"]!="mostrarActualizacion"){
    include_once("frmConsulta.php");
}else{
    include_once("frmActualizacionDatos.php");
}


?>
