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
class bloque_registroAdicionarEspacioExistenteCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroAdicionarEspacioExistenteCoordinador();
 		$this->funcion=new funciones_registroAdicionarEspacioExistenteCoordinador($configuracion, $this->sql);
	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                        case "listaPlanes":
						$this->funcion->listarPlanes($configuracion);
						break;

					case "verPlanSeleccionado":
						$this->funcion->planSeleccionado($configuracion);
						break;

					case "espacioSeleccionado":
						$this->funcion->formularioEASeleccionado($configuracion);
						break;

					case "seleccionClasificacion":
						$this->funcion->seleccionarClasificacion($configuracion);
						break;

					case "sinOpciones":
						$this->funcion->crearNoOpciones($configuracion);
						break;

					case "validarEA":
						$this->funcion->validarinformacion($configuracion);
						break;

					case "confirmado":
						$this->funcion->guardarEA($configuracion);
						break;

					case "buscarEA":
						$this->funcion->buscarEA($configuracion);
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

                         case "validarEA":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEspacioExistenteCoordinador";
				$variable.="&opcion=validarEA";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&codEspacio=".$_REQUEST["codEspacio"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
				$variable.="&clasificacion=".$_REQUEST["clasificacion"];
				$variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
				$variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
				$variable.="&nivel=".$_REQUEST["nivel"];
				$variable.="&htd=".$_REQUEST["htd"];
				$variable.="&htc=".$_REQUEST["htc"];
				$variable.="&hta=".$_REQUEST["hta"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "buscador":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEspacioExistenteCoordinador";
				$variable.="&opcion=buscarEA";
				$variable.="&codEspacio=".$_REQUEST["codigoEA"];
				$variable.="&palabraEA=".$_REQUEST["palabraEA"];
				$variable.="&planEstudioCoor=".$_REQUEST["planEstudioCoor"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


                         case "modificar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEspacioExistenteCoordinador";
				$variable.="&opcion=espacioSeleccionado";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&planEstudioCoor=".$_REQUEST["planEstudioCoor"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
				$variable.="&clasificacion=".$_REQUEST["clasificacion"];
				$variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
				$variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
				$variable.="&nivel=".$_REQUEST["nivel"];
				$variable.="&htd=".$_REQUEST["htd"];
				$variable.="&htc=".$_REQUEST["htc"];
				$variable.="&hta=".$_REQUEST["hta"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
                            
                         case "confirmado":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEspacioExistenteCoordinador";
				$variable.="&opcion=confirmado";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&planEstudioCoor=".$_REQUEST["planEstudioCoor"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
				$variable.="&clasificacion=".$_REQUEST["clasificacion"];
				$variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
				$variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
				$variable.="&nivel=".$_REQUEST["nivel"];
				$variable.="&htd=".$_REQUEST["htd"];
				$variable.="&htc=".$_REQUEST["htc"];
				$variable.="&hta=".$_REQUEST["hta"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "cancelar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAdicionarEspacioExistenteCoordinador";
				$variable.="&opcion=listaPlanes";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
				$variable.="&clasificacion=".$_REQUEST["clasificacion"];
				$variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
				$variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
				$variable.="&nivel=".$_REQUEST["nivel"];
				$variable.="&htd=".$_REQUEST["htd"];
				$variable.="&htc=".$_REQUEST["htc"];
				$variable.="&hta=".$_REQUEST["hta"];
				$variable.="&id_espacio=".$_REQUEST["id_espacio"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

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
$esteBloque=new bloque_registroAdicionarEspacioExistenteCoordinador($configuracion);
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