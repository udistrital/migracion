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

class sql_generarFactura extends sql
{
	function cadena_sql($configuracion,$tipo,$variable="")
		{
			
			switch($tipo)
			{
				case "select_exencion":
					$cadena_sql="SELECT ";
					$cadena_sql.="`id_exencion`, ";
					$cadena_sql.="`nombre`, ";
					$cadena_sql.="`porcentaje`, ";
					$cadena_sql.="`etiqueta`, ";
					$cadena_sql.="`tipo`, ";
					$cadena_sql.="`soporte` ";
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."exencion ";
					$cadena_sql.="WHERE ";
					$cadena_sql.="tipo=".$variable;
					
					break;
					
				case "select":
					$cadena_sql="SELECT ";
					$cadena_sql.="`id_entidad`, ";
					$cadena_sql.="`id_padre`, ";
					$cadena_sql.="`id_usuario`, ";
					$cadena_sql.="`fecha`, ";
					$cadena_sql.="`nombre`, ";
					$cadena_sql.="`etiqueta`, ";
					$cadena_sql.="`logosimbolo`, ";
					$cadena_sql.="`nit`, ";
					$cadena_sql.="`fundacion`, ";
					$cadena_sql.="`direccion`, ";
					$cadena_sql.="`telefono`, ";
					$cadena_sql.="`web`, ";
					$cadena_sql.="`correo`, ";
					$cadena_sql.="`mision`, ";
					$cadena_sql.="`vision`, ";
					$cadena_sql.="`descripcion`, ";
					$cadena_sql.="`comentario`, ";
					$cadena_sql.="`tipo`, ";
					$cadena_sql.="`latitud`, ";
					$cadena_sql.="`longitud` ";
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."entidad "; 			
					$cadena_sql.="WHERE ";
					
					foreach ($variable as $key => $value) 
					{
						$cadena_sql.=$key."=".$value." ";
						$cadena_sql.="AND ";
					}
					$cadena_sql=substr($cadena_sql,0,(strlen($cadena_sql)-4));
				
				
				default:
					break;
				
				case "insertar":
					$cadena_sql="INSERT INTO ";
					$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo "; 
					$cadena_sql.="( ";
					$cadena_sql.="`id_usuario`, ";
					$cadena_sql.="`codigo_est`, ";
					$cadena_sql.="`estado`, ";
					$cadena_sql.="`anno`, ";
					$cadena_sql.="`periodo`, ";
					$cadena_sql.="`fecha` ";
					$cadena_sql.=") ";
					$cadena_sql.="VALUES ";
					$cadena_sql.="( ";
					$cadena_sql.="'".$variable['id_usuario']."', ";
					$cadena_sql.="'".$_REQUEST['codigo']."', ";
					$cadena_sql.="'0', ";
					$cadena_sql.="'".$variable['anno']."', ";
					$cadena_sql.="'".$variable['periodo']."', ";
					$cadena_sql.="'".time()."' ";
					$cadena_sql.=")";
					break;
				
				case "updateExencion":
					$cadena_sql="INSERT INTO ";
					$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion "; 
					$cadena_sql.="( ";
					$cadena_sql.="`codigo_est`, ";
					$cadena_sql.="`id_programa`, ";
					$cadena_sql.="`id_exencion`, ";
					$cadena_sql.="`anno`, ";
					$cadena_sql.="`periodo`, ";
					$cadena_sql.="`fecha` ";
					$cadena_sql.=") ";
					$cadena_sql.="VALUES ";
					$cadena_sql.="( ";
					$cadena_sql.=$variable['codigo'].", ";
					$cadena_sql.="'".$variable['id_programa']."', ";
					$cadena_sql.="'".$variable['exencion']."', ";
					$cadena_sql.="'".$variable['anno']."', ";
					$cadena_sql.="'".$variable['periodo']."', ";
					$cadena_sql.="'".time()."' ";
					$cadena_sql.=")";
					break;
								
				case "updateExencionOracle":
								
					/*$cadena_sql="UPDATE ACEST ";
					$cadena_sql.="SET ";
					$cadena_sql.="est_exento = '".."' ";
					$cadena_sql.="est_motivo_exento = ".." ";
					$cadena_sql.="est_porcentaje = ".." ";
					$cadena_sql.="WHERE ";
					$cadena_sql.="est_cod = ".$variable['codigo']." ";
					*/
					break;
					
				case "insertExencion":
					$cadena_sql="INSERT INTO ";
					$cadena_sql.=$configuracion["prefijo"]."solicitudExencion "; 
					$cadena_sql.="( ";
					$cadena_sql.="`id_solicitud`, ";
					$cadena_sql.="`id_exencion`, ";
					$cadena_sql.=") ";
					$cadena_sql.="VALUES ";
					$cadena_sql.="( ";
					$cadena_sql.="'".$variable['id_solicitud']."', ";
					$cadena_sql.="'".$variable['exencion']."' ";
					$cadena_sql.=")";			
					break;
					
				case "insertConcepto":
					$cadena_sql="INSERT INTO backoffice_solicitudConcepto "; 
					$cadena_sql.="( ";
					$cadena_sql.="`id_solicitud`, ";
					$cadena_sql.="`id_concepto`, ";
					$cadena_sql.="`valor` ";
					$cadena_sql.=") ";
					$cadena_sql.="VALUES ";
					$cadena_sql.="( ";
					$cadena_sql.="'".$variable['id_solicitud']."', ";
					$cadena_sql.="'".$variable['concepto']."', ";
					$cadena_sql.="'1' ";
					$cadena_sql.=")";
					
					break;
					
				
				
				case "select":
					$cadena_sql="SELECT ";
					$cadena_sql.="`id_solicitud_recibo`, ";
					$cadena_sql.="`id_usuario`, ";
					$cadena_sql.="`codigo_est`, ";
					$cadena_sql.="`estado`, ";
					$cadena_sql.="`fecha` ";
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."solicitud_recibo "; 
					$cadena_sql.="WHERE ";
					$cadena_sql.="`estado`=1 ";
					$cadena_sql.="AND ";
					$cadena_sql.="`id_usuario`='".$variable["id_usuario"]."'";
					//estado=0 solicitud no procesada
					//estado=1 solicitud en proceso
					//estado=2 solicitud procesada
					break;
					
				case "deleteExencion":
					$cadena_sql="DELETE ";
					$cadena_sql.="FROM ";
					$cadena_sql.=$configuracion["prefijo"]."estudiante_exencion "; 
					$cadena_sql.="WHERE ";
					$cadena_sql.="`codigo_est`=".$variable["codigo"]." ";
					//TO DO asociar un anno y un periodo a las exenciones
					
					
					break;
			
			}
			
			
		
			return $cadena_sql;
		
		}
}
?>
