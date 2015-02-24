<?php

	if(!isset($GLOBALS["autorizado"]))
	{
		include("../index.php");
		exit;		
	}
	
	include_once("funcion.class.php");
	
	class bloque_reciboAceptaAcuerdo
	{

		public function __construct($configuracion){
			array_walk($_REQUEST, array($this,'secureSuperGlobalREQUEST'));
			$this->funcion=new funciones_admin_reciboAceptaAcuerdo($configuracion);
		}
		
		public function jxajax($configuracion){

		}
		
		public function  html($configuracion){
			$carreras=$this->funcion->pintarAcuerdo();	

		}
		
		public function  action($configuracion){
			$this->funcion->insertarCompromiso($_REQUEST['opcion']);
		}
		
		public function secureSuperGlobalREQUEST(&$value,$key)
		{
			$_REQUEST[$key] = htmlspecialchars(stripslashes($_REQUEST[$key]));
			$_REQUEST[$key] = str_ireplace("script", "blocked", $_REQUEST[$key]);
			$_REQUEST[$key] = str_ireplace("select", "blocked", $_REQUEST[$key]);
			return $_REQUEST[$key];
		}
	
	}

	$esteBloque=new bloque_reciboAceptaAcuerdo($configuracion);

	
	if(!isset($_REQUEST['jxajax'])){
		if(!isset($_REQUEST['action'])){
			$esteBloque->html($configuracion);
		}else{
			$esteBloque->action($configuracion);
		}
	}else{
		$esteBloque->jxajax($configuracion);
	}

	
	