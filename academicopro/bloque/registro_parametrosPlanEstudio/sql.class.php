<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registro_parametrosPlanEstudio extends sql {
    function cadena_sql($configuracion,$opcion,$variable="") {

        switch($opcion) {

            case 'buscarParametros':

                $cadena_sql="select distinct parametro_creditosPlan,parametros_OB,parametros_OC,parametros_EI,parametros_EE,parametros_CP ";
                $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio ";
                $cadena_sql.=" where parametro_idPlanEstudio=".$variable;

                break;

            case 'buscarParametrosPlan':

                $cadena_sql="select distinct parametro_idPlanEstudio ";
                $cadena_sql.="from ".$configuracion['prefijo']."parametro_plan_estudio ";
                $cadena_sql.=" where parametro_idPlanEstudio='".$variable."'";

                break;

            case 'buscarDatosPlan':

                $cadena_sql="select planEstudio_propedeutico PROPEDEUTICO";
                $cadena_sql.=" from ".$configuracion['prefijo']."planEstudio ";
                $cadena_sql.=" where id_planEstudio='".$variable."'";

                break;

            case 'tipoCarrera':
                
                $cadena_sql="select cra_tip_cra from accra where cra_cod=".$variable;
                
                break;

            case 'registrarParametros':

                $cadena_sql="INSERT INTO ".$configuracion['prefijo']."parametro_plan_estudio ";
                $cadena_sql.="VALUES ( ";
                $cadena_sql.=" '".$variable[0]."',";
                $cadena_sql.=" '".$variable[1]."',";
                $cadena_sql.=" '".$variable[2]."',";
                $cadena_sql.=" '".$variable[3]."',";
                $cadena_sql.=" '".$variable[4]."',";
                $cadena_sql.=" '".$variable[5]."',";
                $cadena_sql.=" '".$variable[6]."',";
                $cadena_sql.=" '".$variable[7]."',";
                $cadena_sql.=" '".$variable[8]."',";
                $cadena_sql.=" '0',";
                $cadena_sql.=" '0',";
                $cadena_sql.=" '".$variable[9]."')";

                break;

            case 'datos_coordinador':
                $cadena_sql="select distinct usucra_cra_cod, cra_nombre, pen_nro ";
                $cadena_sql.="from geusucra ";
                $cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
                $cadena_sql.="INNER JOIN ACPEN ON geusucra.usucra_cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.=" where usucra_nro_iden=".$variable[0];
                $cadena_sql.=" and pen_nro=".$variable[1];

            break;

            case 'registroEvento':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0,'".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."')";

            break;

            case 'registroComentario':

                $cadena_sql="insert into ".$configuracion['prefijo']."comentario_general_planEstudio ";
                $cadena_sql.="VALUES('".$variable[0]."',";
                $cadena_sql.="'".$variable[1]."',";
                $cadena_sql.="'".$variable[2]."',";
                $cadena_sql.="'".$variable[3]."',";
                $cadena_sql.="'".$variable[4]."',";
                $cadena_sql.="'".$variable[5]."',";
                $cadena_sql.="'".$variable[6]."',";
                $cadena_sql.="'".$variable[7]."')";

            break;

            case 'actualizarParametros':

                $cadena_sql="UPDATE ".$configuracion['prefijo']."parametro_plan_estudio SET ";
                $cadena_sql.=" parametro_creditosPlan = ".$variable[1];
                $cadena_sql.=" parametros_OB  = ".$variable[5];
                $cadena_sql.=" parametros_OC = ".$variable[6];
                $cadena_sql.=" parametros_EI = ".$variable[7];
                $cadena_sql.=" parametros_EE = ".$variable[8];
                $cadena_sql.=" WHERE  parametro_idPlanEstudio = ".$variable[0];

                break;

             

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>