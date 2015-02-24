<?php

	if(!isset($GLOBALS["autorizado"]))
	{
		include("../index.php");
		exit;		
	}

	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/crud.class.php");
	include_once("funcion.class.php");
	
	class bloquePanelNotas
	{

		public function __construct($configuracion){
			
		}

		public function  html($configuracion){
			
		}
	}

	error_reporting(E_ALL);
	
	$esteBloque=new bloquePanelNotas($configuracion);

	$esteBloque->html($configuracion);

	
	