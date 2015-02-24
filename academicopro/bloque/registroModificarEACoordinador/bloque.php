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
class bloque_registroModificarEACoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroModificarEACoordinador();
 		$this->funcion=new funciones_registroModificarEACoordinador($configuracion, $this->sql);
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

					case "registrados":
						$this->funcion->verRegistrados($configuracion);
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

					case "solicitar":
						$this->funcion->formularioCreacion($configuracion);
						break;
                                        case "solicitarEncabezado":
						$this->funcion->formularioModificarEncabezado($configuracion);
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

                         case "registrados":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCrearEACoordinador";
				$variable.="&opcion=registrados";
				$variable.="&proyecto=".$_REQUEST["proyecto"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "seleccionClasificacion":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCrearEACoordinador";
				$variable.="&opcion=seleccionClasificacion";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "sinOpciones":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCrearEACoordinador";
				$variable.="&opcion=sinOpciones";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
				$variable.="&clasificacion=".$_REQUEST["clasificacion"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "validarEA":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroModificarEACoordinador";
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

                         case "modificar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroModificarEACoordinador";
				$variable.="&opcion=solicitar";
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
                            
                         case "confirmado":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroModificarEACoordinador";
				$variable.="&opcion=confirmado";
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

                         case "cancelar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminConfigurarPlanEstudioCoordinador";
				$variable.="&opcion=verProyectos";
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
				$variable.="&id_espacio=".$_REQUEST["id_espacio"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "confirmadoEncabezado":
                                $this->funcion->guardarEAEncabezado($configuracion);
                             break;
                         
                         
                }
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_registroModificarEACoordinador($configuracion);
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