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
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");
//06/11/2012 Milton Parra: Se ajustan mensajes cuando faltan datos en las notas. Se coloca mensaje cuando no presenta Promedio Acumulado
//Clase
class bloque_reporteInterno extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{	//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		//$this->tema=$tema;
                $this->configuracion=$configuracion;
		$this->funcion=new funcion_reporteInterno($configuracion);
		$this->sql=new sql_reporteInterno();
                
	}
	
	
	function html()
	{
		$this->acceso_db=$this->funcion->conectarDB($this->configuracion, "");
		// @ Crear un objeto de la clase funcion
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
        switch($_REQUEST['opcion'])
		{
			case "informe":
				$this->funcion->generarInforme($this->configuracion);
				break;
			
			case "ingresar":
				$this->funcion->ingresarCodigo($this->configuracion);
				break;

                        case "generar":
                                $this->funcion->generarCodigo($this->configuracion);
				break;
			
			default:
				break;	
		}
	}
	
	
	function action()
	{	switch((isset($_REQUEST['opcion'])?$_REQUEST['opcion']:''))
		{
			case "nuevo":	
				$this->funcion->guardarEspacio($this->configuracion);
				break;
				
			case "ver":
				//$this->funcion->editarEspacio($configuracion);
				break;
			default:
				
				unset($_REQUEST['action']);	
					
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=reporte_interno";
				$variable.="&opcion=informe";
				$variable.="&codigo=".$_REQUEST['codigo'];
                                $variable.="&no_pagina=true";
                

                       include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
				
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_reporteInterno($configuracion);

	

if(!isset($_REQUEST['action']))
{

	$esteBloque->html();
}
else
{

	$esteBloque->action();
}


?>
