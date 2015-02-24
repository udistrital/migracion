<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_admin_paginaPrincipalCoordinador extends sql {	//@ Método que crea las sentencias sql para el modulo admin_noticias
    function cadena_sql($configuracion,$tipo,$variable="") {
        switch($tipo) {

            case "datos_coordinador":
                    $cadena_sql = "SELECT cra_cod,cra_nombre FROM accra WHERE CRA_EMP_NRO_IDEN = $variable AND cra_estado = 'A'";
                    break;

                case 'fechas_horarios':

                    $cadena_sql="SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhhmmss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmddhhmmss') ";
                    $cadena_sql.=" from accaleventos";
                    $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='A') ";
                    $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='A') ";
                    $cadena_sql.=" AND ACE_ESTADO='A' AND ACE_CRA_COD='".$variable."' ";
                    $cadena_sql.=" AND ACE_COD_EVENTO IN (2)";

                    break;

                case 'fechas_preinscripcion':

                    $cadena_sql="SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhhmmss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmddhhmmss') ";
                    $cadena_sql.=" from accaleventos";
                    $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='A') ";
                    $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='A') ";
                    $cadena_sql.=" AND ACE_ESTADO='A' AND ACE_CRA_COD='".$variable."' ";
                    $cadena_sql.=" AND ACE_COD_EVENTO IN (15)";

                    break;

                case 'fechas_inscripcion':

                    $cadena_sql="SELECT ACE_COD_EVENTO, ACE_CRA_COD, TO_CHAR(ACE_FEC_INI,'YYYYmmddhhmmss'), TO_CHAR(ACE_FEC_FIN,'YYYYmmddhhmmss') ";
                    $cadena_sql.=" from accaleventos";
                    $cadena_sql.=" WHERE ACE_ANIO = (SELECT APE_ANO FROM ACASPERI WHERE APE_ESTADO='A') ";
                    $cadena_sql.=" AND ACE_PERIODO = (SELECT APE_PER FROM ACASPERI WHERE APE_ESTADO='A') ";
                    $cadena_sql.=" AND ACE_ESTADO='A' AND ACE_CRA_COD='".$variable."' ";
                    $cadena_sql.=" AND ACE_COD_EVENTO IN (8, 9)";

                    break;


        }#Cierre de switch
        return $cadena_sql;
    }#Cierre de funcion cadena_sql
}#Cierre de clase
?>
