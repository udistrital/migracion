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

class sql_registroMultipleClasificacionEspacioCoordinador extends sql
{
	function cadena_sql($configuracion,$opcion,$variable="")
	{

		switch($opcion)
		{
                    
                    
                    case 'espaciosPlanEstudio':
                        $cadena_sql = " SELECT DISTINCT id_planEstudio CODIGO_PLAN, ";
                        $cadena_sql .= " PEE.id_espacio CODIGO, ";
                        $cadena_sql .= " espacio_nombre NOMBRE, ";
                        $cadena_sql .= " id_nivel NIVEL, ";
                        $cadena_sql .= " PEE.id_clasificacion CLASIFICACION, ";
                        $cadena_sql .= " clasificacion_abrev ABREVIATURA, ";
                        $cadena_sql .= " espacio_nroCreditos CREDITOS, ";
                        $cadena_sql .= " PEE.id_estado ESTADO, ";
                        $cadena_sql .= " PEE.id_aprobado APROBADO, ";
                        $cadena_sql .= " PEE.id_cargado CARGADO, ";
                        $cadena_sql .= " horasDirecto HTD, ";
                        $cadena_sql .= " horasCooperativo HTC, ";
                        $cadena_sql .= " espacio_horasAutonomo HTA, ";
                        $cadena_sql .= " semanas SEMANAS, ";
                        $cadena_sql .= " PEE.ofrecido_portafolio PORTAFOLIO";
                        $cadena_sql .= " FROM ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                        $cadena_sql .= " INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON PEE.id_espacio = EA.id_espacio ";
                        $cadena_sql .= " INNER JOIN ".$configuracion['prefijo']."espacio_clasificacion EC ON PEE.id_clasificacion = EC.id_clasificacion ";
                        $cadena_sql .= " WHERE  id_planEstudio = '".$variable['planEstudio']."' ";
                        $cadena_sql .= " AND PEE.id_estado = '1' ";
                        $cadena_sql .= " AND PEE.id_aprobado = '1' ";
                        $cadena_sql .= " AND PEE.id_clasificacion != '4' ";
                        $cadena_sql .= " ORDER  BY id_nivel, ";
                        $cadena_sql .= " PEE.id_espacio ASC ";
                        break;

                      case "buscarPeriodoActivo":
                         $cadena_sql=" select ape_ano ANO," ;
                         $cadena_sql.=" ape_per PERIODO" ;
                         $cadena_sql.=" from acasperi ";
                         $cadena_sql.=" where ape_estado like '%A%'";
                         break;
                     
                    case 'activarEspacioPortafolio':
                        $cadena_sql=" UPDATE sga_planEstudio_espacio SET ofrecido_portafolio='1' ";
                        $cadena_sql.=" WHERE id_planEstudio=".$variable['planEstudio'];
                        $cadena_sql.=" AND id_espacio=".$variable['codEspacio'];
                        break;                     

                    case 'actualizarEspacioPortafolio':
                        $cadena_sql=" UPDATE sga_planEstudio_espacio SET ofrecido_portafolio=".$variable['ofrecido_portafolio'];
                        $cadena_sql.=" WHERE id_planEstudio=".$variable['planEstudio'];
                        $cadena_sql.=" AND id_espacio=".$variable['codEspacio'];
                        break;                     
                     
                    case 'registroLogEvento':

                         $cadena_sql="insert into ".$configuracion['prefijo']."log_eventos ";
                         $cadena_sql.="VALUES(0,";
                         $cadena_sql.="'".$variable['usuario']."', ";
                         $cadena_sql.="'".$variable['fecha']."', ";
                         $cadena_sql.="'".$variable['log_evento']."', ";
                         $cadena_sql.="'".$variable['descripcion']."', ";
                         $cadena_sql.="'".$variable['log_registro']."', ";
                         $cadena_sql.="'0') ";//usuario afectado
                         break;                    
                    
//******************************************************************************************                    

                     
                    case 'actualizarClasificacion':
                        $cadena_sql ="UPDATE ".$configuracion['prefijo']."planEstudio_espacio";
                        $cadena_sql .=" SET ofrecido_portafolio='1'";
                        $cadena_sql .=" WHERE id_planEstudio='".$variable[0]."'";
                        $cadena_sql .=" AND id_espacio='".$variable[1]."'";
                        break;                     
                    
                    case 'planEstudioOracle':
                        $cadena_sql = "SELECT DISTINCT pen_nro, ";
                        $cadena_sql .= "                pen_asi_cod, ";
                        $cadena_sql .= "                asi_nombre, ";
                        $cadena_sql .= "                pen_sem, ";
                        $cadena_sql .= "                clp_cea_cod, ";
                        $cadena_sql .= "                cea_abr, ";
                        $cadena_sql .= "                pen_cre, ";
                        $cadena_sql .= "                pen_nro_ht, ";
                        $cadena_sql .= "                pen_nro_hp, ";
                        $cadena_sql .= "                pen_nro_aut ";
                        $cadena_sql .= "FROM   acpen ";
                        $cadena_sql .= "       inner join acclasificacpen ";
                        $cadena_sql .= "         ON clp_asi_cod = pen_asi_cod ";
                        $cadena_sql .= "            AND clp_pen_nro = pen_nro ";
                        $cadena_sql .= "       inner join geclasificaespac ";
                        $cadena_sql .= "         ON cea_cod = clp_cea_cod ";
                        $cadena_sql .= "       inner join acasi ";
                        $cadena_sql .= "         ON asi_cod = pen_asi_cod ";
                        $cadena_sql .= "WHERE  pen_nro = '".$variable."' ";
                        $cadena_sql .= "       AND clp_cea_cod != 4 ";
                        $cadena_sql .= "       AND clp_estado = 'A' ";
                        $cadena_sql .= "ORDER  BY pen_sem, ";
                        $cadena_sql .= "          pen_asi_cod " ;

                        break;





                    case 'buscarElectivo':
                        $cadena_sql=" select *";
                        $cadena_sql.=" from acclasificacpen ";
                        $cadena_sql.=" where clp_pen_nro=".$variable[0];
                        $cadena_sql.=" and clp_asi_cod=".$variable[1];
                        $cadena_sql.=" and CLP_CEA_COD='4'";
                        break;
                    
                    case 'actualizarElectivoOracle':
                        $cadena_sql=" UPDATE acclasificacpen SET clp_estado='A' ";
                        $cadena_sql.=" WHERE CLP_PEN_NRO=".$variable[0];
                        $cadena_sql.=" AND CLP_ASI_COD=".$variable[1];
                        $cadena_sql.=" AND CLP_CEA_COD='4'";
                        break;
                    
                    case 'actualizarElectivoMySQL':
                        $cadena_sql=" UPDATE sga_planEstudio_espacio SET ofrecido_portafolio='1' ";
                        $cadena_sql.=" WHERE id_planEstudio=".$variable[0];
                        $cadena_sql.=" AND id_espacio=".$variable[1];
                        break;
                    
                    case 'crearElectivo':
                        $cadena_sql=" INSERT INTO acclasificacpen VALUES";
                        $cadena_sql.=" ('".$variable[2]."',";
                        $cadena_sql.=" '".$variable[1]."',";
                        $cadena_sql.=" '".$variable[0]."',";
                        $cadena_sql.=" '4',";
                        $cadena_sql.=" 'A')";
                        break;

                    case 'actualizarPortafolio':
                        $cadena_sql=" UPDATE sga_planEstudio_espacio SET pec_id_estado='1' ";
                        $cadena_sql.=" WHERE pec_id_planEstudio=".$variable[0];
                        $cadena_sql.=" AND pec_id_espacio=".$variable[1];
                        $cadena_sql.=" AND pec_id_clasificacion='4'";
                        break;




		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>