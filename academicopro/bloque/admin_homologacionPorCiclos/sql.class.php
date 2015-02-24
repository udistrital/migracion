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

class sql_adminHomologacionPorCiclos extends sql
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
                            
                            $cadena_sql="SELECT DISTINCT ASI_NOMBRE NOM_ASIGNATURA, ";
                            $cadena_sql.="(CASE WHEN PEN_CRE IS NOT NULL THEN PEN_CRE    ELSE 0 END)   AS CREDITOS  ";
                            $cadena_sql.="FROM ACPEN, ACASI  ";
                            $cadena_sql.="WHERE PEN_ESTADO='A' ";
                            $cadena_sql.=" AND PEN_ASI_COD = ASI_COD";
                            $cadena_sql.=" AND PEN_ASI_COD= ".$variable['cod_espacio'];
                            if ($variable['cod_proyecto'])
                                $cadena_sql.=" AND PEN_CRA_COD= ".$variable['cod_proyecto'];
                            
                        break;
                        
                        case 'consultaCohortesProyecto':
                            $cadena_sql="SELECT DISTINCT SUBSTR(EST_COD,1,5) COHORTE ";
                            $cadena_sql.="FROM ACEST ";
                            $cadena_sql.="WHERE EST_CRA_COD=".$variable;
                            $cadena_sql.=" ORDER BY COHORTE DESC";
                            
                        break;
                
                    case 'consultarEstudiantesCohorte':
                        $cadena_sql="SELECT ";
                        $cadena_sql.="EST.EST_COD AS COD_ESTUDIANTE, ";
                        $cadena_sql.="EST.EST_NOMBRE AS NOMBRE, ";
                        $cadena_sql.="ANTIGUOS.COD_EST_ANTERIOR AS COD_ESTUDIANTE_ANTERIOR ";
                        $cadena_sql.=" FROM ACEST EST ";
                        $cadena_sql.=" LEFT OUTER JOIN (SELECT EST2.EST_NRO_IDEN AS IDEN,  ";
                        $cadena_sql.=" EST2.EST_COD AS COD_EST_ANTERIOR ";
                        $cadena_sql.=" FROM ACEST EST2 ";
                        $cadena_sql.=" INNER JOIN ACESTADO ON EST2.EST_ESTADO_EST =  ESTADO_COD ";
                        $cadena_sql.=" WHERE ESTADO_ACTIVO='N' ";
                        $cadena_sql.=" AND EST2.EST_CRA_COD = ".$variable['cod_proyectoAnt']." ";
                        $cadena_sql.=") ANTIGUOS ON EST.EST_NRO_IDEN = ANTIGUOS.IDEN ";
                        $cadena_sql.="WHERE SUBSTR(EST.EST_COD,1,5)='".$variable['cohorte']."' ";
                        $cadena_sql.="AND EST.EST_CRA_COD = ".$variable['cod_proyecto']." ";
                        $cadena_sql.="AND EST.EST_ESTADO_EST IN ('A','B','V','J' )";
                        $cadena_sql.=" ORDER BY COD_ESTUDIANTE";

                    break;
                    
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>