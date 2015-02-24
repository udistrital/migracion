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

class sql_adminEspacioNoAprobado extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{
			case 'proyectos_curriculares':

                            $cadena_sql="select distinct cra_cod, cra_nombre, pen_nro ";
                            $cadena_sql.="from accra ";
                            $cadena_sql.="inner join acpen on accra.cra_cod=acpen.pen_cra_cod ";
                            $cadena_sql.="where cra_se_ofrece like '%S%' and pen_nro>200 ";
                            $cadena_sql.=" order by 3";

                        break;

			case 'espaciosNOaprobados':

                            $cadena_sql="SELECT PEE.id_espacio, espacio_nombre, id_nivel, PEE.id_cargado ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico EA on PEE.id_espacio=EA.id_espacio ";
                            $cadena_sql.="WHERE PEE.id_planEstudio=".$variable[0];
                            $cadena_sql.=" AND PEE.id_estado= 1";
                            $cadena_sql.=" AND PEE.id_aprobado= 0";
                            $cadena_sql.=" AND PEE.id_cargado= 0";
                            $cadena_sql.=" ORDER BY 3";

                        break;

			case 'plan_estudio':

                            $cadena_sql="SELECT distinct id_planEstudio ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio PE ORDER BY id_planEstudio ";
                            
                        break;
                    
			case 'espacios_proyectos':

                            $cadena_sql="SELECT PEE.id_espacio, espacio_nombre, id_nivel, PEE.id_cargado ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."planEstudio_espacio PEE ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico EA on PEE.id_espacio=EA.id_espacio ";
                            $cadena_sql.="WHERE PEE.id_planEstudio=".$variable[0];
                            $cadena_sql.=" AND PEE.id_estado= 1";
                            $cadena_sql.=" AND PEE.id_aprobado= 1";
                            $cadena_sql.=" ORDER BY 3,1";

                        break;

			case 'buscar_acasi':

                            $cadena_sql="SELECT * from acasi ";
                            $cadena_sql.="WHERE asi_cod =".$variable[0];

                        break;

			case 'buscar_acpen':

                            $cadena_sql="SELECT * from acpen ";
                            $cadena_sql.="WHERE pen_asi_cod =".$variable[0];
                            $cadena_sql.=" and pen_nro =".$variable[1];

                        break;
                        
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>