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

class sql_registroRequisitos extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{

		switch($opcion)
		{
			case 'plan_estudio':

                            $cadena_sql="SELECT DISTINCT PEN_NRO FROM GEUSUCRA";
                            $cadena_sql.=" INNER JOIN ACPEN ON GEUSUCRA.USUCRA_CRA_COD=ACPEN.PEN_CRA_COD";
                            $cadena_sql.=" WHERE USUCRA_NRO_IDEN=".$variable;
                            $cadena_sql.=" AND PEN_ESTADO='A' AND PEN_NRO>200";

                        break;

                        case 'espacios_academicos':

                            $cadena_sql="SELECT DISTINCT EA.id_espacio, espacio_nombre,id_nivel FROM ".$configuracion['prefijo']."espacio_academico EA ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."planEstudio_espacio PE ON EA.id_espacio=PE.id_espacio ";
                            $cadena_sql.="WHERE PE.id_planEstudio=".$variable;
                            $cadena_sql.=" AND PE.id_estado = '1'";
                            $cadena_sql.=" ORDER BY PE.id_nivel,EA.id_espacio";

                        break;

                        case 'insertar_registro':

                            $cadena_sql="INSERT INTO ".$configuracion['prefijo']."requisitos_espacio_plan_estudio ";
                            $cadena_sql.="VALUES('".$variable[0]."','".$variable[1]."','".$variable[2]."','".$variable[3]."')";

                        break;

                        case 'actualizar_registro':

                            $cadena_sql="UPDATE ".$configuracion['prefijo']."requisitos_espacio_plan_estudio SET requisitos_previoAprobado=".$variable[3];
                            $cadena_sql.=" WHERE requisitos_idPlanEstudio=".$variable[0]." AND requisitos_idEspacioPrevio=".$variable[1]." AND requisitos_idEspacioPosterior=".$variable[2];

                        break;

                        case 'requisitos_registrados':

                            $cadena_sql="SELECT requisitos_idEspacioPrevio,EA.espacio_nombre, requisitos_idEspacioPosterior, EAS.espacio_nombre,requisitos_previoAprobado ";
                            $cadena_sql.="FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio RE ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico EA ON  EA.id_espacio=RE.requisitos_idEspacioPrevio ";
                            $cadena_sql.="INNER JOIN ".$configuracion['prefijo']."espacio_academico EAS ON  EAS.id_espacio=RE.requisitos_idEspacioPosterior ";
                            $cadena_sql.="WHERE requisitos_idPlanEstudio=".$variable;

                        break;

                        case 'eliminar_registro':

                            $cadena_sql="DELETE FROM ".$configuracion['prefijo']."requisitos_espacio_plan_estudio";
                            $cadena_sql.=" WHERE requisitos_idPlanEstudio = ".$variable[0]."";
                            $cadena_sql.=" AND requisitos_idEspacioPrevio = ".$variable[1]."";
                            $cadena_sql.=" AND requisitos_idEspacioPosterior = ".$variable[2]."";
                            $cadena_sql.=" AND requisitos_previoAprobado = ".$variable[3]." LIMIT 1";

                        break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}


}
?>