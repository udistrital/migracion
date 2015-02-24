<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    paulo_cesar@etb.net.co                                                #
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion      #
############################################################################
*/
/***************************************************************************
  
index.php 

Oficina Asesora de Sistemas
Copyright (C) 2008

Última revisión 15 de julio de 2008

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	
* @link		N/D
* @description  Formulario para el registro de un archivo de bloques
* @usage        
*******************************************************************************/ 
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}


$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/sesion.class.php");
$sesion=new sesiones($configuracion);
$sesion->especificar_enlace($enlace);
$usuario = $sesion->rescatar_valor_sesion($configuracion,"id_usuario");

if (is_resource($enlace))
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	
	$conexion=new dbConexion($configuracion);
	$accesoOracle=$conexion->recursodb($configuracion,"oracle");
	$enlace=$accesoOracle->conectar_db();
		
	//Rescatar las carreras que han realizado solicitudes
	if($_REQUEST['accion']=='listaCompleta'){
		$cadena_sql=cadenaSQL_admin_impresion($configuracion, "solicitudCarrera", $usuario[0][0]);
		$accion="listaCompleta";
	}
	elseif($_REQUEST['accion']=='listaExtemporaneos'){
		$cadena_sql=cadenaSQL_admin_impresion($configuracion, "solicitudCarreraExtemporaneos", $usuario[0][0]);
		$accion="listaExtemporaneos";
	}
	
	$registroSolicitud=acceso_db_admin_impresion($cadena_sql,$acceso_db,"busqueda");
	
	if($registroSolicitud)
	{
		$i=0;
		?><table align="center" class="tablaMarco">
			<tr>
				<td >		
					<table class="tablaMarco ">
						<tr class=texto_elegante >
							<td colspan='4'>
							<b>::::..</b>  Solicitud Recibos de Pago
							
							<hr class=hr_subtitulo>
							</td>
						</tr>
						<tr class="bloquecentralencabezado">
							<td class='cuadro_plano centrar' width='5%'>
								C&oacute;digo
							</td>
							<td class='cuadro_plano centrar' >
								Nombre
							</td>
							<td class='cuadro_plano centrar'>
								Solicitudes
							</td>
							<td class='cuadro_plano centrar' width='15%'>
							<a href="<?		
								$variable="pagina=oas_verificar_recibo";
								$variable.="&accion=1";
								$variable.="&hoja=1";
								$variable.="&mostrar=lista";
								$variable.="&registro=".$registroSolicitud[$i][0];
								$variable=$cripto->codificar_url($variable,$configuracion);
								echo $indice.$variable;		
								?>">Enlace</a>
								
							</td>
						</tr>
		<?
		while(isset($registroSolicitud[$i][0]))
		{
			unset($registroCarrera);
			//Buscar el nombre de la carrera en ORACLE
			$cadena_sql=cadenaSQL_admin_impresion($configuracion, "carrera", $registroSolicitud[$i][0]);		
			//echo $cadena_sql;
			$registroCarrera=acceso_db_admin_impresion($cadena_sql,$accesoOracle,"busqueda");
			if(is_array($registroCarrera))
			{
				$nombreCarrera=$registroCarrera[0][1];
			
			}
			else
			{
				$nombreCarrera="Carrera Desconocida ".$i;
			
			}
			//Enlace
				?>		<tr class="bloquecentralcuerpo">
							<td class='cuadro_plano centrar'>
								<? echo $registroSolicitud[$i][0]?>
							</td>
							<td class='cuadro_plano'>
								<? echo $nombreCarrera ?>
							</td>
							<td class='cuadro_plano'>
								<? echo $registroSolicitud[$i][1]." solicitudes. "; ?>
							</td>
							<td class='cuadro_plano'>
							<a href="<?		
								$variable="pagina=oas_verificar_recibo";
								$variable.="&opcion=solicitado";
								$variable.="&hoja=1";
								$variable.="&accion=".$accion;
								$variable.="&carrera=".$registroSolicitud[$i][0];
								$variable=$cripto->codificar_url($variable,$configuracion);
								echo $indice.$variable;		
								?>">Ver>></a>
								
							</td>
						</tr><?
			$i++;
		}
		?>
						<tr class="bloquelateralcuerpo">
							<td colspan='4'>
							<hr class="hr_subtitulo">							
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table><?
	
	
	}
	
}	
	

function cadenaSQL_admin_impresion($configuracion, $tipo, $variable)
{
	$cadena_sql="";
	switch($tipo)
	{
		case "solicitudCarrera":
			
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_carrera`, ";
			$cadena_sql.="count(*) ";			
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="estado=0 "; 
			$cadena_sql.="AND tipoPlantilla IN (1,2,3) ";
			$cadena_sql.="GROUP BY ";
			$cadena_sql.="id_carrera ";		
			break;
		case "solicitudCarreraExtemporaneos":
			
			$cadena_sql="SELECT ";
			$cadena_sql.="`id_carrera`, ";
			$cadena_sql.="count(*) ";			
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."solicitudRecibo ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="estado=0 ";
			$cadena_sql.="AND tipoPlantilla IN (4,5) ";			
			$cadena_sql.="GROUP BY ";
			$cadena_sql.="id_carrera ";		
			break;	
		
		case "carrera":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="cra_cod, ";
			$cadena_sql.="cra_nombre, ";
			$cadena_sql.="cra_dep_cod ";						
			$cadena_sql.="FROM ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="cra_cod =".$variable." ";
			break;
			
		case "acins":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="count(*) ";
			$cadena_sql.="FROM ";
			$cadena_sql.="acins ";						
			//$cadena_sql.="FROM ";
			//$cadena_sql.="ACCRA ";
			//$cadena_sql.="WHERE ";
			//$cadena_sql.="cra_cod =".$variable." ";
			break;	
	
	}
	
	return $cadena_sql;



}

function acceso_db_admin_impresion($cadena_sql,$acceso_db,$tipo)
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

		
?>
