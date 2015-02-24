<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


//======= Revisar si no hay acceso ilegal ==============
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
//======================================================
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/html.class.php");

function sumarObligatoriosYElectivos($numObligatorios,$numElectivos) {
    require_once("clase/config.class.php");
    setlocale(LC_MONETARY, 'en_US');
    $esta_configuracion = new config();
    $configuracion = $esta_configuracion->variable();
    //echo $num_div;exit;
    //$funcion = new funcionGeneral();
    //Conectarse a la base de datos
    $conexion=new funcionGeneral();
    $conexionOracle=$conexion->conectarDB($configuracion,"coordinadorCred");
    //$valor=$acceso_db->verificar_variables($valor);
    $html = new html();
    $mensaje="";   
    if(is_numeric($numObligatorios) && is_numeric($numElectivos)){
            $total_espacios=(int)$numObligatorios + (int)$numElectivos;
            $html = "<input type='text' name='total' id='total' size='6' value='".$total_espacios."' readonly='true'>";
    }else{
            $html = "";
    }
    $respuesta = new xajaxResponse();
    $respuesta->addAssign("div_total", "innerHTML", $html);
    
    return $respuesta;
}


?>
