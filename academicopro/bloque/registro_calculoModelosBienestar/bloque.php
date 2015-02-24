<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
 0.0.0.1    Maritza Callejas    05/09/2013
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
class bloque_registroCalculoModelosBienestar extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{	//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		//$this->tema=$tema;
                $this->configuracion=$configuracion;
		$this->funcion=new funcion_registroCalculoModelosBienestar($configuracion);
		$this->sql=new sql_registroCalculoModelosBienestar();
                
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
			
                        case "consultarDatos":
				$this->funcion->consultarDatos();
				break;
			
                        case "consultaProyectos":
				$this->funcion->consultaProyectos();
				break;
			
                        case "consultaEstudiantes":
				$this->funcion->consultaEstudiantes();
				break;
			
			default:
                                $this->funcion->mostrarFormulario();
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
				$variable="pagina=registro_calculoModelosBienestar";
				$variable.="&opcion=consultarDatos";
                                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
			
			case "consultaProyectos":
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=registro_calculoModelosBienestar";
				$variable.="&opcion=consultaProyectos";
                                $variable.="&facultad=".$_REQUEST['facultad'];
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
			
			case "consultaEstudiantes":
                                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
				$variable="pagina=registro_calculoModelosBienestar";
				$variable.="&opcion=consultaEstudiantes";
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;
			
			default:
				
				unset($_REQUEST['action']);	
					
				$pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registro_calculoModelosBienestar";
                
                                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
				
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCalculoModelosBienestar($configuracion);
	

if(!isset($_REQUEST['action']))
{

	$esteBloque->html();
}
else
{

	$esteBloque->action();
}
?>