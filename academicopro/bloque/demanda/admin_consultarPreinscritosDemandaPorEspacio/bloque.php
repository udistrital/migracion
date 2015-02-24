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
class bloque_adminConsultarPreinscritosDemandaPorEspacio extends bloque
{

    
    public $configuracion;


    public function __construct($configuracion)
	{	
                $this->configuracion=$configuracion;
                //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		//$this->tema=$tema;
		$this->funcion=new funcion_adminConsultarPreinscritosDemandaPorEspacio($configuracion);
		$this->sql=new sql_adminConsultarPreinscritosDemandaPorEspacio($configuracion);
	}
	
	
	function html()
	{
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
			case "reporteEspacios":
				$this->funcion->mostrarListadoEspacios();
			break;
                    
                        case "consultarPorCodigo":
                                $this->funcion->mostrarEspaciosOpcionCodigo();
                        break;
                    
                        case "consultarPorNombre":
                                $this->funcion->mostrarEspaciosOpcionNombre();
                        break;                    
	                   
                        case "consultarListadoEstudiantes":
                                $this->funcion->mostrarListadoProyecto();
                        break;

                        default:
				$this->funcion->seleccionarEspacio();
			break;
					
			
		}#Cierre de funcion html
	}
	
	
	function action()
	{

            switch($_REQUEST['opcion'])
		{
                   
                        case "buscarCodigo":  
                            //var_dump($configuracion);exit;
                                $pagina= $this->configuracion["host"]. $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_consultarPreinscritosDemandaPorEspacio";
				$variable.="&opcion=consultarPorCodigo";
				$variable.="&codEspacio=".$_REQUEST["datosBusqueda"];
                                
                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable, $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;

                       case "buscarNombre":

                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=admin_consultarPreinscritosDemandaPorEspacio";
				$variable.="&opcion=consultarPorNombre";
				$variable.="&nombreEspacio=".$_REQUEST["datosBusqueda"];

                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;                       
				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminConsultarPreinscritosDemandaPorEspacio($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>