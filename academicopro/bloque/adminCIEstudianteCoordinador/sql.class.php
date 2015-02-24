<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminCIEstudianteCoordinador extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="") {
        switch($tipo) {

            #consulta de caracteristicas generales de espacios academicos en un plan de estudios,
            #consulta de caracteristicas generales de espacios academicos en un plan de estudios
            case 'buscarInfoEstudiante':

                $cadena_sql="select est_ind_cred ";
                $cadena_sql.="from acest ";
                $cadena_sql.="where est_cod=".$variable;


            break;


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
