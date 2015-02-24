<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    11/03/2014
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
class bloque_adminGenerarActa extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{	//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		//$this->tema=$tema;
                $this->configuracion=$configuracion;
		$this->funcion=new funcion_adminGenerarActa($configuracion);
		$this->sql=new sql_adminGenerarActa($configuracion);
                
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
			
                        case "revisarDatos":
				$this->funcion->revisarDatos($_REQUEST['tipoActa']);
				break;
			case "generarActa":
				$this->funcion->generarActa($_REQUEST['tipoActa']);
				break;
			case "copiaActaGrado":
			        $this->funcion->mostrarFormularioActa('copiaActaGrado');
				break;	
			case "actaGrado":
			        $this->funcion->mostrarFormularioActa('actaGrado');
				break;	
			default:
                                $this->funcion->mostrarFormularioActa();
				break;	
		}
	}
	
	
	function action()
        
	{	
            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
            $this->cripto=new encriptar();

            switch((isset($_REQUEST['opcion'])?$_REQUEST['opcion']:''))
		{
			
			case "consultarEstudiante":
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_generarActa";
				$variable.="&opcion=revisarDatos";
                                $variable.="&datoBusqueda=".$_REQUEST['datoBusqueda'];
                                $variable.="&tipoBusqueda=".$_REQUEST['tipoBusqueda'];
                                $variable.="&tipoActa=".$_REQUEST['tipoActa'];
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
			case "generar":
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="no_pagina=admin_generarActa";
				$variable.="&opcion=generarActa";
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&idenEstudiante=".$_REQUEST["idenEstudiante"];
                                $variable.="&tipoActa=".$_REQUEST['tipoActa'];
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
                 
			default:
				
				unset($_REQUEST['action']);	
					
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_generarActa";
                
                                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
				
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminGenerarActa($configuracion);
	

if(!isset($_REQUEST['action']))
{

	$esteBloque->html();
}
else
{

	$esteBloque->action();
}
?>