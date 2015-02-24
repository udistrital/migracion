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

class sql_registroAdicionarEspacioExistenteCoordinador extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{

                    case "listaPlanesEstudio":
                            $cadena_sql="SELECT ";
                            $cadena_sql.="id_planEstudio,";
                            $cadena_sql.="planEstudio_nombre,";
                            $cadena_sql.="planEstudio_ano,";
                            $cadena_sql.="planEstudio_periodo,";
                            $cadena_sql.="planEstudio_niveles,";
                            $cadena_sql.="planEstudio_fechaCreacion, ";
                            $cadena_sql.="id_proyectoCurricular ";
                            $cadena_sql.="FROM ".$configuracion["prefijo"];
                            $cadena_sql.="planEstudio ";
                            $cadena_sql.="WHERE id_estado=1";
                            $cadena_sql.=" AND id_planEstudio!=".$variable;
                            
                            break;

                    case "buscarEACodigo":
                       
                            $cadena_sql="SELECT DISTINCT PEE.id_planEstudio, planEstudio_nombre, EA.id_espacio, espacio_nombre, espacio_nroCreditos, espacio_horasAutonomo,";
                            $cadena_sql.=" espacio_horasCooperativo, espacio_horasDirecto, id_nivel, PEE.id_clasificacion, semanas";
                            $cadena_sql.=" FROM sga_planEstudio_espacio PEE";
                            $cadena_sql.=" INNER JOIN sga_espacio_academico EA ON PEE.id_espacio = EA.id_espacio";
                            $cadena_sql.=" INNER JOIN sga_planEstudio PE ON PEE.id_planEstudio = PE.id_planEstudio";
                            $cadena_sql.=" WHERE PEE.id_espacio =".$variable;
                            $cadena_sql.=" AND PEE.id_estado =1";
                            $cadena_sql.=" AND PEE.id_aprobado =1";
                            //echo $cadena_sql;exit;
                            break;

                    case "buscarEANombre":

                            $cadena_sql="SELECT DISTINCT PEE.id_planEstudio, planEstudio_nombre, EA.id_espacio, espacio_nombre, espacio_nroCreditos, espacio_horasAutonomo, ";
                            $cadena_sql.=" espacio_horasCooperativo, espacio_horasDirecto, id_nivel, PEE.id_clasificacion";
                            $cadena_sql.=" FROM sga_planEstudio_espacio PEE";
                            $cadena_sql.=" INNER JOIN sga_espacio_academico EA ON PEE.id_espacio = EA.id_espacio";
                            $cadena_sql.=" INNER JOIN sga_planEstudio PE ON PEE.id_planEstudio = PE.id_planEstudio";
                            $cadena_sql.=" WHERE espacio_nombre like '%".$variable."%'";
                            $cadena_sql.=" AND PEE.id_estado =1";
                            $cadena_sql.=" AND PEE.id_aprobado =1";
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
                            
                            $cadena_sql="SELECT DISTINCT id_planEstudio, PEE.id_espacio, id_nivel, clasificacion_abrev, PEE.id_estado, PEE.id_aprobado,";
                            $cadena_sql.=" espacio_nombre, espacio_nroCreditos, horasDirecto, horasCooperativo, espacio_horasAutonomo, PEE.id_clasificacion";
                            $cadena_sql.=" FROM ".$configuracion['prefijo']."planEstudio_espacio PEE";
                            $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_academico EA  ON PEE.id_espacio = EA.id_espacio";
                            $cadena_sql.=" INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC  ON PEE.id_clasificacion = EC.id_clasificacion";
                            $cadena_sql.=" WHERE id_planEstudio =".$variable;
                            $cadena_sql.=" AND PEE.id_aprobado = '1'";
                            $cadena_sql.=" AND PEE.id_estado='1'";
                            $cadena_sql.=" order by 3,2 ";
                            break;

			case 'clasificacion':

                            $cadena_sql="SELECT  id_clasificacion, clasificacion_nombre ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."espacio_clasificacion ";
//                            $cadena_sql.="where id_clasificacion!=5 ";
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
                            $cadena_sql.="semanas) ";
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

                        case "semestreActual":

                            $cadena_sql="select ape_ano, ape_per " ;
                            $cadena_sql.="from acasperi ";
                            $cadena_sql.="where ape_estado like '%A%'";
            //                echo $this->cadena_sql;
            //                exit;
                            break;

                        case 'registroEvento':

                            $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                            $cadena_sql.="VALUES('', '".$variable[0]."', ";
                            $cadena_sql.="'".$variable[1]."', ";
                            $cadena_sql.="'".$variable[2]."', ";
                            $cadena_sql.="'".$variable[3]."', ";
                            $cadena_sql.="'".$variable[4]."',";
                            $cadena_sql.="'".$variable[5]."')";
                            break;

                        case 'buscarEspacioARegistrar':

                            $cadena_sql="select id_nivel from ".$configuracion['prefijo']."planEstudio_espacio ";
                            $cadena_sql.="where ";
                            $cadena_sql.="id_planEstudio =".$variable[0]." ";
                            $cadena_sql.="and ";
                            $cadena_sql.="id_espacio = ".$variable[10]." ";
                            $cadena_sql.="and ";
                            $cadena_sql.="id_estado = 1";
                            break;


                        case 'espacioAprobado':

                            $cadena_sql="SELECT count(id_planEstudio) ";
                            $cadena_sql.="FROM sga_planEstudio_espacio ";
                            $cadena_sql.="WHERE id_espacio =".$variable;
                            $cadena_sql.=" AND id_aprobado=1";
                            break;

                    /*case 'nivelEspacioAprobado':

                        $cadena_sql="SELECT id_nivel ";
                        $cadena_sql.="FROM sga_planEstudio_espacio ";
                        $cadena_sql.="WHERE id_espacio =".$variable[0];
                        $cadena_sql.=" AND id_planEstudio =".$variable[1];
                        $cadena_sql.=" AND id_aprobado=1";

                        break;*/

		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>