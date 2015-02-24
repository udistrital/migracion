<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sql.class.php");

class sql_registroPortafolioElectivasCoordinador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{
			
                     case 'proyectos_curriculares':

                            $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                            $cadena_sql.="from accra ";
                            $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                            $cadena_sql.="inner join geusucra on accra.cra_cod=geusucra.USUCRA_CRA_COD ";
                            $cadena_sql.="where pen_nro>200 ";
                            $cadena_sql.=" and USUCRA_NRO_IDEN=".$variable;
                            $cadena_sql.=" order by 3";
                        break;

                        case 'datos_coordinador':
                            $cadena_sql="select distinct usucra_cra_cod, cra_nombre, pen_nro ";
                            $cadena_sql.="from geusucra ";
                            $cadena_sql.="INNER JOIN accra ON geusucra.usucra_cra_cod=accra.cra_cod ";
                            $cadena_sql.="INNER JOIN ACPEN ON geusucra.usucra_cra_cod=acpen.pen_cra_cod ";
                            $cadena_sql.=" where usucra_nro_iden=".$variable;
//                            $cadena_sql.=" and cra_se_ofrece like '%S%'";
                            $cadena_sql.=" and pen_nro>200";
                        break;

			case 'buscar_registrados':
                            
                            $cadena_sql="SELECT DISTINCT id_planEstudio , PEE.id_espacio , id_nivel , clasificacion_abrev , PEE.id_estado , PEE.id_aprobado , ";
                            $cadena_sql.="espacio_nombre, espacio_nroCreditos, espacio_horasDirecto, espacio_horasCooperativo, espacio_horasAutonomo ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico EA  ON PEE.id_espacio = EA.id_espacio ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC  ON PEE.id_clasificacion = EC.id_clasificacion ";
                            $cadena_sql.="WHERE id_planEstudio =".$variable;
                            $cadena_sql.=" order by 3 ";
                        break;

			case 'clasificacion':

                            $cadena_sql="SELECT  id_clasificacion, clasificacion_nombre ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_clasificacion ";
                            $cadena_sql.="where id_clasificacion!=5 ";
                        break;

			case 'rango_codigos':

                            $cadena_sql="SELECT valor_inicial, valor_final ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."codigos_planEstudioEspacios ";
                            $cadena_sql.="WHERE id_planEstudio=".$variable;
                        break;
                    
			case 'codigos_no_asignadosMysql':

                            $cadena_sql="SELECT * ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio_espacio ";
                            $cadena_sql.="WHERE id_espacio=".$variable;
                        break;

			case 'codigos_no_asignadosOracle':

                            $cadena_sql="SELECT * ";
                            $cadena_sql.="FROM acasi ";
                            $cadena_sql.="WHERE asi_cod=".$variable;
                        break;

			case 'registro_espacioAcademico':

                            $cadena_sql="INSERT INTO ".$configuracion['prefijo']."espacio_academico (";
                            $cadena_sql.="id_espacio ,";
                            $cadena_sql.="espacio_nombre  ,";
                            $cadena_sql.="espacio_nroCreditos ,";
                            $cadena_sql.="espacio_horasDirecto ,";
                            $cadena_sql.="espacio_horasCooperativo ,";
                            $cadena_sql.="espacio_horasAutonomo ,";
                            $cadena_sql.="espacio_fechaCreacion ,";
                            $cadena_sql.="aprobado ,";
                            $cadena_sql.="id_estado  ,";
                            $cadena_sql.="id_cargado  ) ";
                            $cadena_sql.="VALUES ( ";
                            $cadena_sql.="'".$variable[10]."',";
                            $cadena_sql.="'".$variable[4]."',";
                            $cadena_sql.="'".$variable[5]."',";
                            $cadena_sql.="'".$variable[7]."',";
                            $cadena_sql.="'".$variable[8]."',";
                            $cadena_sql.="'".$variable[9]."',";
                            $cadena_sql.="'".date('Y/m/d')."',";
                            $cadena_sql.="'0',";
                            $cadena_sql.="'1',";
                            $cadena_sql.="'0')";
                        break;

			case 'registro_planEstudio':

                            $cadena_sql="INSERT INTO ".$configuracion['prefijo']."planEstudio_espacio (";
                            $cadena_sql.="id_planEstudio ,";
                            $cadena_sql.="id_espacio ,";
                            $cadena_sql.="id_nivel ,";
                            $cadena_sql.="id_clasificacion ,";
                            $cadena_sql.="id_estado ,";
                            $cadena_sql.="id_aprobado ,";
                            $cadena_sql.="id_cargado ,";
                            $cadena_sql.="horasDirecto ,";
                            $cadena_sql.="horasCooperativo, ";
                            $cadena_sql.="semanas ) ";
                            $cadena_sql.="VALUES ( ";
                            $cadena_sql.="'".$variable[0]."',";
                            $cadena_sql.="'".$variable[10]."',";
                            $cadena_sql.="'".$variable[6]."',";
                            $cadena_sql.="'".$variable[3]."',";
                            $cadena_sql.="'1',";
                            $cadena_sql.="'0',";
                            $cadena_sql.="'0',";
                            $cadena_sql.="'".$variable[7]."',";
                            $cadena_sql.="'".$variable[8]."',";
                            $cadena_sql.="'".$variable[11]."')";
                        break;

			case 'borrar_registroEspacio':

                            $cadena_sql="delete from ".$configuracion['prefijo']."espacio_academico ";
                            $cadena_sql.="WHERE id_espacio=".$variable[10];
                            $cadena_sql.=" AND espacio_nombre=".$variable[4];
                        break;

			case 'buscar_facultad':

                            $cadena_sql="SELECT cra_dep_cod ";
                            $cadena_sql.="FROM accra ";
                            $cadena_sql.="WHERE cra_cod=".$variable[1];
                        break;

                        case "ingresarComentario":
                            
                         $cadena_sql="INSERT INTO ".$configuracion["prefijo"]."comentario_general_planEstudio " ;
                         $cadena_sql.="(comentario_idPlanEstudio, ";
                         $cadena_sql.="comentario_idProyectoCurricular, ";
                         $cadena_sql.="comentario_usuario, ";
                         $cadena_sql.="comentario_fecha, ";
                         $cadena_sql.="comentario_leidoAsesorVice, ";
                         $cadena_sql.="comentario_leidoCoordinador, ";
                         $cadena_sql.="comentario_descripcion) ";
                         $cadena_sql.="VALUES (";
                         $cadena_sql.="'".$variable[0]."', ";
                         $cadena_sql.="'".$variable[1]."', ";
                         $cadena_sql.="'".$variable[2]."', ";
                         $cadena_sql.="'".$variable[3]."', ";
                         $cadena_sql.="'0', ";
                         $cadena_sql.="'1', ";
                         $cadena_sql.="'Solicitud de aprobación del Espacio Académico ".$variable[4]."-".$variable[5]."')";
                        break;

                        case "bimestreActual":
                            
                         $cadena_sql="select ape_ano, ape_per " ;
                         $cadena_sql.="from acasperi ";
                         $cadena_sql.="where ape_estado like '%A%'";
                        break;

                        case 'registroLogEvento':

                         $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                         $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                         $cadena_sql.="'".$variable[1]."', ";
                         $cadena_sql.="'11', ";
                         $cadena_sql.="'Creo Espacio Académico', ";
                         $cadena_sql.="'".$variable[2]."-".$variable[3].", ";
                         $cadena_sql.=$variable[4].", ".$variable[5].", ".$variable[6]."',";
                         $cadena_sql.="'".$variable[5]."')";
                        break;

			case 'registro_espacioAcademicoExtrinseco':

                            $cadena_sql="INSERT INTO ".$configuracion['prefijo']."encabezado (";
                            $cadena_sql.="encabezado_nombre ,";
                            $cadena_sql.="id_planEstudio ,";
                            $cadena_sql.="id_proyectoCurricular ,";
                            $cadena_sql.="encabezado_descripcion ,";
                            $cadena_sql.="id_clasificacion ,";
                            $cadena_sql.="encabezado_creditos ,";
                            $cadena_sql.="encabezado_nivel)";
                            $cadena_sql.="VALUES ( ";
                            $cadena_sql.="'".$variable[4]."',";
                            $cadena_sql.="'".$variable[0]."',";
                            $cadena_sql.="'".$variable[1]."',";
                            $cadena_sql.="'".$variable[2]."',";
                            $cadena_sql.="'".$variable[3]."',";
                            $cadena_sql.="'".$variable[5]."',";
                            $cadena_sql.="'".$variable[6]."')";
                        break;

                        case "consultaEspacioPlan":
                            $cadena_sql="SELECT ESPACIO.id_espacio, ";
                            $cadena_sql.="ESPACIO.espacio_nombre, ";
                            $cadena_sql.="PLAN_ESPACIO.id_nivel, ";
                            $cadena_sql.="espacio_nroCreditos, ";
                            $cadena_sql.="horasDirecto, ";        //tabla planEstudioEspacio
                            $cadena_sql.="horasCooperativo, ";    //tabla planEstudioEspacio
                            $cadena_sql.="espacio_horasAutonomo, ";
                            $cadena_sql.="CLASIFICACION.clasificacion_nombre, ";
                            $cadena_sql.="CLASIFICACION.id_clasificacion, ";
                            $cadena_sql.="REL_ELECTIVO.id_nombreElectivo, ";  //registro[9]
                            $cadena_sql.="ELECTIVO.nombreElectivo, ";
                            $cadena_sql.="PLAN_ESPACIO.id_aprobado, ";        //registro[11]
                            $cadena_sql.="PLAN_ESPACIO.id_planEstudio, ";      //registro[12]
                            $cadena_sql.="PLAN_ESPACIO.semanas ";      //registro[13]
                            $cadena_sql.="FROM sga_espacio_academico AS ESPACIO ";
                            $cadena_sql.="INNER JOIN sga_planEstudio_espacio AS PLAN_ESPACIO ";
                            $cadena_sql.="ON PLAN_ESPACIO.id_espacio = ESPACIO.id_espacio ";
                            $cadena_sql.="INNER JOIN sga_espacio_clasificacion AS CLASIFICACION ";
                            $cadena_sql.="ON CLASIFICACION.id_clasificacion = PLAN_ESPACIO.id_clasificacion ";
                            $cadena_sql.="LEFT OUTER JOIN sga_espacioNombreElectivo AS REL_ELECTIVO ";
                            $cadena_sql.="ON PLAN_ESPACIO.id_espacio = REL_ELECTIVO.id_espacio ";
                            $cadena_sql.="LEFT OUTER JOIN sga_nombreElectivo AS ELECTIVO ";
                            $cadena_sql.="ON REL_ELECTIVO.id_nombreElectivo = ELECTIVO.id_nombreElectivo ";
                            $cadena_sql.="WHERE PLAN_ESPACIO.id_planEstudio=".$variable." ";
                            $cadena_sql.=" AND PLAN_ESPACIO.id_estado=1 ";
                            $cadena_sql.=" AND PLAN_ESPACIO.id_clasificacion=4 ";
                            //$cadena_sql.="AND PLAN_ESPACIO.id_espacio not in (SELECT DISTINCT id_espacio FROM sga_espacioEncabezado WHERE id_planEstudio =".$variable." and id_estado=1 ) ";
                            $cadena_sql.="ORDER BY PLAN_ESPACIO.id_nivel,ESPACIO.id_espacio, REL_ELECTIVO.id_nombreElectivo, ESPACIO.espacio_nombre ASC";
            //                echo $cadena_sql;
            //                exit;
                        break;

                        case 'actualizar_espacioAcademico':

                            $cadena_sql="UPDATE ".$configuracion['prefijo']."espacio_academico ";
                            $cadena_sql.="SET espacio_nombre='".$variable[1]."',";
                            $cadena_sql.="espacio_nroCreditos='".$variable[2]."',";
                            $cadena_sql.="espacio_horasDirecto='".$variable[3]."' ,";
                            $cadena_sql.="espacio_horasCooperativo='".$variable[4]."' ,";
                            $cadena_sql.="espacio_horasAutonomo='".$variable[5]."' ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.=" id_espacio='".$variable[0]."'";
                        break;

			case 'actualizar_planEstudio':

                            $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio ";
                            $cadena_sql.="SET horasDirecto='".$variable[3]."' ,";
                            $cadena_sql.="horasCooperativo='".$variable[4]."' ,";
                            $cadena_sql.="semanas='".$variable[10]."' ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="id_planEstudio='".$variable[8]."' and";
                            $cadena_sql.=" id_espacio='".$variable[0]."'";
                        break;

                        case 'borrar_espacioAcademico':

                            $cadena_sql="DELETE from ".$configuracion['prefijo']."espacio_academico ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.=" id_espacio='".$variable[0]."'";
                        break;

			case 'borrar_planEstudio':

                            $cadena_sql="DELETE FROM ".$configuracion['prefijo']."planEstudio_espacio ";
                            $cadena_sql.="WHERE ";
                            $cadena_sql.="id_planEstudio='".$variable[8]."' and";
                            $cadena_sql.=" id_espacio='".$variable[0]."'";
                        break;

                        case "comentariosNoLeidos":
                            
                            $cadena_sql="SELECT count(*) FROM ".$configuracion["prefijo"]."comentario_espacio_planEstudio ";
                            $cadena_sql.="WHERE comentario_idEspacio=".$variable['COD_ESPACIO'];
                            $cadena_sql.=" AND comentario_idPlanEstudio=".$variable['PLAN_ESPACIO'];
                            $cadena_sql.=" AND comentario_leidoCoordinador=0";
                        break;



		}
		return $cadena_sql;
	}


}
?>