<?
/*
########################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                      
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion     
########################################
*/
/**************************************************************
index.php 

Paulo Cesar Coronado
Copyright (C) 2001-2005

Última revisión 6 de Marzo de 2006

***************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Menu principal
* @usage        
****************************************************************/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	$cripto=new encriptar();
	
	//Rescatar el total de inscritos
	$cadena_sql=cadenasqlAdmin($configuracion,"inscritosGrado",0);
	$campos=$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	if($campos>0)
	{
		$inscritoGrado=$registro[0][0];	
	}
	else
	{
		$inscritoGrado="0";	
	}
	
?><table align="center" class="tablaMarcoLateral">
	<tbody>
		<tr>
			<td >
				<table align="center" border="0" cellpadding="5" cellspacing="0" class="bloquelateral_2">
					<tr class="centralcuerpo">
						<td>
						<b>:.</b> Men&uacute;
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=adminInscritoGrado";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&opcion=listaCompleta";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Listado Total Inscritos (<? echo $inscritoGrado?>)</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=adminInscritoGrado";
							$variable.="&accion=1";
							$variable.="&hoja=1";
							$variable.="&opcion=listadoTotalProyecto";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Inscritos por Proyecto</a>
							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<hr class="hr_substitulo">							
						</td>
					</tr>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=inscribirCoordinacion";
							$variable.="&sinCodigo=1";
							$variable.="&opcion=nuevo";
							//Codigo del Estudiante
							$variable.="&xajax=datos_basicos";
							//La pagina de incripcion utiliza ajax y registra la funcion 
							$variable.="&xajax=pais|region|paisFormacion|regionFormacion";
							$variable.="&xajax_file=inscripcion";
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>"> Inscribir Estudiante</a>
							
						</td>
					</tr>
					<?
					if(isset($_REQUEST["opcion"]) && !isset($_REQUEST["sinCodigo"]))
					{
					?>
					<tr class="bloquelateralcuerpo">
						<td>
						<a href="<?		
							$variable="pagina=listadoGrande";
							$variable.="&opcion=".$_REQUEST["opcion"];
							if(isset($_REQUEST["registro"]))
							{
								$variable.="&registro=".$_REQUEST["registro"];							
							}
							$variable=$cripto->codificar_url($variable,$configuracion);
							echo $indice.$variable;		
							?>">Vista de Impresi&oacute;n</a>
							
						</td>
					</tr><?
					}
					?><tr class="bloquelateralcuerpo">
						<td>
						<br>
						</td>
					</tr>
					
				</table>
			</td>
		</tr>
	</tbody>
</table>
<?
}


function cadenasqlAdmin($configuracion,$opcion="",$valor)
{
	switch($opcion)
	{
		//TO DO Modificar la forma en que se ingresan los registros a la base de datos.
		case "inscritosGrado":
			$cadena_sql="SELECT ";
			$cadena_sql.="count(codigo) ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."usuario ";
			
			break;
		default:
			$cadena_sql="";
	
	
	}
	
	return $cadena_sql;

}

?>