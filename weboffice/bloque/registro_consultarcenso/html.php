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

$estaPagina="consultarCenso";

include ($configuracion["raiz_documento"].$configuracion["estilo"]."/".$this->estilo."/tema.php");

$estilo=$this->estilo;

$formulario="registro_".$estaPagina;
$verificar="control_vacio(".$formulario.",'codigo')";

$acceso_db=new dbms($configuracion);
$enlace=$acceso_db->conectar_db();

if (is_resource($enlace))
{
	if(isset($_REQUEST['opcion']))
	{
		$accion=$_REQUEST['opcion'];
		
		if($accion=="mostrar")
		{
			
			if(isset($_REQUEST['registro']))
			{
				mostrar_registro($configuracion,$tema,$_REQUEST['registro'], $acceso_db, $formulario);
			}
		}
		else
		{
			
			if($accion=="nuevo")
			{
				nuevo_registro($configuracion,$tema,$accion,$formulario,$verificar,1,1,$estilo,$acceso_db);
			
			}
			else
			{
				if($accion=="editar")
				{
					editar_registro($configuracion,$tema,$_REQUEST['registro'], $acceso_db, $formulario);
				
				}
				else
				{
					if($accion=="corregir")
					{
						corregir_registro($configuracion,$tema,$accion,$formulario,$verificar,$fila,$tab);
					
					}
				}		
			}
			
		
		}
	}
	else
	{
		$accion="nuevo";
		nuevo_registro($configuracion,$tema,$accion,$formulario,$verificar,1,1,$estilo,$acceso_db);
	}
}




?>