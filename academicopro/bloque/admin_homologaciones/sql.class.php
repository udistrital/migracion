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

class sql_adminHomologaciones extends sql
{
	function cadena_sql($opcion,$variable="")
	{

		switch($opcion)
		{
			
                        case 'consultaProyectosCoordinador':
                            
                            $cadena_sql="SELECT cra_cod, ";
                            $cadena_sql.="cra_nombre  ";
                            $cadena_sql.="FROM accra  ";
                            $cadena_sql.="WHERE CRA_EMP_NRO_IDEN = ". $variable;
                            $cadena_sql.=" AND cra_estado = 'A'";
                            
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
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>