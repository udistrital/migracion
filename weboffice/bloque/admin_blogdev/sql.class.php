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

class sql_adminBlogdev extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosUsuario":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="usr_id, ";
				$cadena_sql.="usr_nom ";
				$cadena_sql.="FROM ";
				$cadena_sql.="usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="usr_id =".$variable." ";
				break;
								
			case "identificacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."registrado ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="identificacion='".$variable."'";
				break;
				
			case "inscripcionBlogdev":
				$cadena_sql="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des ";				
				$cadena_sql.="FROM ";
				$cadena_sql.="bitacora";
				break;
				
			case "datosUsuarios":
				$cadena_sql="SELECT ";
				$cadena_sql.="clo_cod, ";
				$cadena_sql.="clo_nom ";				
				$cadena_sql.="FROM ";
				$cadena_sql.="claobj";
				break;
				
			case "insertarRegistro":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="aplicaciones ";
				$cadena_sql.="(";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="apl_nom, ";
				$cadena_sql.="apl_des ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$variable[5]."', ";
				$cadena_sql.="'".$variable[3]."', ";
				$cadena_sql.="'".$variable[4]."' ";
				$cadena_sql.=")";
				break;
				
			case "consultarRegistro":
				$cadena_sql="select rownum, ";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="apl_nom, ";
				$cadena_sql.="apl_des ";
				$cadena_sql.="from ";
				$cadena_sql.="(";
				$cadena_sql.="SELECT ";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="apl_nom, ";
				$cadena_sql.="apl_des ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aplicaciones ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="apl_cod DESC";
				$cadena_sql.=") ";
				$cadena_sql.="where ";
				$cadena_sql.="rownum < 2";
				break;
				
			case "consultarConsecutivo":
				$cadena_sql="SELECT ";
				$cadena_sql.="MAX (apl_cod)+1 ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aplicaciones";
				break;
				
			Case "insertadosAplic":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="(";				
				$cadena_sql.="SELECT ";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="apl_nom, ";
				$cadena_sql.="apl_des, ";
				$cadena_sql.="(ROW_NUMBER() OVER (ORDER BY apl_cod)) R ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aplicaciones ";
				$cadena_sql.=") ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="R ";
				$cadena_sql.="BETWEEN ";
				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
				$cadena_sql.=" AND ";
				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite superior				
				break;	
			
			case "totalAplic":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aplicaciones ";
				break;
				
			case "consultaAplicaciones":
				$cadena_sql="SELECT ";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="apl_nom, ";
				$cadena_sql.="apl_des ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aplicaciones ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="apl_cod=".$variable[1]."";
				break;
				
			case "editaraplicaciones":
				$cadena_sql="UPDATE ";
				$cadena_sql.="aplicaciones ";
				$cadena_sql.="SET ";
				$cadena_sql.="apl_nom='".$variable[1]."', ";
				$cadena_sql.="apl_des='".$variable[2]."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="apl_cod=".$variable[0]."";
				break;
				
			case "consultaAplicEditados":
				$cadena_sql="SELECT ";
				$cadena_sql.="apl_cod ";
				$cadena_sql.=", ";
				$cadena_sql.="apl_nom ";
				$cadena_sql.=", ";
				$cadena_sql.="apl_des ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aplicaciones ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="apl_cod=".$variable[1]." ";
				break;
		
			default:
				$cadena_sql="";
				break;
		}
		//echo $cadena_sql."<br>";
		return $cadena_sql;
	}
	
	
}
?>