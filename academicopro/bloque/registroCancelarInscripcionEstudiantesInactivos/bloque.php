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
class bloque_registroCancelarInscripcionEstudiantesInactivos extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCancelarInscripcionEstudiantesInactivos();
 		$this->funcion=new funciones_registroCancelarInscripcionEstudiantesInactivos($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "cancelar":
						$this->funcion->verReporte($configuracion);
						break;

					case "cancelarEstudiantes":
						$this->funcion->cancelarEstudiantes($configuracion);
						break;

					case "cancelarAsignatura":
						$this->funcion->cancelarAsignatura($configuracion);
						break;

                                        default:
                                                $this->funcion->cuadroRegistro($configuracion);
                                            break;
                                }
			}
                 
			


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{

                        case "cancelarEstudiantes":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarInscripcionEstudiantesInactivos";
				$variable.="&opcion=cancelarEstudiantes";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&id_estado=".$_REQUEST["id_estado"];
                                $variable.="&totalEstudiantes=".$_REQUEST["totalEstudiantes"];

                                for($i=1;$i<$_REQUEST["totalEstudiantes"];$i++)
                                {
                                    if($_REQUEST["codEstudiante".$i]!=NULL)
                                    {
                                        $variable.="&codEstudiante".$i."=".$_REQUEST["codEstudiante".$i];
                                        $seleccionado++;
                                    }
                                }
                                $variable.="&seleccionado=".$seleccionado;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        
                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCancelarInscripcionEstudiantesInactivos($configuracion);
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