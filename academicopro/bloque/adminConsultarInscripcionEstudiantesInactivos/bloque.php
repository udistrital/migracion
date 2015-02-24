<?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_adminConsultarInscripcionEstudiantesInactivos extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminConsultarInscripcionEstudiantesInactivos();
 		$this->funcion=new funciones_adminConsultarInscripcionEstudiantesInactivos($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "verProyectos":
						$this->funcion->verProyectos($configuracion);
						break;
                                            
					case "reporte":
						$this->funcion->seleccionarEstado($configuracion);
                                                break;

                                        default:
                                                $this->funcion->verProyectos($configuracion);
                                            break;
                                }
			}
                 
			


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{

                        case "cancelarEstado":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarInscripcionEstudiantesInactivos";
				$variable.="&opcion=cancelar";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&id_estado=".$_REQUEST["id_estado"];
                                
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminConsultarInscripcionEstudiantesInactivos($configuracion);
//echo $_REQUEST['action'];
if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action($configuracion);
	}
}


?>