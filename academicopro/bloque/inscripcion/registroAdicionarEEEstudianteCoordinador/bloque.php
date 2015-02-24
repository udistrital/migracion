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
class bloque_registroAdicionarEEEstudianteCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroAdicionarEEEstudianteCoordinador();
 		$this->funcion=new funciones_registroAdicionarEEEstudianteCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "espacios":
						$this->funcion->consultarEspaciosPermitidos($configuracion);
						break;

					case "electivas":
						$this->funcion->consultarElectivasPermitidos($configuracion);
						break;

					case "adicionar":
						$this->funcion->buscarGrupo($configuracion);
						break;

                                        case "inscribir":
						$this->funcion->inscribirCredito($configuracion);
						break;

                                        case "otrosGrupos":
						$this->funcion->buscarOtrosGrupos($configuracion);
						break;


				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->nuevoRegistro($configuracion);
			}


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{

                        case "adicionar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEEEstudianteCoordinador";
				$variable.="&opcion=adicionar";
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&carrera=".$_REQUEST["carrera"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&nombre=".$_REQUEST["nombre"];
                                $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "inscribir":
                       
				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEEEstudianteCoordinador";
				$variable.="&opcion=inscribir";
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&carrera=".$_REQUEST["carrera"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
//echo $pagina.$variable;exit;
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroAdicionarEEEstudianteCoordinador($configuracion);
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