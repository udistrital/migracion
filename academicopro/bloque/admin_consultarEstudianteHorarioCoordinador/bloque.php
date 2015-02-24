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
class bloque_adminConsultarEstudianteHorarioCoordinador extends bloque
{
    private $configuracion;

    public function __construct($configuracion)
	{	//include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		//$this->tema=$tema;
                $this->configuracion=$configuracion;
		$this->funcion=new funcion_adminConsultarEstudianteHorarioCoordinador($configuracion);
		$this->sql=new sql_adminConsultarEstudianteHorarioCoordinador();
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
                                $this->funcion->consultarEstudiante();
                        break;

                        case "validar":
                                $this->funcion->validarEstudiante();
                        break;

                        default:
                            $this->funcion->consultarEstudiante();
                        break;
                       
		}#Cierre de funcion html
	}
	
	
	function action()
	{
            switch($_REQUEST['opcion'])
		{
                        case "validar":

                            $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                            $variable="pagina=admin_consultarEstudianteHorarioCoordinador";
                            $variable.="&opcion=validar";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            //$variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;

                        

				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminConsultarEstudianteHorarioCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>