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

class sql_adminRecalcularEstadoEstudiante extends sql
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

                        default:
                            $cadena_sql="";
                        break;
                           
		}
		return $cadena_sql;
	}


}
?>