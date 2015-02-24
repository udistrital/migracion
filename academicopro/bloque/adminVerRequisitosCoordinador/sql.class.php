<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_adminVerRequisitosCoordinador extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {
            

            case 'espacios_academicos':

                $cadena_sql="SELECT DISTINCT EA.id_espacio, espacio_nombre FROM ".$configuracion['prefijo']."espacio_academico EA ";
                $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PE ON EA.id_espacio=PE.id_espacio ";
                $cadena_sql.="WHERE PE.id_planEstudio=".$variable;
                $cadena_sql.=" ORDER BY EA.espacio_nombre";

                break;

            case 'requisitos_registrados':

                $cadena_sql="SELECT requisitos_idEspacioPrevio,EA.espacio_nombre, requisitos_idEspacioPosterior, EAS.espacio_nombre,requisitos_previoAprobado";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio RE";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON  EA.id_espacio=RE.requisitos_idEspacioPrevio";
                $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico EAS ON  EAS.id_espacio=RE.requisitos_idEspacioPosterior";
                $cadena_sql.=" WHERE requisitos_idPlanEstudio=".$variable;
                $cadena_sql.=" ORDER BY requisitos_idEspacioPosterior, requisitos_idEspacioPrevio";

                break;



            case 'proyectos_curriculares':
                
                $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                $cadena_sql.="from accra ";
                $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                $cadena_sql.="where pen_nro>200 ";
                $cadena_sql.=" and USUCRA_NRO_IDEN=".$variable;
                $cadena_sql.=" order by 3";                
                
                break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>