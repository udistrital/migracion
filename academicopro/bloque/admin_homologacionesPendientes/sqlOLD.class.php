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

class sql_adminHomologacionesPendientes extends sql
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
                        
                        case 'consultaCohortesProyecto':
                            $cadena_sql =" SELECT DISTINCT (CASE WHEN  LENGTH(EST_COD)=11 THEN SUBSTR(EST_COD,1,5) ";
                            $cadena_sql.=" ELSE SUBSTR(EST_COD,1,3) ";
                            $cadena_sql.=" END )    AS COHORTE ";
                            $cadena_sql.=" FROM ACEST ";
                            $cadena_sql.=" WHERE EST_CRA_COD=".$variable;
                            $cadena_sql.=" AND EST_ESTADO_EST IN ('A','B','V','J' )";
                            $cadena_sql.=" AND EST_COD IN (SELECT DISTINCT NOT_EST_COD FROM ACNOT WHERE NOT_EST_REG='A')";
                            $cadena_sql.=" ORDER BY COHORTE DESC";
                            
                        break;
                
                    case 'consultarEstudiantesCohorte':
                        $cadena_sql =" SELECT ";
                        $cadena_sql.=" EST.EST_COD           AS COD_ESTUDIANTE, ";
                        $cadena_sql.=" EST.EST_NOMBRE        AS NOMBRE ";
                        $cadena_sql.=" FROM ACEST EST ";
                        $cadena_sql.=" WHERE SUBSTR(EST.EST_COD,1,5)='".$variable['cohorte']."' ";
                        $cadena_sql.=" AND EST.EST_CRA_COD = ".$variable['cod_proyecto']." ";
                        $cadena_sql.=" AND EST.EST_ESTADO_EST IN ('A','B','V','J' )";
                        $cadena_sql.=" AND EST.EST_COD IN (SELECT DISTINCT NOT_EST_COD FROM ACNOT WHERE NOT_EST_REG='A')";
                        $cadena_sql.=" ORDER BY COD_ESTUDIANTE";

                    break;
                    
		}
		return $cadena_sql;
	}


}
?>