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
class bloque_adminConsultarPlanEstudioCoordinador extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminConsultarPlanEstudioCoordinador($configuracion);
		$this->sql=new sql_adminConsultarPlanEstudioCoordinador();
	}
	
	
	function html($configuracion)
	{
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "menuCoordinador":
                                $this->funcion->menuCoordinador($configuracion);
                        break;

                        case "verProyectos":
                                $this->funcion->verProyectos($configuracion);
                        break;
                    
			case "mostrar":
				$this->funcion->mostrarRegistro($configuracion);
			break;

                        case "mostrarOtroPlan":
				$this->funcion->mostrarRegistroOtroPlan($configuracion,$_REQUEST['planEstudio']);
			break;

                        case "ver":
				$this->funcion->verRegistro($configuracion);
			break;

                        case "buscarPlan":
				$this->funcion->buscarEnfasis($configuracion,$_REQUEST['proyecto'],$_REQUEST['nombreProyecto']);
			break;

			default:
				$this->funcion->mostrarRegistro($configuracion);
			break;
		}#Cierre de funcion html
	}
	
	
	function action($configuracion)
	{

            switch($_REQUEST['opcion'])
		{
                        case "proyectos":

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarPlanEstudioCoordinador";
                            $variable.="&opcion=mostrar";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;

                        

				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminConsultarPlanEstudioCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>