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

class sql_registroCrearEACoordinador extends sql
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
                            //$cadena_sql.="where id_clasificacion!=5 ";

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
                            $cadena_sql.="horasCooperativo , ";
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
                         $cadena_sql.="VALUES";
                         $cadena_sql.="(0, '".$variable['usuario']."', ";
                         $cadena_sql.="'".date('YmdHis')."', ";
                         $cadena_sql.="'".$variable['evento']."', ";
                         $cadena_sql.="'".$variable['descripcion']."', ";
                         $cadena_sql.="'".$variable['registro']."', ";
                         $cadena_sql.="'".$variable['afectado']."')";

                         break;

			case 'registro_espacioAcademicoExtrinseco':

                            $cadena_sql="INSERT INTO ".$configuracion['prefijo']."encabezado (";
                            $cadena_sql.="encabezado_nombre ,";
                            $cadena_sql.="id_planEstudio ,";
                            $cadena_sql.="id_proyectoCurricular ,";
                            $cadena_sql.="encabezado_descripcion ,";
                            $cadena_sql.="id_clasificacion ,";
                            $cadena_sql.="encabezado_creditos ,";
                            $cadena_sql.="encabezado_nivel,";
                            $cadena_sql.="id_estado)";
                            $cadena_sql.="VALUES ( ";
                            $cadena_sql.="'".$variable[4]."',";
                            $cadena_sql.="'".$variable[0]."',";
                            $cadena_sql.="'".$variable[1]."',";
                            $cadena_sql.="'".$variable[2]."',";
                            $cadena_sql.="'".$variable[3]."',";
                            $cadena_sql.="'".$variable[5]."',";
                            $cadena_sql.="'".$variable[6]."',";
                            $cadena_sql.="'1')";

                        break;
                    
			case 'nivel_maximoPlan':

                            $cadena_sql="SELECT DISTINCT MAX( `id_nivel` )";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."planEstudio_espacio ";
                            $cadena_sql.="WHERE id_planEstudio='".$variable."'";
                            $cadena_sql.=" ORDER BY `id_nivel` ASC ";

                        break;


		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>