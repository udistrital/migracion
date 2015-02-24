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
class bloque_registroPreinscripcionDemandaPorEstudiante extends bloque
{
    public $configuracion;
    
	 public function __construct($configuracion)
	{
                $this->configuracion=$configuracion;
                //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		//$this->tema=$tema;
		$this->funcion=new funcion_registroPreinscripcionDemandaPorEstudiante($configuracion);
		$this->sql=new sql_registroPreinscripcionDemandaPorEstudiante();
	}
	
	
	function html()
	{
		$this->acceso_db=$this->funcion->conectarDB($this->configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "consultar":
                                $this->funcion->consultarEstudiante($this->configuracion);
                        break;

                        case "validar":
                                $this->funcion->validarEstudiante($this->configuracion);
                        break;

                        default:
                            $this->funcion->consultarEstudiante($this->configuracion);
                        break;
                       
		}#Cierre de funcion html
	}
	
	
	function action()
	{
//            echo $_REQUEST['opcion']."<br>";
//            echo $_REQUEST['action'];
//            exit;

            switch($_REQUEST['opcion'])
		{
                        case "validar":
                            $pagina=  $this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=admin_consultarPreinscripcionDemandaPorEstudiante";
                            $variable.="&opcion=validar";
                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;

                        

				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_registroPreinscripcionDemandaPorEstudiante($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>