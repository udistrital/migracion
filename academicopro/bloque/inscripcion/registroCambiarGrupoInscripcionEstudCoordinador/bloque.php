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
class bloque_registroCambiarGrupoInscripcionEstudCoordinador extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCambiarGrupoInscripcionEstudCoordinador();
 		$this->funcion=new funciones_registroCambiarGrupoInscripcionEstudCoordinador($configuracion, $this->sql);
                $this->configuracion=$configuracion;
	}

	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "buscar":
						$this->funcion->buscarGrupos();
						break;

                                        case "otrosGrupos":
						$this->funcion->buscarOtrosGrupos();
						break;

                                        case "cambiar":
						$this->funcion->cambiarGrupo();
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
                        case "cambiar":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoInscripcionEstudCoordinador";
				$variable.="&opcion=cambiar";
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variable.="&proyecto=".(isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'');
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                $variable.="&grupoAnterior=".$_REQUEST["grupoAnterior"];

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCambiarGrupoInscripcionEstudCoordinador($configuracion);

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