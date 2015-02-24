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

class sql_registroModificarEACoordinador extends sql
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

			case 'actualizar_espacioAcademico':

                            $cadena_sql="UPDATE ".$configuracion['prefijo']."espacio_academico SET ";
                            $cadena_sql.="espacio_nombre ='".$variable[4]."'";
                            $cadena_sql.=", espacio_nroCreditos='".$variable[5]."'";
                            $cadena_sql.=", espacio_horasDirecto='".$variable[7]."'";
                            $cadena_sql.=", espacio_horasCooperativo='".$variable[8]."'";
                            $cadena_sql.=", espacio_horasAutonomo='".$variable[9]."'";
                            $cadena_sql.=" WHERE id_espacio='".$variable[10]."'";
                            
                        break;

			case 'actualizar_planEstudio':

                            $cadena_sql="UPDATE ".$configuracion['prefijo']."planEstudio_espacio SET";
                            $cadena_sql.=" id_nivel='".$variable[6]."'";
                            $cadena_sql.=", id_clasificacion='".$variable[3]."'";
                            $cadena_sql.=", horasDirecto='".$variable[7]."'";
                            $cadena_sql.=", horasCooperativo='".$variable[8]."'";
                            $cadena_sql.=" WHERE id_planEstudio='".$variable[0]."'";
                            $cadena_sql.=" AND id_espacio='".$variable[10]."'";
                            

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

                        case "bimestreActual":

                            $cadena_sql="select ape_ano, ape_per " ;
                            $cadena_sql.="from acasperi ";
                            $cadena_sql.="where ape_estado like '%A%'";

                        break;

                        case 'registroModificarEA':

                         $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                         $cadena_sql.="VALUES(0, '".$variable[0]."', ";
                         $cadena_sql.="'".$variable[1]."', ";
                         $cadena_sql.="'19', ";
                         $cadena_sql.="'Modifica Espacio Académico Coordinador', ";
                         $cadena_sql.="'".$variable[2]."-".$variable[3].", ";
                         $cadena_sql.=$variable[4].", 0, 0, ".$variable[5].", ".$variable[6]."', ";
                         $cadena_sql.="'".$variable[5]."')";

                         break;

                      case 'actualizar_espacioAcademicoEncabezado':

                        $cadena_sql="UPDATE ".$configuracion['prefijo']."encabezado SET ";
                        $cadena_sql.="encabezado_nombre ='".$variable[1]."',";
                        $cadena_sql.=" encabezado_creditos='".$variable[2]."',";
                        $cadena_sql.=" encabezado_nivel ='".$variable[3]."',";
                        $cadena_sql.=" id_clasificacion ='".$variable[6]."'";
                        $cadena_sql.=" WHERE id_encabezado='".$variable[0]."'";
                        $cadena_sql.=" AND id_planEstudio='".$variable[4]."'";
                        $cadena_sql.=" AND id_proyectoCurricular='".$variable[5]."'";

                        break;


		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>