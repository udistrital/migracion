<?php

class tabs
{
	var $newtab;	
	var $newlabel;	
	
	 public function __construct($configuracion)
	{
		$this->newtab=array();
		$this->newlabel=array();
		$this->contenedor="Contenedor-1";
		$this->contador=0;
		$this->addJquery=true;
	}


	function tab($variable,$label){
		$this->newtab[$this->contador]=$variable;
		$this->newlabel[$this->contador]=$label;
		$this->contador++;

	}


	function armar_acordeon($configuracion)
	{
	
 
		$this->html_pagina.="<meta http-equiv='Content-Type'content='text/html; charset=iso-8859-1' />\n";
		$this->html_pagina.="<link type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tabs/css/condor/jquery-ui-1.8.custom.css'  rel='stylesheet'  />\n";		
		$this->html_pagina.="<script type='text/javascript'  src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tabs/jquery-1.4.2.min.js' ></script>\n";		
		$this->html_pagina.="<script type='text/javascript'  src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tabs/jquery-ui-1.8.custom.min.js' ></script>\n";
		
		
		$this->html_pagina.='<script type="text/javascript">';
		$this->html_pagina.='	$(function(){';
		$this->html_pagina.='	$("#accordion").accordion({ header: "h3" });';
		$this->html_pagina.='	});';
		$this->html_pagina.='</script>';
		
		$this->html_pagina.='<style type="text/css">';
		$this->html_pagina.='	body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}';
		$this->html_pagina.='	.demoHeaders { margin-top: 2em; }';
		$this->html_pagina.='</style>';
		
		
       		$this->html_pagina.="\n<div id='accordion'>";
		
			$i=0;

			for($i=0;$i<$this->contador;$i++){
		
			    $this->html_pagina.="\n<div><h3><a href='#'>".$this->newlabel[$i]."</a></h3>"; //href='#tab-".$i."'
			       
			    $this->html_pagina.="<div>\n";  //id='tab-".$i."'
			    $this->html_pagina.=$this->newtab[$i];
			    $this->html_pagina.="</div>\n";   
			    $this->html_pagina.="\n</div>\n";   
    
	  			
		
			}
		
			 			       
  
    
		
		$this->html_pagina.="</div>";
		
		return $this->html_pagina;
	
	       		

	}
	
	

	function armar_tabs($configuracion,$name="")
	{

		$this->html_pagina.="<meta http-equiv='Content-Type'content='text/html; charset=iso-8859-1' />\n";
		$this->html_pagina.="<link type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tabs/css/condor/jquery-ui-1.8.20.custom.css'  rel='stylesheet'  />\n";		
		if($this->addJquery<>false){
			$this->html_pagina.="<script type='text/javascript'  src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tabs/jquery-1.4.2.min.js' ></script>\n";	
		}
		$this->html_pagina.="<script type='text/javascript'  src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tabs/jquery-ui-1.8.custom.min.js' ></script>\n";
	
		
		
		$this->html_pagina.='<script type="text/javascript">';
		$this->html_pagina.='	$(function(){';
		$this->html_pagina.="	$('#tabs".$name."').tabs();";
		$this->html_pagina.='	});';
		$this->html_pagina.='</script>';
		
		$this->html_pagina.='<style type="text/css">';
		$this->html_pagina.='	body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 10px;}';
		$this->html_pagina.='	.demoHeaders { margin-top: 2em; }';
		$this->html_pagina.='</style>';
		
		
       	$this->html_pagina.="<div id='tabs".$name."'>";
		$this->html_pagina.="<ul>";
		
			$i=0;
			$contenido="";
			for($i=0;$i<$this->contador;$i++){
		
			    $this->html_pagina.="<li><a href='#".$name.$i."'><span>".$this->newlabel[$i]."</span></a></li>"; //href='#tab-".$i."'
			       
			    $contenido.="<div name='".$i."' id='".$name.$i."'>\n";  //id='tab-".$i."'
			    $contenido.=$this->newtab[$i];
			    $contenido.="</div>\n";   
    	
			}
		
			$this->html_pagina.="</ul>";
		
	
			 			       
		       $this->html_pagina.=$contenido;  
	       
    
		
			$this->html_pagina.="</div>";

		return $this->html_pagina;
	
	       		

	}	

	function armar_tabs_tmp($configuracion)
	{
	
 	include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");

		$cripto=new encriptar();

		$indice=$configuracion["host"].$configuracion["site"]."/index.php?";
	
			
		$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/tabs.js' type='text/javascript' language='javascript'></script>\n";			
		$this->html_pagina.="<link rel='shortcut icon' href='".$configuracion["host"].$configuracion["site"]."/"."favicon.ico' />\n";
		$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/simpletabs.css' />\n";			
		
       		$this->html_pagina.="<tdiv>";
			$this->html_pagina.="<ul class='simpleTabsNavigation'>";
		
			$i=0;
			$j=0;
			
			$this->pagina='admin_actualiza_datos';
			
			for($i;$i<$this->contador;$i++){
				
				$variable="pagina=".$this->pagina;
				$variable.="&tdiv=".$i;
				$variable=$cripto->codificar_url($variable,$configuracion);
			
			    $this->html_pagina.="<li><a onclick=cambiar('".$i."')><span>".$this->newlabel[$i]."</span></a></li>";
			}
			
			$this->html_pagina.="</ul>";
			
			$contenido=$_REQUEST['tdiv'];
				

			for($j;$j<$this->contador;$j++){
				
				$contenido.="<tdiv style='display:none;' name='".$j."'  id='".$j."'>\n"; 
				$contenido.=$this->newtab[$j];
				$contenido.="</tdiv>\n";
			}
	  			
		
			 			       
		       $this->html_pagina.=$contenido;
	       
    
		
			$this->html_pagina.="</tdiv>";
		
		return $this->html_pagina;
	
	       		

	}

	function armar_tabs_pest($configuracion)
	{
	
 
		$this->html_pagina.="<link rel='shortcut icon' href='".$configuracion["host"].$configuracion["site"]."/"."favicon.ico' />\n";
		$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/simpletabs_1.1.js' type='text/javascript' language='javascript'></script>\n";		
		$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/simpletabs.css' />\n";			
		
       		$this->html_pagina.="<tdiv class='simpleTabs'>";
			$this->html_pagina.="<ul class='simpleTabsNavigation'>";
		
			$i=0;

			for($i=0;$i<$this->contador;$i++){
		
			    $this->html_pagina.="<li><a href='#'><span>".$this->newlabel[$i]."</span></a></li>"; //href='#tab-".$i."'
			       
			    $contenido.="<tdiv name='".$i."' id='".$i."' class='simpleTabsContent'>\n";  //id='tab-".$i."'
			    $contenido.=$this->newtab[$i];
			    $contenido.="</tdiv>\n";   
    
	  			
		
			}
		
			$this->html_pagina.="</ul>";
		
	
			 			       
		       $this->html_pagina.=$contenido;  
	       
    
		
			$this->html_pagina.="</tdiv>";
		
		return $this->html_pagina;
	
	       		

	}

}



?>
