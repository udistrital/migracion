<?
/*
############################################################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                        #
#    Copyright: Vea el archivo LICENCIA.txt que viene con la distribucion  #
****************************************************************************/

/**************************************************************************
* @name          pagina.class.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 3 de marzo de 2008
****************************************************************************
* @subpackage   
* @package	clase
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Esta clase esta disennada para manejar la presentacion de las 
*               diferentes paginas usadas en la aplicacion. Se encarga de 
*               administrar los bloques constitutivos de las paginas
*
******************************************************************************/


class pagina
{
	

	function pagina($configuracion)
	{
		$GLOBALS["autorizado"]=TRUE;
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		
		if(isset($_REQUEST[$configuracion["enlace"]]))
		{		
			$cripto->decodificar_url($_REQUEST[$configuracion["enlace"]],$configuracion);
			if(isset($_REQUEST["pagina"]))
			{
				$this->especificar_pagina($_REQUEST["pagina"]);
			}
			else
			{
				$this->especificar_pagina("");	
			}	
			
		}
		else
		{
			if(isset($_REQUEST["redireccion"]))
			{
				$variable="";		
				reset ($_REQUEST);
				while (list ($clave, $val) = each ($_REQUEST)) 
				{
					if($clave !="redireccion")
					{
						$variable.="&".$clave."=".$val;
					}
				}
				
				$cripto->decodificar_url($_REQUEST["redireccion"],$configuracion);
				
				while (list ($clave, $val) = each ($_REQUEST)) 
				{
						$variable.="&".$clave."=".$val;
				}
				
				$variable=$cripto->codificar_url($variable,$configuracion);
				$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
				echo "<script>location.replace('".$indice.$variable."')</script>";
				
			}
			else
			{
				if(isset($_REQUEST["formulario"]))
				{
					$variable="";		
					reset ($_REQUEST);
					foreach ($_REQUEST as $clave=>$val)
					{
						if($clave !="formulario")
						{
							$formulario[$clave]=$val;
						}
					}
					
					$cripto->decodificar_url($_REQUEST["formulario"],$configuracion);
					
					foreach ($formulario as $clave=>$val)
					{
							$_REQUEST[$clave]=$val;
					}
					
					
				}
				
				$pagina_nivel=0;			
				$this->especificar_pagina("index");
			}
		
		}
		
		
		/*foreach($_REQUEST as $clave=>$valor)
		{
			echo $clave."=".$valor;			
		}*/
		
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/autenticacion.class.php");
		$this->autenticador=new autenticacion($this->id_pagina,$configuracion);
		
		if(!isset($_REQUEST['action']))
		{
			
			$this->mostrar_pagina($configuracion);
		}
		else
		{
			$this->procesar_pagina($configuracion);
		}
	}
	
	
	
	function especificar_pagina($nombre)
	{
	
		$this->id_pagina=$nombre;
		return 1;
	
	}
	
	
	function mostrar_pagina($configuracion)
	{
		
		
		$this->cadena_sql="SELECT  ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina.*,";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque.nombre, ";
		$this->cadena_sql.=$configuracion["prefijo"]."pagina.parametro ";
		$this->cadena_sql.="FROM ";
		$this->cadena_sql.=$configuracion["prefijo"]."pagina, ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina, ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque ";
		$this->cadena_sql.="WHERE ";
		$this->cadena_sql.=$configuracion["prefijo"]."pagina.nombre='".$this->id_pagina."' ";
		$this->cadena_sql.="AND ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina.id_bloque=".$configuracion["prefijo"]."bloque.id_bloque ";
		$this->cadena_sql.="AND ";
		$this->cadena_sql.=$configuracion["prefijo"]."bloque_pagina.id_pagina=".$configuracion["prefijo"]."pagina.id_pagina";
		//echo $this->cadena_sql;
		$this->base=new dbms($configuracion);
		$this->enlace=$this->base->conectar_db();
		if (is_resource($this->enlace))
		{
			$this->base->registro_db($this->cadena_sql,0);
			$this->registro=$this->base->obtener_registro_db();
			$this->total=$this->base->obtener_conteo_db();

			if($this->total<1)
			{
				echo "<h3>La pagina que esta intentando acceder no esta disponible.</h3><br>";
				unset($this->registro);
				unset($this->total);
				exit;
			}
			else
			{
                           
                                //no mostrar errores
                                error_reporting(0);
                                   //Verificar parametros por defecto
				if($this->registro[0][5]!="")
				{
					$parametros=explode("&",$this->registro[0][5]);
					foreach($parametros as $valor)
					{
						$elParametro=explode("=",$valor);
						$_REQUEST[$elParametro[0]]=$elParametro[1];

					}
				}
				$nueva_sesion=new sesiones($configuracion);
				$esta_sesion=$nueva_sesion->numero_sesion();
				$this->registro=$nueva_sesion->rescatar_valor_sesion($configuracion,"id_usuario");
				if($this->registro)
				{
					$this->id_usuario=$this->registro[0][0];
				}
				else
				{
					$this->id_usuario=0;
				}
				
				$this->SQL="SELECT  ";
				$this->SQL.="usuario, ";
				$this->SQL.="estilo ";
				$this->SQL.="FROM ";
				$this->SQL.=$configuracion["prefijo"]."estilo ";
				$this->SQL.="WHERE ";
				$this->SQL.="usuario='".$this->id_usuario."'";
				//echo $this->SQL;
				
				$this->base->registro_db($this->SQL,0);
				$this->registro=$this->base->obtener_registro_db();
				$this->total=$this->base->obtener_conteo_db();
				if($this->total<1)
				{
					$this->estilo='basico';
				}
				else
				{
					$this->estilo=$this->registro[0][1];
				}
				
				unset($this->registro);
				unset($this->total);
				
				
				$this->tamanno=$configuracion["tamanno_gui"];
				$GLOBALS["fila"]=0;
				$GLOBALS["tab"]=1;
				
				
				if(!isset($_REQUEST["no_pagina"]))
				{
                                        header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
                                        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                                        header("Cache-Control: no-store, no-cache, must-revalidate");
                                        header("Cache-Control: post-check=0, pre-check=0", false);
                                        header("Pragma: no-cache");

					//Para paginas que utilizan ajax
					if(isset($_REQUEST["xajax"]))
					{
						require_once($configuracion["raiz_documento"].$configuracion["clases"]."/xajax/xajax.inc.php");
						$GLOBALS["xajax"] = new xajax();
						//$GLOBALS["xajax"]->debugOn();
						
						//Registrar las funciones especificas de ajax para la pagina
						//Las funciones vienen relacionadas en la variable xajax separadas por el caracter "|"						
						$funciones_ajax=explode('|', $_REQUEST["xajax"]);
						$i=0;
						
						//Incluir el archivo que procesara las peticiones Ajax en PHP
						if(!isset($_REQUEST["xajax_file"]))
						{
							include_once($configuracion["raiz_documento"].$configuracion["clases"]."/xajax/include/funciones_ajax.class.php");
							while(isset($funciones_ajax[$i]))
							{
								$GLOBALS["xajax"]->registerExternalFunction($funciones_ajax[$i],$configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/include/funciones_ajax.class.php",XAJAX_POST);
								$i++;
							}
						}
						else
						{
							include_once($configuracion["raiz_documento"].$configuracion["clases"]."/xajax/include/".$_REQUEST["xajax_file"].".class.php");
							while(isset($funciones_ajax[$i]))
							{
								$GLOBALS["xajax"]->registerExternalFunction($funciones_ajax[$i],$configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/include/".$_REQUEST["xajax_file"].".class.php",XAJAX_POST);
								//$GLOBALS["xajax"]->registerFunction($funciones_ajax[$i],$configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/include/".$_REQUEST["xajax_file"].".class.php",XAJAX_POST);
								$i++;
							}
						}
						
						
						
						$GLOBALS["xajax"]->processRequests();
						$GLOBALS["xajax"]->printJavascript($configuracion["host"].$configuracion["site"].$configuracion["clases"]."/xajax/");
					}



					$this->html_pagina.="<html>\n";
					$this->html_pagina.="<head>\n";
					$this->html_pagina.="<title>".$configuracion['titulo']."</title>\n";
					$this->html_pagina.="<meta http-equiv='Expires' content='0' />\n";
					$this->html_pagina.="<meta http-equiv='Pragma' content='no-cache' />\n";
					$this->html_pagina.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n";
					$this->html_pagina.="<link rel='shortcut icon' href='".$configuracion["host"].$configuracion["site"]."/"."favicon.png' />\n";
					$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/".$this->estilo."/estilo.php' />\n";
					$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/funciones.js' type='text/javascript' language='javascript'></script>\n";
					$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/textarea.js"."'></script>\n";
					$this->html_pagina.="<!--[if lt IE 7.]>\n";
					$this->html_pagina.="<script defer type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/pngfix.js'></script>\n";
					$this->html_pagina.="<![endif]-->\n";
										
					//Para las paginas que tienen georeferenciacion
					if(isset($_REQUEST["googlemaps"]))
					{
						$this->html_pagina.="<script type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/googlemaps.js"."'></script>";
						$this->html_pagina.="<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$configuracion["googlemaps"]."' type='text/javascript'></script>";
					}
					
					
					$this->html_pagina.="</head>\n";
					$this->html_pagina.="<body leftMargin='0' topMargin='0' class='fondoprincipal'";
					if(isset($_REQUEST["googlemaps"]))
					{
						$this->html_pagina.="onload='load()' onunload='GUnload()'";
					}
					$this->html_pagina.=">\n";
					
					if($this->id_pagina=='index' || $this->id_pagina=='registro_exito'||$this->id_pagina=='logout_exito'||$this->id_pagina=='index_no_usuario')
					{
						$this->html_pagina.="<table width='".$this->tamanno."' align='center' cellspacing='0' border='0' cellpadding='0'>\n";
					}
					else
					{
						$this->html_pagina.="<table width='".$this->tamanno."' align='center' cellspacing='0' border='0' cellpadding='0' class='tabla_general'>\n";
					}

					$this->html_pagina.="<noscript>
                                            <table width='100%' align='center' cellspacing='0' border='0' cellpadding='0' >
                                                    <tr>
                                                        <td bgcolor='#F4FA58' align='center'>
                                                            <img src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/error.png"."' width='35' heigth='35'
                                                        </td>
                                                        <td bgcolor='#F4FA58' align='center'>
                                                            <p><h5>La página que esta viendo requiere para su funcionamiento el uso de JavaScript. Si lo ha deshabilitado intencionalmente, por favor vuelva a activarlo.</h5></p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <META HTTP-EQUIV='Refresh' CONTENT='0;URL=".$configuracion["host"].$configuracion["site"].$configuracion["clases"]."/indexNoJavascript.html'>
                                                </noscript>";
					$this->html_pagina.="<tbody>\n";
					
					$this->html_pagina.="<tr>\n";
					echo $this->html_pagina;
					$this->html_pagina="";
					$secciones=$this->ancho_seccion($this->cadena_sql,$configuracion);
					$this->armar_seccion('A',$this->cadena_sql,$configuracion,$GLOBALS["fila"],$GLOBALS["tab"],$secciones);
					$this->html_pagina.="</tr>\n";
					echo $this->html_pagina;
					
					$this->html_pagina="<tr>\n";
					echo $this->html_pagina;
					$this->armar_seccion('B',$this->cadena_sql,$configuracion,$GLOBALS["fila"],$GLOBALS["tab"],$secciones);
					$this->armar_seccion('C',$this->cadena_sql,$configuracion,$GLOBALS["fila"],$GLOBALS["tab"],$secciones);
					$this->armar_seccion('D',$this->cadena_sql,$configuracion,$GLOBALS["fila"],$GLOBALS["tab"],$secciones);
					$this->html_pagina="</tr>\n";
					echo $this->html_pagina;
					
					$this->html_pagina="<tr>\n";
					echo $this->html_pagina;
					$this->armar_seccion('E',$this->cadena_sql,$configuracion,$GLOBALS["fila"],$GLOBALS["tab"],$secciones);
					$this->html_pagina="</tr>\n";
					$this->html_pagina.="</tbody>\n";
					$this->html_pagina.="</table>\n";
					echo $this->html_pagina;
					
					$this->html_pagina="<script language='JavaScript' type='text/javascript' src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tooltip.js'></script>";
					$this->html_pagina.="</body>\n";
					$this->html_pagina.="</html>\n";
					echo $this->html_pagina;				
				}
				else
				{
					$this->armar_no_pagina('C',$this->cadena_sql,$configuracion);
				}
			}
		
		
		}
		
	}
	
	function procesar_pagina($configuracion)
	{
		include_once($configuracion["raiz_documento"].$configuracion["bloques"]."/".$_REQUEST['action']."/bloque.php");		
	}
	
	function ancho_seccion($cadena,$configuracion)
	{
		$secciones=array("B","C","D");
		
		$la_seccion=array();
		foreach ($secciones as $key => $value) 
		{
			$this->la_cadena=$cadena." ";
			$this->la_cadena.="AND ";
			$this->la_cadena.=$configuracion["prefijo"]."bloque_pagina.seccion='".$value."' ";
			$this->la_cadena.="LIMIT 1 ";
			//echo $this->la_cadena;
			$this->base->registro_db($this->la_cadena,0);
			$this->armar_registro=$this->base->obtener_registro_db();
			$this->total=$this->base->obtener_conteo_db();
			if($this->total>0)
			{
				$la_seccion[$value]=1;
			
			}
		}
		return $la_seccion;
	}
	
	function armar_seccion($seccion,$cadena,$configuracion,$fila,$tab,$secciones)
	{
		$this->la_cadena=$cadena.' AND '.$configuracion["prefijo"].'bloque_pagina.seccion="'.$seccion.'" ORDER BY '.$configuracion["prefijo"].'bloque_pagina.posicion ASC';
		$this->base->registro_db($this->la_cadena,0);
		$this->armar_registro=$this->base->obtener_registro_db();
		$this->total=$this->base->obtener_conteo_db();
		if($this->total>0)
		{
			$ancho="";
			if($seccion=='B')
			{
				$ancho=$this->tamanno*(0.2);

			}
			else
			{
				if($seccion=='D')
				{
					$ancho=$this->tamanno*(0.2);;
				}
			}
			
			if($seccion=='B'||$seccion=='D')
			{
				if(!isset($secciones["C"]))
				{
					echo "<td valign='top' class='seccion_colapsada'>\n";				
				}
				else
				{
					echo "<td valign='top' class='seccion_".$seccion."'>\n";	
				}
				
			}
			else
			{
				if($seccion=='C')
				{
					if(isset($secciones["B"]) && isset($secciones["D"]))
					{
						echo "<td valign='top' class='seccion_".$seccion."'>\n";											
					}
					else
					{
						if(isset($secciones["B"]) || isset($secciones["D"]))
                                                {
                                                    echo "<td valign='top' class='seccion_C_colapsada'>\n";
                                                }
                                                else
                                                {
                                                    echo "<td valign='top' class='seccion_C_colapsada2'>\n";

                                                }
					}
				}
				else
				{
					echo "<td colspan='3' valign='top' width='100%'>\n";
				}	
				
			
			}			
						
			
			for($this->contador=0;$this->contador<$this->total;$this->contador++)
			{
				
				$this->id_bloque=$this->armar_registro[$this->contador][0];
				$this->incluir=$this->armar_registro[$this->contador][4];
				include($configuracion["raiz_documento"].$configuracion["bloques"]."/".$this->incluir."/bloque.php");
				
				
			}
			
			echo "</td>\n";		
		}
		$GLOBALS["fila"]=$fila;
		$GLOBALS["tab"]=$tab;
		return TRUE;	
		
	}
	//Fin del metodo armar_seccion	
	
	function armar_no_pagina($seccion,$cadena,$configuracion)
	{
		$this->la_cadena=$cadena.' AND '.$configuracion["prefijo"].'bloque_pagina.seccion="'.$seccion.'" ORDER BY '.$configuracion["prefijo"].'bloque_pagina.posicion ASC';
		$this->base->registro_db($this->la_cadena,0);
		$this->armar_registro=$this->base->obtener_registro_db();
		$this->total=$this->base->obtener_conteo_db();
		if($this->total>0)
		{
						
			
			for($this->contador=0;$this->contador<$this->total;$this->contador++)
			{
				
				$this->id_bloque=$this->armar_registro[$this->contador][0];
				$this->incluir=$this->armar_registro[$this->contador][4];
				include($configuracion["raiz_documento"].$configuracion["bloques"]."/".$this->incluir."/bloque.php");
				
				
			}
			
		
		}
		return TRUE;	
		
	}
}



?>
