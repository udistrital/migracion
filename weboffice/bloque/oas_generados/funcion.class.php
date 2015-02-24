<?php
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");

class funciones_adminSolicitud extends funcionGeneral
{

	function __construct($configuracion, $sql)
	{
		//[ TO DO ]En futuras implementaciones cada usuario debe tener un estilo		
		//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
		include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->sql=$sql;
	}
	
	
	function nuevoRegistro($configuracion,$acceso_db)
	{}
	
   	function editarRegistro($configuracion,$tema,$id_entidad,$acceso_db,$formulario)
   	{}
   	
    	function corregirRegistro()
    	{}
	
	function mostrarRegistro($configuracion,$registro, $total, $opcion="",$variable)
    	{
		
		switch($opcion)
		{
			case "multiplesCarreras":
				$this->multiplesCarreras($configuracion,$registro, $total, $variable);
				break;
				
			case "bloqueado":
				$this->reciboBloqueado($configuracion,$registro, $total);
		
		}
	}
	
		
/*__________________________________________________________________________________________________
		
						Metodos especificos 
__________________________________________________________________________________________________*/
		
	function consultarCarrera($configuracion)
	{
		//Conexion ORACLE
		$accesoOracle=$this->conectarDB($configuracion,"oracle");
		$conexion=$accesoOracle;
		
		$annoActual=$this->datosGenerales($configuracion,$conexion, "anno") ;
		$periodoActual=$this->datosGenerales($configuracion,$conexion, "per") ;
		
		if(isset($_REQUEST["carrera"]))
		{
			$variable[0]=$_REQUEST["carrera"];
		}
		else
		{
			$variable[0]=99999;
		}
		$variable[1]=$annoActual;
		$variable[2]=$periodoActual;
	
		if(isset($_REQUEST["opcion"]))
		{
			switch($_REQUEST["opcion"])
			{
				case "generado":
					//Se seleccionan los recibos bloqueados en ACESTMAT de acuerdo a los criterios de busqueda
					switch($_REQUEST["accion"])
					{
						case "listaCompleta":
							
							//Paginacion
							//Obtener el total de registros
							$cadena_sql=$this->sql->cadena_sql($configuracion,$accesoOracle,"totalBloqueado",$variable);						
							$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
							if(is_array($registro))
							{
								$totalRegistros=$registro[0][0];
							}
							else
							{
								$totalRegistros=0;
							}
							
							//Obtener el numero de pagina
							$this->totalPaginas=ceil($totalRegistros/$configuracion['registro']);
							
							//Obtener el numero de la pagina
							if(!isset($_REQUEST["hoja"]))
							{
								$this->paginaActual=1;
							}
							else
							{
								$this->paginaActual=$_REQUEST["hoja"];
							}
							$variable[3]=$this->paginaActual;
							
							//Obtener la pagina especifica
							$cadena_sql=$this->sql->cadena_sql($configuracion,$accesoOracle,"bloqueadoCompleto",$variable);
							
							
							break;					
					}
					break;
				
				default:
					break;
			}
			
			$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
			if(is_array($registro))
			{
				//Obtener el total de registros
				$totalRegistros=$this->totalRegistros($configuracion, $conexion);
				$this->mostrarRegistro($configuracion,$registro, $totalRegistros, "bloqueado","");
				
				
			}
			else
			{
				
			}
		}
		else
		{
		
		}
		
	}
	
	function reciboBloqueado($configuracion,$registro, $total)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/navegacion.class.php");
		
		setlocale(LC_MONETARY, 'en_US');
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		$menu=new navegacion();
		$variableNavegacion["pagina"]="oas_admin_generados";
		$variableNavegacion["opcion"]="generado";	
		$variableNavegacion["accion"]="listaCompleta";
		$variableNavegacion["carrera"]=$registro[0][2];
		?>
                <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/js/jquery.js"></script>
                <script type="text/javascript" src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/js/jquery.dataTables.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#tabla').dataTable();
                    })
                </script>
                <link type="text/css" href="<? echo $configuracion["host"].$configuracion["site"].$configuracion["javascript"];?>/datatables/css/jquery.dataTables_themeroller.css" rel="stylesheet"/>
                <table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Recibos Generados<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td>
									<table class='contenidotabla' id="tabla">
										<thead>
                                                                                <tr class='cuadro_color'>
											<td class='cuadro_plano centrar'>Recibo</td>
											<td class='cuadro_plano centrar'>C&oacute;digo</td>
											<td class='cuadro_plano centrar'>Nombre</td>
											<td class='cuadro_plano centrar'>Valor</td>
											<td class='cuadro_plano centrar'>Vencimiento</td>
										</tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?
                                                                                        for($contador=0;$contador<$total;$contador++)
                                                                                        {
                                                                                                echo "<tr>\n";
                                                                                                echo "<td class='cuadro_plano centrar'>".$registro[$contador][0]."</td>\n";
                                                                                                //echo "<td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador][1]."</a></td>\n";
                                                                                                echo "<td class='cuadro_plano'><a href='";		
                                                                                                $variable="pagina=oas_factura_coordinador";
                                                                                                //Codigo del Estudiante
                                                                                                $variable.="&opcion=imprimir";
                                                                                                $variable.="&no_pagina=true";
                                                                                                $variable.="&factura=".$registro[$contador][0];
                                                                                                $variable=$cripto->codificar_url($variable,$configuracion);
                                                                                                echo $indice.$variable."'";		
                                                                                                echo ">".$registro[$contador][1]."</a></td>\n";
                                                                                                echo "<td class='cuadro_plano'>".$registro[$contador][13]."</td>\n";
                                                                                                echo "<td class='cuadro_plano centrar'>".money_format('$ %!.0i',$registro[$contador][3])."</td>\n";
                                                                                                echo "<td class='cuadro_plano centrar'>".$registro[$contador][10]."</td>\n";
                                                                                                echo "</tr>";
                                                                                        }
                                                                            ?>
                                                                                </tbody>
									</table>
								
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p class="textoNivel0">La tabla anterior muestra el consolidado de recibos que se han generado 
						por parte de los estudiantes.</p>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee revisar.</p>
						
					</td>
				</tr>
			</tbody>
		</table>
		
		<?
	
	
	}
	
	//Funcion para mostrar el estado de la deuda del estudiante
		
	function multiplesCarreras($configuracion,$registro, $total, $variable)
	{
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
		$cripto=new encriptar();
		
		//Conexion ORACLE
		$conexion=$this->conectarDB($configuracion,"oracle");
		?><table width="100%" align="center" border="0" cellpadding="10" cellspacing="0" >
			<tbody>
				<tr>
					<td >
						<table width="100%" border="0" align="center" cellpadding="5 px" cellspacing="1px" >
							<tr class="texto_subtitulo">
								<td>
								Recibos Generados por Proyecto Curricular<br>
								<hr class="hr_subtitulo">
								</td>
							</tr>
							<tr>
								<td>
									<table class='contenidotabla'>
										<tr class='cuadro_color'><!--td class='cuadro_plano centrar''>Cod.</td--><td class='cuadro_plano centrar''>Total</td><td class='cuadro_plano centrar'>Proyecto Curricular</td></tr><?
		for($contador=0;$contador<$total;$contador++)
		{
			$variable[0]=$registro[$contador][0];
			$cadena_sql=$this->sql->cadena_sql($configuracion,$conexion, "totalCarreraBloqueado",$variable);
			$registroCarrera=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "busqueda");
			if(is_array($registroCarrera))
			{
				if($registroCarrera[0][0]>0)
				{
					//Con enlace a la busqueda
					$parametro="pagina=oas_admin_generados";
					$parametro.="&hoja=1";
					$parametro.="&opcion=generado";
					$parametro.="&accion=listaCompleta";
					$parametro.="&carrera=".$registro[$contador][0];
					$parametro=$cripto->codificar_url($parametro,$configuracion);

					//echo "<tr><td class='cuadro_plano centrar'>".$registroCarrera[$contador][12]."</td>";
					echo "<td class='cuadro_plano centrar'>".$registroCarrera[0][0]."</td>";
					echo "<td class='cuadro_plano'><a href='".$indice.$parametro."'>".$registro[$contador][1]."</a></td></tr>";
				}
				else
				{
					echo "<tr><td class='cuadro_plano centrar'>".$registroCarrera[0][0]."</td><td class='cuadro_plano'>".$registro[$contador][1]."</td></tr>";
				}
			}
			
		}?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<p class="textoNivel0">La tabla anterior muestra el consolidado de recibos que se han generado 
						por parte de los estudiantes.</p>
						<p class="textoNivel0">Por favor realice click sobre el nombre del proyecto curricular que desee revisar.</p>
						</td>
				</tr>
			</tbody>
		</table>
		
		<?
	}
			
		function desbloquearRecibos($configuracion,$conexion)
		{
			foreach($_REQUEST as $clave => $valor) 
			{
				if(substr($clave,0,strlen("codDesbloquear"))=="codDesbloquear")
				{
					
					$variable[0]=$_REQUEST["codDesbloquear_".substr($clave,(strlen("codDesbloquear_")))];
					$variable[1]=$_REQUEST["recDesbloquear_".substr($clave,(strlen("recDesbloquear_")))];
					
					$cadena_sql=$this->sql->cadena_sql($configuracion,$conexion, "desbloquear",$variable);
					$registro=$this->ejecutarSQL($configuracion, $conexion, $cadena_sql, "");
					//echo $cadena_sql."aw<br>";
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=oas_admin_solicitud";
					$variable.="&opcion=generado";
					$variable.="&accion=listaCompleta";
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$cripto=new encriptar();
					$variable=$cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>"; 
					
				};
				
				
				
			} 	
		
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
		
		
		
		function calcular_pago($configuracion,$acceso_db, $accesoOracle, $valores)
		{
			//1. Verificar pago inicial y reliquidado
			$cadena_sql=cadena_busqueda_recibo($configuracion, $accesoOracle, $valores,"datosEstudiante");
			//echo $cadena_sql;
			$registro=ejecutar_admin_recibo($cadena_sql,$accesoOracle);	
			if(is_array($registro))
			{
				
				$valor_matricula=$registro[0][2];
				$valor_reliquidado=$valor_matricula;
				$valor_original=$registro[0][1];
				unset($registro);
				
				//2. Rescatar exenciones del estudiante
				$descripcion="";
				$cadena_sql=cadena_busqueda_recibo($configuracion, $acceso_db, $valores,"exencionSolicitud");		
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
		
		
		
		
}

?>

