<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");

//Clase
class bloqueNavega extends bloque
{
	//@Método constructor donde se crea un objeto funcion de la clase admin_noticia y un objeto sql de la clase sql_adminNoticia 
	 public function __construct($configuracion)
	{
		
	}
	
	function action($configuracion)
	{	
		$totalVariable="&pagina=".$_REQUEST['pagina'];
		if(isset($_REQUEST['modulo']))
			{
				$totalVariable.="&modulo=".$_REQUEST['modulo'];
			}
		$totalVariable.="&opcion=".$_REQUEST['opcion'];
		$totalVariable.="&id_tipo=".$_REQUEST['id_tipo'];
		$totalVariable.="&hoja=".$_REQUEST['ir_hoja'];
				
		include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
		$cripto=new encriptar();
		$totalVariable=$cripto->codificar($totalVariable,$configuracion);
		$indice=$configuracion["host"].$configuracion["site"]."/index.php?index=";
		echo "<script>location.replace('".$indice.$totalVariable."')</script>";     
	
	}
	
	
}


// @ Crear un objeto bloque especifico

$this->esteBloque=new bloqueNavega($configuracion);

if(!isset($_REQUEST['action']))
{
	//Error
}
else
{
	$this->esteBloque->action($configuracion);
}


?>