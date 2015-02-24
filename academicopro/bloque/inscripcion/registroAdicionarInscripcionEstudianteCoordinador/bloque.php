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
class bloque_registroAdicionarInscripcionEstudianteCoordinador extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
 		$this->sql=new sql_registroAdicionarInscripcionEstudianteCoordinador();
 		$this->funcion=new funciones_registroAdicionarInscripcionEstudianteCoordinador($configuracion, $this->sql);
                $this->configuracion=$configuracion;
	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "espacios":
						$this->funcion->consultarEspaciosPermitidos();
						break;

					case "electivas":
						$this->funcion->consultarElectivasPermitidos();
						break;

					case "adicionar":
						$this->funcion->buscarGrupo();
						break;

                                        case "validar":
						$this->funcion->verificarCancelacion();
						break;

                                        case "inscribir":
						$this->funcion->inscribirCredito();
						break;

                                        case "otrosGrupos":
						$this->funcion->buscarOtrosGrupos();
						break;


				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->nuevoRegistro();
			}


	}

	function action()
	{
            switch($_REQUEST['opcion'])
		{

                        case "adicionar":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
				$variable.="&opcion=adicionar";
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&nivel=".$_REQUEST["nivel"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&carrera=".$_REQUEST["carrera"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                                $variable.="&nombre=".$_REQUEST["nombre"];
                                $variable.="&estado_est=".$_REQUEST["estado_est"];
//var_dump($_REQUEST);exit;
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "validar":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
				$variable.="&opcion=validar";
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&nivel=".$_REQUEST["nivel"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&carrera=".$_REQUEST["carrera"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                                $variable.="&nombre=".$_REQUEST["nombre"];
                                $variable.="&estado_est=".$_REQUEST["estado_est"];
//var_dump($_REQUEST);exit;
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "inscribir":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarInscripcionEstudianteCoordinador";
				$variable.="&opcion=inscribir";
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&nivel=".$_REQUEST["nivel"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&carrera=".$_REQUEST["carrera"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&creditosInscritos=".$_REQUEST['creditosInscritos'];
                                $variable.="&estado_est=".$_REQUEST['estado_est'];

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroAdicionarInscripcionEstudianteCoordinador($configuracion);
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