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
class bloque_registroPortafolioElectivasCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroPortafolioElectivasCoordinador();
 		$this->funcion=new funciones_registroPortafolioElectivasCoordinador($configuracion, $this->sql);
	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "ver":
						$this->funcion->verElectivas($configuracion);
						break;

					case "crear":
						$this->funcion->crearNoOpciones($configuracion);
						break;

					case "confirmado":
						$this->funcion->guardarEA($configuracion);
						break;

					case "validarEA":
						$this->funcion->validarinformacion($configuracion);
						break;

					case "modificarEspacio":
						$this->funcion->modificarEspacioElectivo($configuracion);
						break;

					case "confirmarModificar":
						$this->funcion->confirmacionModificarElectivo($configuracion);
						break;

					case "confirmadoModificacion":
						$this->funcion->actualizarElectivo($configuracion);
						break;

					case "confirmarBorrarEA":
						$this->funcion->confirmarBorrar($configuracion);
						break;

                                        case "confirmadoBorrado":
						$this->funcion->BorrarElectivo($configuracion);
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
				$variable="pagina=registroPortafolioElectivasCoordinador";
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
				$variable="pagina=registroPortafolioElectivasCoordinador";
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
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=crear";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
				$variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "validarEA":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=validarEA";
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
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "confirmarModificar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=confirmarModificar";
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
                                $variable.="&semanas=".$_REQUEST["semanas"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


                         case "modificarCreacion":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=crear";
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
                                $variable.="&semanas=".$_REQUEST["semanas"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "modificar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=modificarEspacio";
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
                                $variable.="&semanas=".$_REQUEST["semanas"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
                            
                         case "confirmado":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=confirmado";
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
                                $variable.="&semanas=".$_REQUEST["semanas"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&id_espacio=".$_REQUEST["id_espacio"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
                            
                         case "confirmadoModificacion":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=confirmadoModificacion";
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
				$variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "confirmadoBorrado":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=confirmadoBorrado";
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
				$variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&semanas=".$_REQUEST["semanas"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "cancelar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroPortafolioElectivasCoordinador";
				$variable.="&opcion=ver";
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
$esteBloque=new bloque_registroPortafolioElectivasCoordinador($configuracion);
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