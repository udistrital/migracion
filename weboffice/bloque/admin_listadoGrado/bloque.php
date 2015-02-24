<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
############################################################################
*/
/****************************************************************************
* @name          bloque.php 
* @revision      Última revisión 12 de julio de 2008
*****************************************************************************
* @subpackage   admin_grado
* @package	bloques
* @copyright    
* @version      0.3
* @link		N/D
* @description  Bloque principal para la administración de solicitudes de recibo de pago
*
******************************************************************************/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
//Se incluye para manejar los mensajes de error
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/dbConexion.class.php");
$conexion=new dbConexion($configuracion);
$accesoOracle=$conexion->recursodb($configuracion,"oracle");
$enlace=$accesoOracle->conectar_db();

//Pagina a donde direcciona el menu
$pagina="registro_grado";

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();
if (is_resource($enlace))
{
	if(isset($_REQUEST["opcion"]))
	{
		$nueva_sesion=new sesiones($configuracion);
		$nueva_sesion->especificar_enlace($enlace);
		$esta_sesion=$nueva_sesion->numero_sesion();
		//Rescatar el valor de la variable usuario de la sesion
		$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"usuario");
		if($registro)
		{
			
			$el_usuario=$registro[0][0];
		}
		$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
		if($registro)
		{
			
			$usuario=$registro[0][0];
		}
		$registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"identificacion");
		if($registro)
		{
			
			$usuarioIdentificacion=$registro[0][0];
		}
		else
		{
			$usuarioIdentificacion="0";		
		}
		
		
		switch($_REQUEST["opcion"])
		{
				
			case "listaCompleta":	
				//Rescatar los recibos que se encuentran en proceso de impresion
				$cadena_sql=cadena_busqueda_grado($configuracion, $acceso_db, $usuario,"inscritosGrado");
					
				$registro=ejecutar_admin_grado($cadena_sql,$acceso_db);	
				
				if(!is_array($registro))
				{	
					
					$cadena="En la actualidad no existen inscritos para esta ceremonia de grado.";
					$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
					alerta::sin_registro($configuracion,$cadena);	
				}
				else
				{
					$campos=count($registro);
					$variable["pagina"]="adminInscritoGrado";
					$variable["opcion"]=$_REQUEST["opcion"];				
					con_registro_grado($configuracion,$registro,$campos,$tema,$acceso_db, $accesoOracle);
					
				}
				break;
			
			case "listadoTotalProyecto":	
					
					$cadena_sql=cadena_busqueda_grado($configuracion, $acceso_db, $usuario,"listaTotalProyecto");
					//echo $cadena_sql;	
					$registro=ejecutar_admin_grado($cadena_sql,$acceso_db);	
					
					if(!is_array($registro))
					{	
						
						$cadena="En la actualidad no existen inscritos para esta ceremonia de grado.";
						$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
						alerta::sin_registro($configuracion,$cadena);	
					}
					else
					{
						$campos=count($registro);
						$variable["pagina"]="adminInscritoGrado";
						$variable["opcion"]=$_REQUEST["opcion"];				
						con_registro_proyecto($configuracion,$registro,$campos,$tema,$acceso_db, $accesoOracle);
						
					}
					break;
					
			case "listadoProyecto":	
					
					$cadena_sql=cadena_busqueda_grado($configuracion, $acceso_db, $_REQUEST["registro"],"listadoProyecto");
					//echo $cadena_sql;	
					$registro=ejecutar_admin_grado($cadena_sql,$acceso_db);	
					
					if(!is_array($registro))
					{	
						
						$cadena="En la actualidad no existen inscritos para esta ceremonia de grado.";
						$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
						alerta::sin_registro($configuracion,$cadena);	
					}
					else
					{
						$campos=count($registro);
						$variable["pagina"]="adminInscritoGrado";
						$variable["opcion"]=$_REQUEST["opcion"];				
						con_registro_grado($configuracion,$registro,$campos,$tema,$acceso_db, $accesoOracle);
						
					}
					break;
		
		}
	
	}
}



/****************************************************************
*  			Funciones				*
****************************************************************/



function con_registro_grado($configuracion,$registro,$campos,$tema,$acceso_db, $accesoOracle)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	setlocale(LC_MONETARY, 'en_US');
	
?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
	<tbody>
		<tr>
			<td >
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
					<tr>
						<td>		
							<table class="contenidotabla">
								<tr class="cuadro_plano">
									<td class="cuadro_plano centrar">
									ID
									</td>
									<td class="cuadro_plano centrar">
									C&oacute;digo
									</td>
									<td class="cuadro_plano centrar">
									Nombres
									</td>
									<td class="cuadro_plano centrar">
									Apellidos
									</td>	
									<td class="cuadro_plano centrar">
									Tipo
									</td>									
									<td class="cuadro_plano centrar">
									Documento
									</td>
									<td class="cuadro_plano centrar">
									Expedici&oacute;n
									</td>
									<td class="cuadro_plano centrar">
									Proyecto
									</td>
									<td class="cuadro_plano centrar">
									Trabajo de Grado
									</td>
									<td class="cuadro_plano centrar">
									Director
									</td>
									<td class="cuadro_plano centrar">
									Tipo de Trabajo
									</td>
									<td class="cuadro_plano centrar">
									Direcci&oacute;n
									</td>
									<td class="cuadro_plano centrar">
									Tel&eacute;fono
									</td>
									<td class="cuadro_plano centrar">
									Celular
									</td>
									<td class="cuadro_plano centrar">
									Correo
									</td>
									<td class="cuadro_plano centrar">
									Sexo
									</td>
								</tr>	
					<?
	for($contador=0;$contador<$campos;$contador++)
	{
						//Codigo estudiante
						?>	
								<tr>
									<td class="cuadro_plano">
									<? echo $contador+1 ?>
									</td>
									<td class="cuadro_plano">
									<span class="texto_negrita"><? echo $registro[$contador][0]?></span>
									</td>
									<td class="cuadro_plano">
									<?echo $registro[$contador][1]?>
									</td>
									<td class="cuadro_plano">
									<?echo $registro[$contador][2]?>
									</td>
									<td class="cuadro_plano">
									<? 
									echo htmlentities($registro[$contador][18]);
									 ?>
									</td>									
									<td class="cuadro_plano">
									<? echo  $registro[$contador][5] ?>
									</td>
									<td class="cuadro_plano">
									<? echo $registro[$contador][6] ?>
									</td>
									<td class="cuadro_plano">
									<? 
									//Buscar la carrera
									$cadena_sql=cadena_busqueda_grado($configuracion, $accesoOracle, $registro[$contador][0],"datosEstudiante");
									//echo $cadena_sql;
									$registroOracle=ejecutar_admin_grado($cadena_sql,$accesoOracle);	
									if(is_array($registro))
									{	
										echo $registroOracle[0][7];
									}
									
									//Insertar la carrera en la base de datos
									//$valor[0]=$registroOracle[0][3];
									//$valor[1]=$registro[$contador][0];
									//$cadena_sql=cadena_busqueda_grado($configuracion, $acceso_db, $valor,"actualizarCarrera");
									//echo $cadena_sql;
									//$registroOracle=ejecutar_admin_grado($cadena_sql,$acceso_db);
									?>	
									</td>
									<td class="cuadro_plano">
									<? echo   htmlentities($registro[$contador][14]) ?>
									</td>
									<td class="cuadro_plano">
									<? echo  htmlentities($registro[$contador][15]) ?>
									</td>
									<td class="cuadro_plano">
									<? 
									if($registro[$contador][16]==0)
									{
										echo  "Trabajo de Grado"; 
										 
									}
									else
									{
										echo "Pasantia";
									}	 ?>
									</td>
									<td class="cuadro_plano">
									<? echo  $registro[$contador][7] ?>
									</td>
									<td class="cuadro_plano">
									<? echo  $registro[$contador][11] ?>
									</td>
									<td class="cuadro_plano">
									<? echo  $registro[$contador][12] ?>
									</td>
									<td class="cuadro_plano">
									<? echo  $registro[$contador][13] ?>
									</td>
									<td class="cuadro_plano">
									<? 
									if($registro[$contador][3]==0)
									{
										echo  "Femenino"; 
										 
									}
									else
									{
										echo "Masculino";
									}	 ?>
									</td>
									
								</tr>
	<?
	}
	?>						</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table><?
}

function con_registro_proyecto($configuracion,$registro,$campos,$tema,$acceso_db, $accesoOracle)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$indice=$configuracion["host"].$configuracion["site"]."/index.php?";	
	
?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
	<tbody>
		<tr>
			<td >
				<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
					<tr>
						<td>		
							<table class="contenidotabla">
								<tr class="cuadro_color">
									<td class="cuadro_plano centrar">
									C&oacute;digo
									</td>
									<td class="cuadro_plano centrar">
									Nombre
									</td>
									<td class="cuadro_plano centrar">
									Total Inscritos
									</td>
								</tr>	
					<?
	for($contador=0;$contador<$campos;$contador++)
	{
						//Codigo estudiante
						?>	
								<tr>
									<td class="cuadro_plano">
									<span class="texto_negrita"><? echo $registro[$contador][0]?></span>
									</td>
									<td class="cuadro_plano">
									<? 
									//Buscar la carrera
									$cadena_sql=cadena_busqueda_grado($configuracion, $accesoOracle, $registro[$contador][0],"datosCarrera");
									//echo $cadena_sql;
									$registroOracle=ejecutar_admin_grado($cadena_sql,$accesoOracle);	
									if(is_array($registro))
									{
										$variable="pagina=adminInscritoGrado";
										$variable.="&accion=1";
										$variable.="&hoja=1";
										$variable.="&opcion=listadoProyecto";
										$variable.="&registro=".$registro[$contador][0];
										$variable=$cripto->codificar_url($variable,$configuracion);
										
										echo "<a href='".$indice.$variable."'>".$registroOracle[0][1]."</a>";
									}
									
									//Insertar la carrera en la base de datos
									//$valor[0]=$registroOracle[0][3];
									//$valor[1]=$registro[$contador][0];
									//$cadena_sql=cadena_busqueda_grado($configuracion, $acceso_db, $valor,"actualizarCarrera");
									//echo $cadena_sql;
									//$registroOracle=ejecutar_admin_grado($cadena_sql,$acceso_db);
									?>	
									</td>
									<td class="cuadro_plano">
									<span class="texto_negrita"><? echo $registro[$contador][1]?></span>
									</td>
								</tr>
	<?
	}
	?>						</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table><?
}

function estadistica($configuracion,$contador)
{

//Estadisticas de recibos solicitados, impresos, en proceso de impresion, anulados y pagados
//
//

//1. Rescatar los consolidados de recibos

$cadena_sql=cadena_busqueda_grado($configuracion, $acceso_db, $valor,"estadistica");


?><table style="text-align: left;" border="0"  cellpadding="5" cellspacing="0" class="bloquelateral" width="100%">
	<tr>
		<td >
			<table cellpadding="10" cellspacing="0" align="center">
				<tr class="bloquecentralcuerpo">
					<td valign="middle" align="right" width="10%">
						<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/info.png" border="0" />
					</td>
					<td align="left">
						Actualmente hay <b><? echo $contador ?> usuarios</b> registrados.
					</td>
				</tr>
				<tr class="bloquecentralcuerpo">
					<td align="right" colspan="2" >
						<a href="<?
						echo $configuracion["site"].'/index.php?page='.enlace('admin_dir_dedicacion').'&registro='.$_REQUEST['registro'].'&accion=1&hoja=0&opcion='.enlace("mostrar").'&admin='.enlace("lista"); 
						
						?>">Ver m&aacute;s informaci&oacute;n >></a>
					</td>
				</tr>
			</table> 
		</td>
	</tr>  
</table>
<?}



function calcular_pago($configuracion,$acceso_db, $accesoOracle, $valores)
{
	//1. Verificar pago inicial y reliquidado
	$cadena_sql=cadena_busqueda_grado($configuracion, $accesoOracle, $valores,"datosEstudiante");
	//echo $cadena_sql;
	$registro=ejecutar_admin_grado($cadena_sql,$accesoOracle);	
	if(is_array($registro))
	{
		
		$valor_matricula=$registro[0][2];
		$valor_reliquidado=$valor_matricula;
		$valor_original=$registro[0][1];
		unset($registro);
		
		//2. Rescatar exenciones del estudiante
		$descripcion="";
		$cadena_sql=cadena_busqueda_grado($configuracion, $acceso_db, $valores,"exencionSolicitud");		
		$registro=ejecutar_admin_grado($cadena_sql,$acceso_db);
		if(is_array($registro))
		{
			
			//3. Calcular el pago de acuerdo a las exenciones y construir las observaciones
			for($i=0;$i<count($registro);$i++)
			{
				$esta_exencion=(100-$registro[$i][7])/100;
				$valor_matricula=$valor_matricula*$esta_exencion;
				$descripcion=$descripcion." ".$registro[$i][8];
			}
			
		}
		$matricula[0]=$valor_matricula;
		$matricula[1]=$descripcion;
		$matricula[2]=$valor_original;
		$matricula[3]=$valor_reliquidado;
		
		//echo $matricula[1];
		return $matricula;
			
	}
	

}

function cadena_busqueda_grado($configuracion, $acceso_db, $valor,$opcion="")
{
	$valor=$acceso_db->verificar_variables($valor);
	
	switch($opcion)
	{
		case "inscritosGrado":	
	
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`codigo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`sexo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`numero`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`lugar`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`direccion`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`pais`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`region`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`ciudad`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`telefono`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`celular`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`correo`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`nombreTrabajo`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`director`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`tipoTrabajo`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`idInscripcion`, ";
 			$cadena_sql.=$configuracion["prefijo"]."tipo_documento.`tipo` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."usuario, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento, "; 
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos, ";
  			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado, ";
  			$cadena_sql.=$configuracion["prefijo"]."tipo_documento ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.estado=0 ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDocumento.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDatos.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."inscripcionGrado.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."tipo_documento.`id_tipo`=".$configuracion["prefijo"]."usuarioDocumento.tipo ";
			$cadena_sql.="GROUP BY ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo ";
			$cadena_sql.="ORDER BY ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.idCarrera,  ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo  ";
				
			//echo $cadena_sql;
			break;
			
		case "datosEstudiante":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="est_cod, ";
			$cadena_sql.="est_nro_iden, ";
			$cadena_sql.="est_nombre, ";
			$cadena_sql.="est_cra_cod, ";
			$cadena_sql.="est_diferido, ";
			$cadena_sql.="est_estado_est, ";
			$cadena_sql.="emb_valor_matricula vr_mat, ";
			$cadena_sql.="cra_nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.="acest, ";
			$cadena_sql.="V_ACESTMATBRUTO, ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="est_cod =".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="emb_est_cod = est_cod ";
			$cadena_sql.="AND ";
			$cadena_sql.="cra_cod = est_cra_cod";
			break;
			
		case "datosCarrera":
			//En ORACLE
			$cadena_sql="SELECT ";
			$cadena_sql.="cra_cod, ";
			$cadena_sql.="cra_nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACCRA ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="cra_cod =".$valor." ";
			break;
		
		
		case "estadistica":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.id_carrera, ";
			$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.solicitud, ";
			$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.impresion, ";
			$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion.anulacion, ";
			$cadena_sql.=$configuracion["prefijo"]."programa.nombre ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."estadisticaImpresion, ";
			$cadena_sql.=$configuracion["prefijo"]."programa ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="id_carrera=".$valor." ";
			break;
			
		case "tipoUsuario":
			$cadena_sql="SELECT ";
			$cadena_sql.="cla_codigo, ";
			$cadena_sql.="cla_clave, ";
			$cadena_sql.="cla_tipo_usu, ";
			$cadena_sql.="cla_estado ";
			$cadena_sql.="FROM ";
			$cadena_sql.="geclaves ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="cla_codigo = ".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="( ";
			$cadena_sql.="cla_tipo_usu = 4 "; //Solo coordinadores o asistentes
			$cadena_sql.="OR ";
			$cadena_sql.="cla_tipo_usu = 4 "; //TO DO Tipo de Usuario Asistente de Coordinador
			$cadena_sql.=") ";			
			break;
		
		case "exencionSolicitud":
				$cadena_sql="SELECT ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`nombre`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`porcentaje`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`etiqueta`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`tipo`, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.`soporte` ";	
				$cadena_sql.="FROM ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudExencion, ";
				$cadena_sql.=$configuracion["prefijo"]."exencion ";			
				$cadena_sql.="WHERE ";
				$cadena_sql.=$configuracion["prefijo"]."solicitudExencion.id_solicitud=".$variable." ";
				$cadena_sql.="AND ";
				$cadena_sql.=$configuracion["prefijo"]."exencion.id_exencion=".$configuracion["prefijo"]."solicitudExencion.id_exencion";
				//echo $cadena_sql;
				break;
			//$cadena_sql.="LIMIT 1 ";
			break;
			
		case "actualizarCarrera":
		
			$cadena_sql="UPDATE ";
			$cadena_sql.=$configuracion["prefijo"]."usuario ";
			$cadena_sql.="SET ";
			$cadena_sql.="idCarrera= ".$valor[0]." ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="codigo = ".$valor[1]." ";
			break;
			
		case "listaTotalProyecto":
			
			$cadena_sql="SELECT ";
			$cadena_sql.="idCarrera, ";
			$cadena_sql.="COUNT(*) ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."usuario ";
			$cadena_sql.="GROUP BY ";
			$cadena_sql.="idCarrera ";
			break;
			
		case "listadoProyecto":	
	
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`codigo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.`sexo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`numero`, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento.`lugar`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`direccion`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`pais`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`region`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`ciudad`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`telefono`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`celular`, ";
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos.`correo`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`nombreTrabajo`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`director`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`tipoTrabajo`, ";
 			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.`idInscripcion`, ";
 			$cadena_sql.=$configuracion["prefijo"]."tipo_documento.`tipo` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."usuario, ";
			$cadena_sql.=$configuracion["prefijo"]."usuarioDocumento, "; 
 			$cadena_sql.=$configuracion["prefijo"]."usuarioDatos, ";
  			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado, ";
  			$cadena_sql.=$configuracion["prefijo"]."tipo_documento ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."inscripcionGrado.estado=0 ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.idCarrera=".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDocumento.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."usuarioDatos.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo=".$configuracion["prefijo"]."inscripcionGrado.codigo ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."tipo_documento.`id_tipo`=".$configuracion["prefijo"]."usuarioDocumento.tipo ";
			$cadena_sql.="GROUP BY ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo ";
			$cadena_sql.="ORDER BY ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.idCarrera,  ";
			$cadena_sql.=$configuracion["prefijo"]."usuario.codigo  ";
				
			//echo $cadena_sql;
			break;
		
		default:
			$cadena_sql="";
			break;
	}
	//echo $cadena_sql;
	return $cadena_sql;
}

function ejecutar_admin_grado($cadena_sql,$acceso_db)
{
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	return $registro;
}

?>
