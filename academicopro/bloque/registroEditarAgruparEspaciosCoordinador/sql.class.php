<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroEditarAgruparEspaciosCoordinador extends sql {
    function cadena_sql($configuracion,$conexion, $opcion,$variable="") {

        switch($opcion) {

            case 'registroModificarEncabezado':

                $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                $cadena_sql.="'".$variable[1]."', ";
                $cadena_sql.="'21', ";
                $cadena_sql.="'Se modifico Nombre General', ";
                $cadena_sql.="'".$variable[2]."-".$variable[3].",";
                $cadena_sql.=" ".$variable[7]."-".$variable[4].", ".$variable[5].", ".$variable[6]."', ";
                $cadena_sql.="'".$variable[5]."')";

                break;

            case 'modificarEncabezado':

               $cadena_sql="UPDATE ".$configuracion['prefijo']."encabezado ";
               $cadena_sql.="SET encabezado_nombre='".$variable[0]."', ";
               $cadena_sql.="encabezado_descripcion='".$variable[1]."', ";
               $cadena_sql.="id_clasificacion ='".$variable[2]."', ";
               $cadena_sql.="encabezado_creditos ='".$variable[3]."', ";
               $cadena_sql.="encabezado_nivel ='".$variable[4]."' ";
               $cadena_sql.="WHERE sga_encabezado.id_encabezado =".$variable[5]." ";
               //$cadena_sql.="AND sga_encabezado.id_estado = 1 ";
               //$cadena_sql.="AND sga_encabezado.id_aprobado = 0 ";
               //$cadena_sql.="AND sga_encabezado.id_cargado = 0";

               break;

            case 'buscarIdEncabezado':
               $cadena_sql="SELECT id_encabezado, encabezado_nombre, encabezado_descripcion, id_planEstudio, id_proyectoCurricular, id_clasificacion, ";
               $cadena_sql.="id_estado, id_aprobado, id_cargado, encabezado_creditos, encabezado_nivel ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."encabezado ";
               $cadena_sql.="WHERE id_encabezado =".$variable;           

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
               $cadena_sql.="espacio_academico.id_estado, id_aprobado, id_cargado ";
               $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio_espacio ";
               $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ON ".$configuracion['prefijo'];
               $cadena_sql.="planEstudio_espacio.id_espacio=sga_espacio_academico.id_espacio ";
               $cadena_sql.="WHERE id_planEstudio=".$variable[0]." AND id_clasificacion=".$variable[1];
               $cadena_sql.=" AND id_nivel=".$variable[2];
               $cadena_sql.=" AND espacio_nroCreditos=".$variable[3];              

               break;

            case 'agruparEspacio':
               $cadena_sql="INSERT INTO ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="(id_planEstudio, id_proyectoCurricular, id_espacio, id_encabezado, id_estado ) ";
               $cadena_sql.="VALUES ('".$variable[0]."', '".$variable[1]."', '".$variable[2]."', '".$variable[3]."', 0)";
               break;

            case 'buscarEspacioGrupo':
               $cadena_sql="SELECT id_planEstudio, id_proyectoCurricular, id_espacio, id_encabezado FROM ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="WHERE id_planEstudio=".$variable[0]." AND ";
               $cadena_sql.="id_proyectoCurricular=".$variable[1]." AND ";
               $cadena_sql.="id_espacio=".$variable[2]." AND ";
               $cadena_sql.="id_encabezado=".$variable[3];

               break;

            case 'buscarEspacioAsociados':
               $cadena_sql="SELECT ".$configuracion['prefijo']."espacioEncabezado.id_espacio, espacio_nombre FROM ".$configuracion['prefijo']."espacioEncabezado ";
               $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico ON ".$configuracion['prefijo'];
               $cadena_sql.="espacio_academico.id_espacio = ".$configuracion['prefijo'];
               $cadena_sql.="espacioEncabezado.id_espacio";
               $cadena_sql.=" WHERE";
               $cadena_sql.=" id_encabezado=".$variable;
               $cadena_sql.=" AND ".$configuracion['prefijo']."espacioEncabezado.id_estado=1";

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

           case 'nombrePlanEstudio':
               $cadena_sql="select planEstudio_nombre " ;
               $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio ";
               $cadena_sql.="where id_planEstudio=".$variable;
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