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
* @subpackage   admin_recibo
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

//Pagina a donde direcciona el menu
$pagina="registro_recibo";

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
		
		//Rescatar los proyectos curriculares a los cuales esta adscrito el usuario
		/*$valor=$usuarioIdentificacion;
		$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $usuario,"tipoUsuario");
		$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);
		if(is_array($registro))
		{
			
			if($_REQUEST["opcion"]=="lista")
			{
				//Rescatar los recibos que se encuentran en proceso de impresion
				$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $usuario,"completa");
				$cadena_hoja=$cadena_sql;
				
				//Si no se viene de una hoja anterior
				if(!isset($_REQUEST["hoja"]))
				{
					$_REQUEST["hoja"]=1;
				}
				$cadena_hoja.=" LIMIT ".(($_REQUEST["hoja"]-1)*$configuracion['registro']).",".$configuracion['registro'];	
				//echo $cadena_hoja;
				
					
				$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);	
				if(is_array($registro))
				{	
					$campos=count($registro);
					$hojas=ceil($campos/$configuracion['registro']);	
				}
				else
				{
					$hojas=1;
				
				}
				
				//Rescatar una hoja específica
				$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);	
				if(!is_array($registro))
				{	
					
					$cadena="En la actualidad esta Coordinación no tiene ningún recibo registrado para impresión.";
					$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
					alerta::sin_registro($configuracion,$cadena);	
				}
				else
				{
					$campos=count($registro);
					$variable["pagina"]="registro_recibo";
					$variable["opcion"]=$_REQUEST["opcion"];				
							
					$menu=new navegacion();
					if($hojas>1)
					{
						$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$pagina,$variable);
					}
					con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db);
					$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$pagina);
				}
				$variable["pagina"]="registro_recibo";
				$variable["opcion"]=$_REQUEST["opcion"];				
				
				$menu=new navegacion();
				if($hojas>1)
				{
				$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$pagina,$variable);
				}
				con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db);
				$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$pagina);
			}
			else
			{
				
				estadistica($configuracion,$registro);
			}	
			
			
		
		}*/
		
		if($_REQUEST["opcion"]=="lista")
			{
				//Rescatar los recibos que se encuentran en proceso de impresion
				$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $usuario,"historico");
				echo $cadena_sql;
				$cadena_hoja=$cadena_sql;
				
				//Si no se viene de una hoja anterior
				if(!isset($_REQUEST["hoja"]))
				{
					$_REQUEST["hoja"]=1;
				}
				$cadena_hoja.=" LIMIT ".(($_REQUEST["hoja"]-1)*$configuracion['registro']).",".$configuracion['registro'];	
				//echo $cadena_hoja;
				
					
				$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);	
				if(is_array($registro))
				{	
					$campos=count($registro);
					$hojas=ceil($campos/$configuracion['registro']);	
				}
				else
				{
					$hojas=1;
				
				}
				
				//Rescatar una hoja específica
				$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);	
				if(!is_array($registro))
				{	
					
					$cadena="En la actualidad esta Coordinación no tiene ningún recibo registrado para impresión.";
					$cadena=htmlentities($cadena, ENT_COMPAT, "UTF-8");
					alerta::sin_registro($configuracion,$cadena);	
				}
				else
				{
					$campos=count($registro);
					$variable["pagina"]="registro_recibo";
					$variable["opcion"]=$_REQUEST["opcion"];				
							
					$menu=new navegacion();
					if($hojas>1)
					{
						$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$pagina,$variable);
					}
					con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db);
					$menu->menu_navegacion($configuracion,$_REQUEST["hoja"],$hojas,$pagina);
				}
			}
			else
			{
				
				estadistica($configuracion,$registro);
			}
		
	}
	
}



/****************************************************************
*  			Funciones				*
****************************************************************/



function con_registro_recibo($configuracion,$registro,$campos,$tema,$acceso_db)
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
					<tr class="texto_subtitulo">
						<td>
						Solicitudes en Proceso de Edici&oacute;n
						<hr class="hr_subtitulo">
						</td>
					</tr>
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
									Vlr. a Pagar
									</td>
									<td class="cuadro_plano centrar">
									Observaciones
									</td>
									<td colspan="2" class="cuadro_plano centrar">
									Opciones
									</td>
								</tr>	
					<?
	for($contador=0;$contador<$campos;$contador++)
	{
		//Codigo estudiante
		$valor[0]=$registro[$contador][2];
		//Codigo carrera
		$valor[1]=$registro[$contador][6];
		
		$mi_matricula=calcular_pago($configuracion, $acceso_db, $valor);


				?>	
								<tr>
									<td class="cuadro_plano">
									<span class="texto_negrita"><? echo $registro[$contador][2]?></span>
									</td>
									<td class="cuadro_plano">
									<?echo $registro[$contador][5] ?>
									</td>
									<td class="cuadro_plano">
									<? echo money_format('$ %!.0i', $mi_matricula[0]) ?>
									</td>
									<td class="cuadro_plano">
									<? echo $mi_matricula[1] ?>	
									</td>
									<td align="center" width="10%" class="cuadro_plano" colspan="2"><?
									if($_REQUEST["accion"]==1)
									{
									?><a href="<?
									$variable="pagina=registro_recibo";
									$variable.="&opcion=editar";
									$variable.="&id_recibo=".$registro[$contador][0];
									$variable=$cripto->codificar_url($variable,$configuracion);
									echo $indice.$variable;	
									?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/editar.png"?>" alt="Editar este registro" title="Editar este registro" border="0" />	
									<?
									}
									if($_REQUEST["accion"]==1)
									{
									?><a href="<?
									$variable="pagina=borrar_registro";
									$variable.="&opcion=solicitud";
									$variable.="&registro=".$registro[$contador][0];
									$redireccion="";		
									reset ($_REQUEST);
									while (list ($clave, $val) = each ($_REQUEST)) 
									{
										$redireccion.="&".$clave."=".$val;
										
									}
									
									$variable.="&redireccion=".$cripto->codificar_url($redireccion,$configuracion);
									
									$variable=$cripto->codificar_url($variable,$configuracion);
									
									echo $indice.$variable;	
									?>"><img width="24" heigth="24" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/boton_borrar.png"?>" alt="Borrar el registro" title="Borrar el registro" border="0" /></a>	
									<?
									}
									?></td>
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

$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valor,"estadistica");


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



function calcular_pago($configuracion, $acceso_db, $valores)
{
	//1. Verificar pago inicial y reliquidado
	$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valores,"matricula");
	$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);	
	if(is_array($registro))
	{
		
		$valor_matricula=$registro[0][2];
		$valor_reliquidado=$valor_matricula;
		$valor_original=$registro[0][1];
		unset($registro);
		
		//2. Rescatar exenciones del estudiante
		$descripcion="";
		$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valores,"exencion");		
		$registro=ejecutar_admin_recibo($cadena_sql,$acceso_db);
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

function cadena_busqueda_recibo($configuracion, $acceso_db, $valor,$opcion="")
{
	$valor=$acceso_db->verificar_variables($valor);
	
	switch($opcion)
	{
		case "historico":
			$cadena_sql="SELECT ";
			$cadena_sql.="RBA_BAN_COD, ";
			$cadena_sql.="BAN_NOMBRE, ";
			$cadena_sql.="RBA_OFICINA, ";
			$cadena_sql.="RBA_DIA||'/'||MES_ABREV||'/'||RBA_ANO FECHA, ";
			$cadena_sql.="RBA_VALOR, ";
			$cadena_sql.="RBA_SECUENCIA ";
			$cadena_sql.="FROM ";
			$cadena_sql.="ACRECBAN, ";
			$cadena_sql.="ACBANCO, ";
			$cadena_sql.="GEMES ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="RBA_COD = ".$valor." ";
			$cadena_sql.="AND ";
			$cadena_sql.="BAN_COD = RBA_BAN_COD ";
			$cadena_sql.="AND ";
			$cadena_sql.="BAN_ESTADO = 'A' ";
			$cadena_sql.="AND ";
			$cadena_sql.="MES_COD = RBA_MES ";
			$cadena_sql.="ORDER BY 3,4,5 DESC";
			break;
			
		case "detallePago":
			$cadena_sql="SELECT emb_est_cod est, ";
			$cadena_sql.="emb_valor_matricula vr_mat, ";
			$cadena_sql.="vlr_seguro ";
			$cadena_sql.="FROM ";
			$cadena_sql.="V_ACESTMATBRUTO,";
			$cadena_sql.="v_valor_seguro ";
			$cadena_sql.="WHERE ";
			$cadena_sql.="emb_est_cod = ".$usuario;
		default:
			$cadena_sql="";
			break;
	}
	//echo $cadena_sql;
	return $cadena_sql;
}

function ejecutar_admin_recibo($cadena_sql,$acceso_db)
{
	$acceso_db->registro_db($cadena_sql,0);
	$registro=$acceso_db->obtener_registro_db();
	return $registro;
}

?>
