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
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloque_adminContarCupos extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminContarCupos($configuracion);
		$this->sql=new sql_adminContarCupos();
	}
	
	
	function html($configuracion)
	{
		//$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
			case "contar":
				$this->funcion->verProyectos($configuracion);
				break;

			case "verCupos":
				$this->funcion->contarCupos($configuracion);
				break;
			
			case "actualizar":
				$this->funcion->actualizarCupos($configuracion);
				break;

			
		}
	}
	
	
	function action($configuracion)
	{	switch($_REQUEST['opcion'])
		{
			case "actualizar":
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminContarCupos";
				$variable.="&opcion=actualizar";
                                $variable.="&cupoReal=".$_REQUEST["cupoReal"];
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

			case "verCupos":
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminContarCupos";
				$variable.="&opcion=verCupos";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminContarCupos($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>