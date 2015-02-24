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

class sql_adminHomologacionesPendientesPorProyecto extends sql
{
	function cadena_sql($opcion,$variable="")
	{

		switch($opcion)
		{
			
                        case 'consultaProyectosCoordinador':
                            
                            $cadena_sql="SELECT cra_cod, ";
                            $cadena_sql.="cra_nombre  ";
                            $cadena_sql.="FROM accra  ";
                            $cadena_sql.="WHERE CRA_EMP_NRO_IDEN = ". $variable['identificacion'];
                            $cadena_sql.=" AND cra_estado = 'A'";
                            if($variable['cod_proyecto'])
                                $cadena_sql.=" AND cra_cod = ". $variable['cod_proyecto'];
                            
                            
                        break;

                        case 'consultaEspacioAcademico':
                            $cadena_sql ="SELECT DISTINCT ASI_NOMBRE NOM_ASIGNATURA, ";
                            $cadena_sql.="(CASE WHEN PEN_CRE IS NOT NULL THEN PEN_CRE    ELSE 0 END)   AS CREDITOS  ";
                            $cadena_sql.="FROM ACPEN, ACASI  ";
                            $cadena_sql.="WHERE PEN_ESTADO='A' ";
                            $cadena_sql.=" AND PEN_ASI_COD = ASI_COD";
                            $cadena_sql.=" AND PEN_ASI_COD= ".$variable['cod_espacio'];
                            if ($variable['cod_proyecto'])
                                $cadena_sql.=" AND PEN_CRA_COD= ".$variable['cod_proyecto'];
                            
                        break;
                        
                        case 'consultaProyectos':
                            $cadena_sql="SELECT cra_cod AS CRA_COD, ";
                            $cadena_sql.="(cra_cod||' - '||cra_nombre) AS NOMBRE  ";
                            $cadena_sql.="FROM accra  ";
                            $cadena_sql.="INNER JOIN ACTIPCRA ON CRA_TIP_CRA=TRA_COD  ";
                            $cadena_sql.="WHERE cra_estado = 'A' ";
                            $cadena_sql.="AND TRA_NIVEL='PREGRADO' ";
                            $cadena_sql.=" ORDER BY cra_cod ";
                            
                        break;
                
                    case 'consultarEstudiantesProyecto':
                        $cadena_sql =" SELECT ";
                        $cadena_sql.=" EST.EST_COD           AS COD_ESTUDIANTE, ";
                        $cadena_sql.=" EST.EST_NOMBRE        AS NOMBRE ";
                        $cadena_sql.=" FROM ACEST EST ";
                        $cadena_sql.=" WHERE EST.EST_CRA_COD = ".$variable." ";
                        $cadena_sql.=" AND EST.EST_ESTADO_EST IN ('A','B','V','J' )";
                        $cadena_sql.=" AND EST.EST_COD IN (SELECT DISTINCT NOT_EST_COD FROM ACNOT WHERE NOT_EST_REG='A')";
                        $cadena_sql.=" ORDER BY COD_ESTUDIANTE";

                    break;
                    
		}
		return $cadena_sql;
	}


}
?>