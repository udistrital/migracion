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
class bloque_registroInconsistenciasEstudiantes extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_registroInconsistenciasEstudiantes($configuracion);
		$this->sql=new sql_registroInconsistenciasEstudiantes();
	}
	
	
	function html($configuracion)
	{
		//$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
//			var_dump($_REQUEST);
//                        echo "<br>usuario".$usuario;
//                        exit;
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                    
                        case "seleccionarPlan":
				$this->funcion->seleccionPlan($configuracion);
				break;

                        case "seleccionar":
				$this->funcion->seleccion($configuracion);
				break;

			case "planEstudios":
				$this->funcion->planEstudios($configuracion);
				break;

			case "registrados":
				$this->funcion->registrados($configuracion);
				break;
			
			case "asignaturas":
				$this->funcion->asignaturas($configuracion);
				break;

			default:
				$this->funcion->seleccionPlan($configuracion);
				break;	
		}
	}
	
	
	function action($configuracion)
	{
                switch($_REQUEST['opcion'])
		{
			                            
                        case "seleccionar":
				
				unset($_REQUEST['action']);
					
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroInconsistenciasEstudiantes";
				$variable.="&opcion=seleccionar";
				$variable.="&planEstudio=".$_REQUEST['planEstudio'];

				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
				
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


                        case "planEstudios":

				unset($_REQUEST['action']);

				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroInconsistenciasEstudiantes";
				$variable.="&opcion=planEstudios";
				$variable.="&planEstudio=".$_REQUEST['planEstudio'];


				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "registrados":

				unset($_REQUEST['action']);

				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroInconsistenciasEstudiantes";
				$variable.="&opcion=registrados";
				$variable.="&planEstudio=".$_REQUEST['planEstudio'];


				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "asignaturas":

				unset($_REQUEST['action']);

				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroInconsistenciasEstudiantes";
				$variable.="&opcion=asignaturas";
				$variable.="&planEstudio=".$_REQUEST['planEstudio'];


				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "seleccionarPlan":
				$this->funcion->seleccionPlan($configuracion);
				break;


		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroInconsistenciasEstudiantes($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>