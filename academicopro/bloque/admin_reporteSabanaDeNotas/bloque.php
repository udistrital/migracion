<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/06/2013
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
//Clase
class bloque_reporteSabanaDeNotas extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{	//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		//$this->tema=$tema;
                $this->configuracion=$configuracion;
		$this->funcion=new funcion_reporteSabanaDeNotas($configuracion);
		$this->sql=new sql_reporteSabanaDeNotas();
                
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
			case "mostrar":
				$this->funcion->mostrarFormSolicitudSabanaNotas();
				break;
                        case "revisarDatos"://echo "<br>paso informe";//exit;
				$this->funcion->revisarDatos($this->configuracion);
				break;
			case "generarSabana"://echo "<br>paso informe";//exit;
				$this->funcion->generarSabana($this->configuracion);
				break;
			default:
				break;	
		}
	}
	
	
	function action()
	{	switch((isset($_REQUEST['opcion'])?$_REQUEST['opcion']:''))
		{
			
			case "registrarEstudiante":
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_reporteSabanaDeNotas";
				$variable.="&opcion=revisarDatos";
//                                foreach ($_REQUEST['codEstudiante'] as $key => $value) {
//				$variable.="&codEstudiante[$key]=".$value;
//                                }
				$variable.="&datoBusqueda=".$_REQUEST["datoBusqueda"];
				$variable.="&tipoBusqueda=".$_REQUEST["tipoBusqueda"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&espacioSuperior=".$_REQUEST["espacioSuperior"];
				$variable.="&espacioIzquierda=".$_REQUEST["espacioIzquierda"];
				include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");

                                $this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
			case "generar":
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="no_pagina=admin_reporteSabanaDeNotas";
				$variable.="&opcion=generarSabana";
//                                foreach ($_REQUEST['codEstudiante'] as $key => $value) {
//				$variable.="&codEstudiante[$key]=".$value;
//                                }
				$variable.="&datoBusqueda=".$_REQUEST["datoBusqueda"];
				$variable.="&tipoBusqueda=".$_REQUEST["tipoBusqueda"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&espacioSuperior=".$_REQUEST["espacioSuperior"];
				$variable.="&espacioIzquierda=".$_REQUEST["espacioIzquierda"];
				include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");

                                $this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
			default:
				
				unset($_REQUEST['action']);	
					
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_reporteSabanaDeNotas";
				$variable.="&opcion=mostrar";
				$variable.="&datoBusqueda=".$_REQUEST["datoBusqueda"];
				$variable.="&tipoBusqueda=".$_REQUEST["tipoBusqueda"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
				
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_reporteSabanaDeNotas($configuracion);
	

if(!isset($_REQUEST['action']))
{

	$esteBloque->html();
}
else
{

	$esteBloque->action();
}


?>