<?
/*
##########################################
#    UNIVERSIDAD DISTRITAL Francisco Jose de Caldas                       
#    Copyright: Vea el archivo EULA.txt que viene con la distribucion  
##########################################
*/
/***************************************************************************
  
html.php 

Paulo Cesar Coronado
Copyright (C) 2001-2007

Última revisión 6 de Marzo de 2007

*****************************************************************************
* @subpackage   
* @package	bloques
* @copyright    
* @version      0.2
* @author      	Paulo Cesar Coronado
* @link		N/D
* @description  Formulario de registro de entidades
* @usage        Toda pagina tiene un id_pagina que es propagado por cualquier metodo GET, POST.
*******************************************************************************/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");
$estilo=$this->estilo;

$formulario="registro_tarjeton";
$verificar="control_vacio(".$formulario.",'eleccion')";

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();

if (is_resource($enlace))
{
	$_REQUEST['tipoTarjeton']=1;
	if(isset($_REQUEST['tipoTarjeton']))
	{
		
		armar_tarjeton($configuracion,$tema,$formulario,$verificar,$estilo,$acceso_db);
		
	}
	else
	{
		echo "<h3>Imposible Rescatar un Tarjet&oacute;n</h3>";
	}
}
/****************************************************************************************
*				Funciones						*
****************************************************************************************/

function armar_tarjeton($configuracion,$tema,$formulario,$verificar,$estilo,$acceso_db)
{
	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
	$cripto=new encriptar();
	$cadena_sql=cadenaSqlTarjeton($configuracion,"rescatarTarjeton",$_REQUEST['tipoTarjeton']);
	$registro=accesodbhtmlTarjeton($acceso_db, $cadena_sql);
	if(is_array($registro))
	{
		//$estructura=encontrarEstructura(count($registro), 4, 10);
		
		$i=0;
		$k=1;
		$foto=1;
		
		$cadenaTarjeton="<table class='tarjeton'>\n";
		$cadenaTarjeton.="<tr>\n";
			
		while(isset($registro[$i][0]))
		{
			if($i==2*$k)
			{
				$cadenaTarjeton.="<tr>\n";
				$k++;
			}
			$cadenaTarjeton.="<td class='posicionTarjeton'>\n";
			$cadenaTarjeton.=$i+1;
			$cadenaTarjeton.="</td>\n";
			$cadenaTarjeton.="<td>\n";
			$cadenaTarjeton.="<table class='normalTarjeton'>\n";
			$cadenaTarjeton.="<tr>\n";
			$cadenaTarjeton.="<td width='10%'>\n";
			if($foto==1)
			{
				$foto=2;
				$cadenaTarjeton.="<img border='0' alt='' width=60px src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/maradona.jpg' />";
			}
			else
			{
				$cadenaTarjeton.="<img border='0' alt='' width=60px src='".$configuracion["host"].$configuracion["site"].$configuracion["grafico"]."/pele.jpg' />";
				$foto=1;
			}
			$cadenaTarjeton.="</td>\n";
			$cadenaTarjeton.="<td>\n";
			$cadenaTarjeton.=$registro[$i][1]." ".$registro[$i][2]."<br>";
			$cadenaTarjeton.="ID. ".$registro[$i][3]."<br>";
			$cadenaTarjeton.="PLANCHA No ".$registro[$i][5]."<br>";
			$cadenaTarjeton.="</td>\n";
			$cadenaTarjeton.="</tr>\n";
			$cadenaTarjeton.="</table>\n";
			$cadenaTarjeton.="</td>\n";
			if($i==2*$k)
			{
				$cadenaTarjeton.="</tr>\n";
			}
			
			$i++;
		}
		$cadenaTarjeton.="</tr>\n";
		$cadenaTarjeton.="</table>\n";
		echo $cadenaTarjeton;
	
	}
	
}


function encontrarEstructura($cantidad, $maxColumnas, $maxFilas)
{
	$j=0;
	
	while($maxColumnas>1)
	{
		$resultado=$cantidad/$maxColumnas;
	
		$residuo=$cantidad%$maxColumnas;
		
		if($resultado>0)
		{
			$matriz[$j][0]=$maxColumnas;
			if($residuo==0)
			{
				$matriz[$j][1]=$resultado;
			}
			else
			{
				$matriz[$j][1]=$resultado+1;
			}
			
			$matriz[$j][2]=$maxColumnas-$residuo;
			$matriz[$j][3]=0;
		}
		else
		{
		
		
		}
		$maxColumnas--;
		$j++;
	}
	
	//Aplicar reglas
	$j=0;
	while(isset($matriz[$j][0]))
	{
		//1.Filas=columnas y no espacios
		if(($matriz[$j][0]==$matriz[$j][1])&&($matriz[$j][2]==0))
		{
			$matriz[$j][3]++;
		}
		
		//2. Filas = Columnas
		if(($matriz[$j][0]==$matriz[$j][1])&&($matriz[$j][2]!=0))
		{
			$matriz[$j][3]++;
		}
		$j++;
	}
	
	$j=0;
	
	while(isset($matriz[$j][0]))
	{
		
		$menorEspacios=$matriz[$j][2];
		
		$j++;
	}

}

function cadenaSqlTarjeton($configuracion,$tipo,$variable="")
{
	
	switch($tipo)
	{
		case "rescatarTarjeton":
			$cadena_sql="SELECT ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.`idCandidato`, ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.`nombre`, ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.`apellido`, ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.`identificacion`, ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.`tipo`, ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.`idPlancha`, ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.`renglon`, ";
			$cadena_sql.=$configuracion["prefijo"]."tarjeton.`posicion` ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$configuracion["prefijo"]."candidato, ";
			$cadena_sql.=$configuracion["prefijo"]."tarjeton ";
			$cadena_sql.="WHERE ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.tipo=".$variable." ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.renglon=1 ";
			$cadena_sql.="AND ";
			$cadena_sql.=$configuracion["prefijo"]."candidato.idPlancha=".$configuracion["prefijo"]."tarjeton.idPlancha ";
			$cadena_sql.="ORDER BY ".$configuracion["prefijo"]."tarjeton.`posicion` ASC ";
			
			break;
		default:
			break;
	}
	//echo $cadena_sql;
	return $cadena_sql;

}

function accesodbhtmlTarjeton($acceso_db, $cadena_sql)
{
	$total=$acceso_db->registro_db($cadena_sql,0);
	if($total>0)
	{
		$registro=$acceso_db->obtener_registro_db();
		return $registro;
	}
	else
	{
		return false;
	}	
}


?>