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

class sql_registroBlogdev extends sql
{
	function cadena_sql($configuracion,$conexion, $opcion,$variable="")
	{
		$variable=$conexion->verificar_variables($variable);		
		
		switch($opcion)
		{
			case "datosAplicaciones":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="apl_nom, ";
				$cadena_sql.="apl_des ";
				$cadena_sql.="FROM ";
				$cadena_sql.="aplicaciones";
				break;
			
			case "identificacion":
				$cadena_sql="SELECT ";
				$cadena_sql.="usr_id, ";
				$cadena_sql.="usr_nom ";
				$cadena_sql.="FROM ";
				$cadena_sql.="usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="usr_id='".$variable."'";
				break;	
				
			case "datosUsuario":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="usr_id, ";
				$cadena_sql.="usr_nom ";
				$cadena_sql.="FROM ";
				$cadena_sql.="usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="usr_id=".$variable."";
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
				$cadena_sql.="bitacora ";
				$cadena_sql.="(";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des, ";
				$cadena_sql.="bit_tio_cod, ";
				$cadena_sql.="bit_clo_cod, ";
				$cadena_sql.="bit_apl_cod ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="seq_bit.nextval, ";
				$cadena_sql.="'".$variable[3]."', ";
				$cadena_sql.="'".$variable[0]."', ";
				$cadena_sql.="SYSDATE, ";
				$cadena_sql.="'".$variable[4]."', ";
				$cadena_sql.="'".$variable[5]."', ";
				$cadena_sql.="'".$variable[12]."', ";
				$cadena_sql.="'".$variable[11]."', ";
				$cadena_sql.="'".$variable[10]."' ";
				$cadena_sql.=")";
				break;
			case "insertarObjrelacionados":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.="objrel ";
				$cadena_sql.="(";
				$cadena_sql.="ore_bit_con, ";
				$cadena_sql.="ore_obj_cod, ";
				$cadena_sql.="ore_tre_cod, ";
				$cadena_sql.="ore_des ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.=$variable[0].",";
				$cadena_sql.=$variable[1].",";
				$cadena_sql.=$variable[2].",";
				$cadena_sql.="'".$variable[3]."' ";
				$cadena_sql.=")";
				break;
			case "consultarConsecutivo":
				$cadena_sql="SELECT ";
				$cadena_sql.="MAX (bit_consec)+1 ";
				$cadena_sql.="FROM ";
				$cadena_sql.="bitacora";
				break;
				
			case "consultarRegistro":
				$cadena_sql="select rownum, ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des ";
				$cadena_sql.="from ";
				$cadena_sql.="(";
				$cadena_sql.="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des ";
				$cadena_sql.="FROM ";
				$cadena_sql.="bitacora, ";
				$cadena_sql.="objetos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="bit_obj_cod=obj_cod ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="bit_consec DESC";
				$cadena_sql.=") ";
				$cadena_sql.="where ";
				$cadena_sql.="rownum < 2";
				break;
					
			case "bloqueadoBitacora":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="(";				
				$cadena_sql.="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des, ";
				$cadena_sql.="usr_nom, ";
				$cadena_sql.="(ROW_NUMBER() OVER (ORDER BY bit_consec)) R ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora, ";
				$cadena_sql.="usuario ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="usr_id=bit_usr_cod ";
				$cadena_sql.=" AND ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.=") ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="R ";
				$cadena_sql.="BETWEEN ";
				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
				$cadena_sql.=" AND ";
				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite superior				
				break;
					
			case "totalBitacora":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="obj_cod=bit_obj_cod";
				break;
				
			case "consutaTiprel":
				$cadena_sql="SELECT ";
				$cadena_sql.="ore_bit_con, ";
				$cadena_sql.="ore_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="ore_tre_cod, ";
				$cadena_sql.="tre_cod, ";
				$cadena_sql.="tre_des, ";
				$cadena_sql.="ore_des, ";
				$cadena_sql.="bit_consec ";
				$cadena_sql.="FROM ";
				$cadena_sql.="bitacora, ";
				$cadena_sql.="objrel, ";
				$cadena_sql.="tiprel, ";
				$cadena_sql.="objetos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ore_bit_con=bit_consec ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_obj_cod=obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_tre_cod=tre_cod ";
				$cadena_sql.="ORDER BY ";
				$cadena_sql.="ore_bit_con DESC";
				break;
				
			case "consutaTipreleditados":
				$cadena_sql="SELECT ";
				$cadena_sql.="ore_bit_con, ";
				$cadena_sql.="ore_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="ore_tre_cod, ";
				$cadena_sql.="tre_cod, ";
				$cadena_sql.="tre_des, ";
				$cadena_sql.="ore_des, ";
				$cadena_sql.="bit_consec ";
				$cadena_sql.="FROM ";
				$cadena_sql.="bitacora, ";
				$cadena_sql.="objrel, ";
				$cadena_sql.="tiprel, ";
				$cadena_sql.="objetos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ore_bit_con=bit_consec ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_obj_cod=obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_tre_cod=tre_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_bit_con=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_obj_cod=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_tre_cod=".$variable[1]."";
				break;
				
			case "consultaObjetos":
				$cadena_sql="SELECT ";
				$cadena_sql.="ore_bit_con, ";
				$cadena_sql.="ore_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="ore_tre_cod, ";
				$cadena_sql.="tre_cod, ";
				$cadena_sql.="tre_des, ";
				$cadena_sql.="ore_des ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objrel, ";
				$cadena_sql.="tiprel, ";
				$cadena_sql.="objetos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ore_obj_cod=obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_tre_cod=tre_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_bit_con=".$variable[0]."";
				break;
				
			case "consultaObjetosrelacionados":
				$cadena_sql="SELECT ";
				$cadena_sql.="ore_bit_con, ";
				$cadena_sql.="ore_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="ore_tre_cod, ";
				$cadena_sql.="tre_cod, ";
				$cadena_sql.="tre_des, ";
				$cadena_sql.="ore_des ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objrel, ";
				$cadena_sql.="tiprel, ";
				$cadena_sql.="objetos ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="ore_obj_cod=obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_tre_cod=tre_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_bit_con=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_obj_cod=".$variable[2]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_tre_cod=".$variable[1]."";
				break;
			
			case "consultaBitacora":
				$cadena_sql="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des, ";
				$cadena_sql.="bit_apl_cod, ";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="clo_cod, ";
				$cadena_sql.="bit_clo_cod, ";
				$cadena_sql.="tio_cod, ";
				$cadena_sql.="bit_tio_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="claobj, ";
				$cadena_sql.="aplicaciones, ";
				$cadena_sql.="tipobj, ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="bit_consec=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="apl_cod=bit_apl_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="clo_cod=bit_clo_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="tio_cod=bit_tio_cod";
				break;				
				
			case "editarBitacora":
				$cadena_sql="UPDATE ";
				$cadena_sql.="bitacora ";
				$cadena_sql.="SET ";
				$cadena_sql.="bit_accion='".$variable[1]."' ";
				$cadena_sql.=", ";
				$cadena_sql.="bit_apl_cod=".$variable[2]." ";
				$cadena_sql.=", ";
				$cadena_sql.="bit_clo_cod=".$variable[3]." ";
				$cadena_sql.=", ";
				$cadena_sql.="bit_tio_cod=".$variable[4]." ";
				$cadena_sql.=", ";
				$cadena_sql.="bit_obj_cod=".$variable[5]." ";
				$cadena_sql.=", ";
				$cadena_sql.="bit_des='".$variable[6]."' "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="bit_consec=".$variable[0]."";
				break;
				
			case "consultaEdicion":
				$cadena_sql="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des, ";
				$cadena_sql.="bit_apl_cod, ";
				$cadena_sql.="apl_cod, ";
				$cadena_sql.="clo_cod, ";
				$cadena_sql.="bit_clo_cod, ";
				$cadena_sql.="tio_cod, ";
				$cadena_sql.="bit_tio_cod ";
				$cadena_sql.="FROM ";
				$cadena_sql.="claobj, ";
				$cadena_sql.="aplicaciones, ";
				$cadena_sql.="tipobj, ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="bit_consec=".$variable[1]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="apl_cod=bit_apl_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="clo_cod=bit_clo_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="tio_cod=bit_tio_cod";
				break;
				
			case "editarObjrel":
				$cadena_sql="UPDATE ";
				$cadena_sql.="objrel ";
				$cadena_sql.="SET ";
				$cadena_sql.="ore_obj_cod='".$variable[2]."' ";
				$cadena_sql.=", ";
				$cadena_sql.="ore_tre_cod=".$variable[1]." ";
				$cadena_sql.=", ";
				$cadena_sql.="ore_des='".$variable[3]."' "; 
				$cadena_sql.="WHERE ";
				$cadena_sql.="ore_bit_con=".$variable[0]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_tre_cod=".$variable[4]." ";
				$cadena_sql.="AND ";
				$cadena_sql.="ore_obj_cod=".$variable[5]." ";
				break;
				
			case "objetos":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="(";				
				$cadena_sql.="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des, ";
				$cadena_sql.="usr_nom, ";
				$cadena_sql.="(ROW_NUMBER() OVER (ORDER BY bit_consec desc)) R ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora, ";
				$cadena_sql.="usuario ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="usr_id=bit_usr_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="lower(obj_nombre) LIKE '%".$variable[1]."%' ";
				$cadena_sql.=") ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="R ";
				$cadena_sql.="BETWEEN ";
				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
				$cadena_sql.=" AND ";
				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite
				break;
				
			case "objetostotal":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="lower(obj_nombre) LIKE '%".$variable[1]."%'";
				break;
				
			case "descripcion":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="(";				
				$cadena_sql.="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des, ";
				$cadena_sql.="(ROW_NUMBER() OVER (ORDER BY bit_consec desc)) R ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="lower(bit_des) LIKE '%".$variable[1]."%' ";
				$cadena_sql.=") ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="R ";
				$cadena_sql.="BETWEEN ";
				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
				$cadena_sql.=" AND ";
				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite
				break;
				
			case "descripciontotal":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="lower(obj_nombre) LIKE '%".$variable[1]."%'";
				break;
				
			case "usuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="* ";
				$cadena_sql.="FROM ";
				$cadena_sql.="(";				
				$cadena_sql.="SELECT ";
				$cadena_sql.="bit_consec, ";
				$cadena_sql.="bit_obj_cod, ";
				$cadena_sql.="obj_cod, ";
				$cadena_sql.="obj_nombre, ";
				$cadena_sql.="bit_usr_cod, ";
				$cadena_sql.="bit_fecha, ";
				$cadena_sql.="bit_accion, ";
				$cadena_sql.="bit_des, ";
				$cadena_sql.="(ROW_NUMBER() OVER (ORDER BY bit_consec desc)) R ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="lower(bit_usr_cod) LIKE '%".$variable[1]."%' ";
				$cadena_sql.=") ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="R ";
				$cadena_sql.="BETWEEN ";
				$cadena_sql.=($variable[3]-1)*$configuracion['registro']+1; //Limite inferior
				$cadena_sql.=" AND ";
				$cadena_sql.=(($variable[3]-1)*$configuracion['registro'])+($configuracion['registro']); //Limite
				break;
				
			case "usuariototal":
				//En ORACLE
				$cadena_sql="SELECT ";
				$cadena_sql.="count(*) ";
				$cadena_sql.="FROM ";
				$cadena_sql.="objetos, ";
				$cadena_sql.="bitacora ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="obj_cod=bit_obj_cod ";
				$cadena_sql.="AND ";
				$cadena_sql.="lower(bit_usr_cod) LIKE '%".$variable[1]."%'";
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
