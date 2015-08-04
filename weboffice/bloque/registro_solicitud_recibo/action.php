<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/***************************************************************************
  
registro.action.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 2 de junio de 2007

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Action de registro de usuarios
* @usage        
******************************************************************************/
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/datosGenerales.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");

//======= Revisar si no hay acceso ilegal ==============
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
//======================================================

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();

if (is_resource($enlace))
{
	if(isset($_REQUEST["codigo"]))
	{
		//Conectarse a una base de datos diferente a la por defecto
		$conexion=new dbConexion($configuracion);
		$accesoOracle=$conexion->recursodb($configuracion,"oracle");
		$enlace=$accesoOracle->conectar_db();
		
		$datoBasico=new datosGenerales();
		//Anno y Periodo actual para el Pago			
		if(isset($_REQUEST["anno"]) && isset($_REQUEST["periodo"]) )
		{
			$annoPer["anno"]=$_REQUEST["anno"];
			$annoPer["periodo"]=$_REQUEST["periodo"];
			
		}
		else
		{
			$dato["anno"]=$datoBasico->rescatarDatoGeneral($configuracion, "anno", "", $accesoOracle);
			$dato["per"]=$datoBasico->rescatarDatoGeneral($configuracion, "per", "", $accesoOracle);
		}
		$dato["salarioMinimo"]=$datoBasico->rescatarDatoGeneral($configuracion, "salarioMinimo", "", $accesoOracle);
		
		
			
		if(isset($_REQUEST['registro']))
		{
			$variable['id_solicitud']=$_REQUEST['registro'];
			//Verificar que el registro que se esta editando en realidad exista
			$cadena_sql=cadena_sql_solicitud($configuracion,"select",$variable);
			$registro=acceso_db_solicitud($cadena_sql,$acceso_db,"busqueda");
			if(is_array($registro))
			{
				unset ($registro);
				unset($variables);
				$usuario=sesion_solicitud($configuracion,$acceso_db,"id_usuario");
				if(is_array($usuario))
				{
					$variables["id_usuario"]=$usuario[0][0];
					$cadena_sql=cadena_sql_solicitud($configuracion,"update",$variables);
					$resultado=acceso_db_solicitud($cadena_sql,$acceso_db,"");
				}
				else
				{
					$resultado=FALSE;			
				}
				
			}
			else
			{
				
				$resultado=FALSE;
			}		
		}
		else
		{
			unset($variables);
			//Si no existe un id registro valido entonces se trata de un registro nuevo o una correccion
			$sesion=sesion_solicitud($configuracion,$acceso_db,"id_usuario");
			
			if(is_array($sesion))
			{
				//1. Guardar Datos Generales de la Solicitud
				
				$variables["id_usuario"]=$sesion[0][0];
				$variables["codigo"]="'".$_REQUEST["codigo"]."'";
				
				//id Carrera
				if(strlen($_REQUEST["codigo"])==11)
				{
					$variables["id_programa"]=substr($_REQUEST["codigo"],4,3);
				}
				else
				{
					$variables["id_programa"]=substr($_REQUEST["codigo"],2,2);
				}
				
				$dato["nivelCarrera"]=$datoBasico->rescatarDatoGeneral($configuracion, "nivelCarrera", "", $accesoOracle);
		
				
				//Anno
				$variables["anno"]=$dato["anno"];
				
				//Periodo
				$variables["periodo"]=$dato["periodo"];
				
				//Salario Minimo
				$variables["salarioMinimo"]=$dato["salarioMinimo"];
				
				
			}
			else
			{
				$variables["id_usuario"]="0";
			}
			
			$cadena_sql=cadena_sql_solicitud($configuracion,"insertar",$variables);
			$resultado=acceso_db_solicitud($cadena_sql,$acceso_db,"");
			
			//id_ solicitud
			$variables["id_solicitud"]=$acceso_db->ultimo_insertado($enlace);
			
			//2. Guardar Exenciones relacionadas con la solicitud
			//3. Actualizar exenciones del estudiante
			
			unset ($registro);
			$cadena_sql=cadena_sql_solicitud($configuracion,"deleteExencion",$variables);		
			$registro=acceso_db_solicitud($cadena_sql,$acceso_db,"");
			foreach ($_REQUEST as $key => $value)
			{
				if(strtolower(substr($key,0,8))=="exencion")
				{
					$exencion=substr($key,9);
					$variables["exencion"]=$exencion;
					$cadena_sql=cadena_sql_solicitud($configuracion,"insertExencion",$variables);	
					$registro=acceso_db_solicitud($cadena_sql,$acceso_db);
					$cadena_sql=cadena_sql_solicitud($configuracion,"updateExencion",$variables);	
					$registro=acceso_db_solicitud($cadena_sql,$acceso_db);
					$cadena_sql=cadena_sql_solicitud($configuracion,"updateExencionOracle",$variables);	
					$registro=acceso_db_solicitud($cadena_sql,$acceso_db);				
				}
				else
				{
					//4. Guardar conceptos asociados a la solicitud
					if(strtolower(substr($key,0,8))=="concepto")
					{
						$concepto=substr($key,9);
						$variables["concepto"]=$concepto;
						$cadena_sql=cadena_sql_solicitud($configuracion,"insertConcepto",$variables);
						$registro=acceso_db_solicitud($cadena_sql,$acceso_db,"");
					}
				}
			
			}
			
			//5. Calcular las cuotas de la solicitud
			
			if($dato["nivelCarrera"]=="POSGRADO")
			{
				//Calcular el valor bruto de la matricula
				if($_REQUEST["tipoPago"]==1)
				{
					//Sistema por Creditos
					//Valor de la Unidad
					$valorUnidad=rescatarValorUnidad($variables["nivelCarrera"],$variables["id_programa"]);
					$valorUnidad=$valorUnidad*$variables["salarioMinimo"];
					$valorMatriculaBruto=round($valorUnidad*($_REQUEST["cantidad"]/1));
				
				}
				else
				{
					//Sistema por SMLV
					$valorMatriculaBruto=round($variables["salarioMinimo"]*($_REQUEST["cantidad"]/1));
				
				}
				
				
				
				$i=1;
				//Calcular cuotas segun $_REQUEST["cuota"]
				if(!is_numeric($_REQUEST["cuota"]) )
				{
					$cuotas=1;
				}
				elseif($_REQUEST["cuota"]>3 || $_REQUEST["cuota"]<1 )
				{
					$cuotas=1;
				}
				else
				{
					$cuotas=$_REQUEST["cuota"];
				}
				
				
				
				
				
				
				
				
			}
			elseif($dato["nivelCarrera"]=="PREGRADO")
			{
				if($_REQUEST["diferido"]=="S")
				{
					//Calcular cuotas diferidas segun tabla
				
				}
			}
			
			
			
			
			
			
			
			//6. Almacenar las cuotas de la solicitud
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		}
		if($resultado)
		{
			unset ($registro);
			$opciones="pagina=administrar_recibo";
			$opciones.="&accion=1";
			$opciones.="&hoja=1";
			$opciones.="&opcion=lista";
			enrutar_solicitud($configuracion,$opciones);	
		}
	
	
	
	}
	else
	{
		unset ($registro);
		//echo $cadena_sql;exit;
		$opciones="pagina=error_ingresar_datos";
		enrutar_solicitud($configuracion,$opciones);
	}
}

//===========================================================================
//                         FUNCIONES
//===========================================================================

function calcularCuota()
{


}

function rescatarValorUnidad()
{

}

function acceso_db_solicitud($cadena_sql,$acceso_db,$tipo)
{
	if($tipo=="busqueda")
	{
		$acceso_db->registro_db($cadena_sql,0);
		$registro=$acceso_db->obtener_registro_db();
		return $registro;
	}
	else
	{
		$resultado=$acceso_db->ejecutar_acceso_db($cadena_sql);
		return $resultado;
	}
}


function enrutar_solicitud($configuracion,$variable)
{
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	$cripto=new encriptar();
	$variable=$cripto->codificar_url($variable,$configuracion);
	echo "<script>location.replace('".$indice.$variable."')</script>";
	
}

function cadena_sql_solicitud($configuracion,$tipo,$variable="")
{
	
	switch($tipo)
	{
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
		
		
		default:
			break;
	}

	return $cadena_sql;

}

function sesion_solicitud($configuracion,$acceso_db,$variable)
{
	$nueva_sesion=new sesiones($configuracion);
	$nueva_sesion->especificar_enlace($acceso_db->obtener_enlace());
	$esta_sesion=$nueva_sesion->numero_sesion();
	
	if (strlen($esta_sesion) != 32) 
	{
		return FALSE;
	
	} 
	else 
	{
		$resultado = $nueva_sesion->rescatar_valor_sesion($configuracion,$variable);
		return $resultado;
	}
	
	
}
	
?>
