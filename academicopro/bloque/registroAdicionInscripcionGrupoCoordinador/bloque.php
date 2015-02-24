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
class bloque_registroAdicionInscripcionGrupoCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroAdicionInscripcionGrupoCoordinador();
 		$this->funcion=new funciones_registroAdicionInscripcionGrupoCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "cuadroRegistro":
						$this->funcion->cuadroRegistro($configuracion);
						break;

					case "varios":
						$this->funcion->variosEstudiantes($configuracion);
						break;

					case "estudianteRegistrar":
						$this->funcion->registrarEstudiante($configuracion);
						break;

					case "registrarVarios":
						$this->funcion->registrarVarios($configuracion);
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

                        case "varios":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminConsultarInscripcionGrupoCoordinador";
				$variable.="&opcion=varios";
                                $variable.="&nroEstudiantes=".$_REQUEST["nroEstudiantes"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nroGrupo=".$_REQUEST["nroGrupo"];
                                $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "estudianteRegistrar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionInscripcionGrupoCoordinador";
				$variable.="&opcion=estudianteRegistrar";
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nroGrupo=".$_REQUEST["nroGrupo"];
                                $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "registrarVarios":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionInscripcionGrupoCoordinador";
				$variable.="&opcion=registrarVarios";
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nroGrupo=".$_REQUEST["nroGrupo"];
                                $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&nroEstudiantes=".$_REQUEST["nroEstudiantes"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
//var_dump($_REQUEST);exit;
                                $total=$_REQUEST['nroEstudiantes']+1;
                                for($i=1;$i<$total;$i++)
                                {
                                    $variable.="&codEstudiante-".$i."=".$_REQUEST['codEstudiante-'.$i];

                                }
                                //var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroAdicionInscripcionGrupoCoordinador($configuracion);
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