<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          pagina.class.php
* @author        Paulo Cesar Coronado
* @revision      Última revisión 15 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		
* @package		clase
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		1.0.0.1
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Clase principal del framework. Gestiona la creacion de paginas
*
/*--------------------------------------------------------------------------------------------------------------------------*/
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
	}


	function tab($variable,$label){
		$this->newtab[$this->contador]=$variable;
		$this->newlabel[$this->contador]=$label;
		$this->contador++;

	}




	function armar_tabs($configuracion)
	{
	
 
		$this->html_pagina.="<link rel='shortcut icon' href='".$configuracion["host"].$configuracion["site"]."/"."favicon.ico' />\n";
		//$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/jquery.tab.css'  media='print, projection, screen' />\n";
		//$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/jquery-1.3.2.min.js' type='text/javascript' language='javascript'></script>\n";
		//$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/jquery.tab.js' type='text/javascript' language='javascript'></script>\n";
		$this->html_pagina.="<script src='".$configuracion["host"].$configuracion["site"].$configuracion["javascript"]."/simpletabs_1.1.js' type='text/javascript' language='javascript'></script>\n";		
		//$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/style.css' />\n";		
		$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/simpletabs.css' />\n";			
		
       		
       		/*$this->html_pagina.="<!-- Additional IE/Win specific style sheet (Conditional Comments) -->\n";
       		$this->html_pagina.="<!--[if lte IE 7]>\n";
		$this->html_pagina.="<link rel='stylesheet' type='text/css' href='".$configuracion["host"].$configuracion["site"].$configuracion["estilo"]."/jquery.tabs-ie.css' media='projection, screen' />\n";
       		$this->html_pagina.="<![endif]-->\n";*/
       		
       		/*
	      	$this->html_pagina.="<script type='text/javascript'>\n";
		$this->html_pagina.="    $(function() {\n";
		$this->html_pagina.="        $('#".$this->contenedor."').tabs();\n"; //{ fxFade: true, fxSpeed: 'fast' }
		$this->html_pagina.="    });\n";
		$this->html_pagina.="</script>\n";
		*/
		
	
		$this->html_pagina.="<tdiv class='simpleTabs'>"; //id='".$this->contenedor."'  
			$this->html_pagina.="<ul class='simpleTabsNavigation'>";
		
			$i=0;
		
			for($i=0;$i<$this->contador;$i++){
		
			       $this->html_pagina.="<li><a href='#'><span>".$this->newlabel[$i]."</span></a></li>"; //href='#tab-".$i."'
			       
			    /*   $contenido.="<div class='simpleTabsContent'>\n";  //id='tab-".$i."'
			       $contenido.=$this->newtab[$i];
			       $contenido.="</div>\n";		*/       
    
	  			
		
			}
		
			$this->html_pagina.="</ul>";
		
			$i=0;
		
			for($i=0;$i<$this->contador;$i++){
		
			 			       
			       $this->html_pagina.="<tdiv class='simpleTabsContent'>\n";  //id='tab-".$i."'
			       $this->html_pagina.=$this->newtab[$i];
			       $this->html_pagina.="</tdiv>\n";		       
    
	  			
		
			}
			
			
		$this->html_pagina.=$contenido;
		$this->html_pagina.="</div>";
		
		echo $this->html_pagina;
	
	       		

	}


}



?>
