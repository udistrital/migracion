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
class bloque_registroInscripcionAgilEstudianteCoorPosgrado extends bloque
{
  private $configuracion;

  public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_registroInscripcionAgilEstudianteCoorPosgrado($configuracion);
            $this->funcion=new funcion_registroInscripcionAgilEstudianteCoorPosgrado($configuracion, $this->sql);

	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "validar":
						$this->funcion->validaciones();
						break;

                                        default:
                                                $this->funcion->validaciones();
                                            break;
                                }
			}
                 
			


	}

	function action()
	{
            switch($_REQUEST['opcion'])
		{

                        case "varios":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
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
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "estudianteRegistrar":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
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
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "registrarVarios":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
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
                                $total=$_REQUEST['nroEstudiantes']+1;
                                for($i=1;$i<$total;$i++)
                                {
                                    $variable.="&codEstudiante-".$i."=".$_REQUEST['codEstudiante-'.$i];

                                }
                                //var_dump($_REQUEST);exit;
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroInscripcionAgilEstudianteCoorPosgrado($configuracion);
//echo $_REQUEST['action'];
if(!isset($_REQUEST['action']))
{
	$esteBloque->html();
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action();
	}
}


?>