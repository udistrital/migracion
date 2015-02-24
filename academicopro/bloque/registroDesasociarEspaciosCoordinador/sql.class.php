<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroDesasociarEspaciosCoordinador extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case 'proyectos_curriculares':

                $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                $cadena_sql.="from accra ";
                $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                $cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                $cadena_sql.="where pen_nro>200 ";
                $cadena_sql.=" and USUCRA_NRO_IDEN=".$variable;
                $cadena_sql.=" order by 3";

                break;

             case 'buscarNivel':
               $cadena_sql="SELECT DISTINCT(id_nivel) FROM ".$configuracion['prefijo']."planEstudio_espacio ";
               $cadena_sql.="WHERE id_planEstudio =".$variable;
               $cadena_sql.=" ORDER BY id_nivel";

               break;

            case 'buscarEncabezados':
               $cadena_sql="SELECT id_encabezado, encabezado_nombre, encabezado_descripcion, id_estado, id_aprobado, id_cargado, ";
               $cadena_sql.="encabezado_creditos, encabezado_nivel ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."encabezado ";
               $cadena_sql.="WHERE id_planEstudio =".$variable[0];
               $cadena_sql.=" AND id_proyectoCurricular =".$variable[1];
               $cadena_sql.=" AND id_clasificacion =".$variable[2];

               break;

            case 'buscarEspaciosxEstado':
               $cadena_sql="SELECT ".$configuracion['prefijo']."espacio_academico.id_espacio, espacio_nombre, espacio_nroCreditos, id_nivel, ";
               $cadena_sql.="id_clasificacion, espacio_horasDirecto, espacio_horasCooperativo, espacio_horasAutonomo, ".$configuracion['prefijo'];
               $cadena_sql.="planEstudio_espacio.id_estado, ".$configuracion['prefijo']."planEstudio_espacio.id_aprobado, ".$configuracion['prefijo']."planEstudio_espacio.id_cargado ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio_espacio ";
               $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ON ".$configuracion['prefijo'];
               $cadena_sql.="planEstudio_espacio.id_espacio=".$configuracion['prefijo']."espacio_academico.id_espacio ";
               $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacioEncabezado ON ".$configuracion['prefijo'];
               $cadena_sql.="planEstudio_espacio.id_espacio=".$configuracion['prefijo']."espacioEncabezado.id_espacio ";
               $cadena_sql.="and sga_planEstudio_espacio.id_planEstudio=sga_espacioEncabezado.id_planEstudio ";
               $cadena_sql.="WHERE ".$configuracion['prefijo']."planEstudio_espacio.id_planEstudio=".$variable[0]." AND id_clasificacion=".$variable[1];
               $cadena_sql.=" AND id_nivel=".$variable[2];
               $cadena_sql.=" AND espacio_nroCreditos=".$variable[3];              
               $cadena_sql.=" AND ".$configuracion['prefijo']."espacioEncabezado.id_encabezado=".$variable[5];
               $cadena_sql.=" AND ".$configuracion['prefijo']."espacioEncabezado.id_estado=1";

               break;

            case 'agruparEspacio':
               $cadena_sql="INSERT INTO ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="(id_planEstudio, id_proyectoCurricular, id_espacio, id_encabezado ) ";
               $cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."' )";
               break;

            case 'desagruparEspacio':
               $cadena_sql="update ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="set id_estado=0, id_aprobado=0 ";
               $cadena_sql.="WHERE id_planEstudio=".$variable[0];
               $cadena_sql.=" AND id_proyectoCurricular=".$variable[1];
               $cadena_sql.=" AND id_espacio=".$variable[2];
               $cadena_sql.=" AND id_encabezado=".$variable[3];
               break;

            case 'buscarEspacioGrupo':
               $cadena_sql="SELECT id_planEstudio, id_proyectoCurricular, id_espacio, id_encabezado, id_aprobado FROM ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="WHERE id_planEstudio=".$variable[0]." AND ";
               $cadena_sql.="id_proyectoCurricular=".$variable[1]." AND ";
               $cadena_sql.="id_espacio=".$variable[2]." AND ";
               $cadena_sql.="id_encabezado=".$variable[3]." AND ";
               $cadena_sql.="id_estado=1";

               break;

            case 'buscarEspacioAsociados':
               $cadena_sql="SELECT ".$configuracion['prefijo']."espacioEncabezado.id_espacio, espacio_nombre FROM ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ON ".$configuracion['prefijo'];
               $cadena_sql.="espacio_academico.id_espacio = ".$configuracion['prefijo'];
               $cadena_sql.="espacioEncabezado.id_espacio WHERE ";
               $cadena_sql.="id_encabezado=".$variable;

               break;           

            case 'buscarCreditos':
               $cadena_sql="SELECT DISTINCT(espacio_nroCreditos) ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio_espacio ";
               $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ON ".$configuracion['prefijo'];
               $cadena_sql.="planEstudio_espacio.id_espacio=".$configuracion['prefijo']."espacio_academico.id_espacio ";
               $cadena_sql.="WHERE id_planEstudio=".$variable;
               $cadena_sql.=" ORDER BY ".$configuracion['prefijo']."espacio_academico.espacio_nroCreditos ASC";

               break;
               
            case 'crearEncabezado':
               $cadena_sql="INSERT INTO ".$configuracion['prefijo']."encabezado ";
               $cadena_sql.="(encabezado_nombre, encabezado_descripcion, id_planEstudio, id_proyectoCurricular, ";
               $cadena_sql.="id_clasificacion, id_estado, id_aprobado, id_cargado, encabezado_creditos, encabezado_nivel ) ";
               $cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', '".$variable[4]."',  ";
               $cadena_sql.="'1', '0', '0', '".$variable[5]."', '".$variable[6]."')";
               break;

            case "bimestreActual":
                $cadena_sql="select ape_ano, ape_per " ;
                $cadena_sql.="from acasperi ";
                $cadena_sql.="where ape_estado like '%A%'";
                //                echo $this->cadena_sql;
                //                exit;

                break;

            case 'registroNombreGeneral':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                $cadena_sql.="'".$variable[1]."', ";
                $cadena_sql.="'13', ";
                $cadena_sql.="'Se creo Nombre General', ";
                $cadena_sql.="'".$variable[2]."-".$variable[3].",";
                $cadena_sql.=" ".$variable[4].", ".$variable[5].", ".$variable[6]."', ";
                $cadena_sql.="'".$variable[5]."')";

                break;

            case 'registroDesagrupar':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                $cadena_sql.="'".$variable[1]."', ";
                $cadena_sql.="'14', ";
                $cadena_sql.="'Desagrupo Espacio Académico', ";
                $cadena_sql.="'".$variable[2]."-".$variable[3].",".$variable[7].",";
                $cadena_sql.=" ".$variable[5].", ".$variable[6]."', ";
                $cadena_sql.="'".$variable[5]."')";

                break;

          case 'clasificacion':
                $cadena_sql="SELECT  id_clasificacion, clasificacion_nombre, clasificacion_abrev";
                $cadena_sql.=" FROM ".$configuracion['prefijo']."espacio_clasificacion";
                //$cadena_sql.="where id_clasificacion!=5 ";
                break;

        }
        //echo $cadena_sql."<br>";
        return $cadena_sql;
    }


}
?>