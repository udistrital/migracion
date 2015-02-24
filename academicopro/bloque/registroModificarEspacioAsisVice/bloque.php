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
class bloque_registroModificarEspacioAsisVice extends bloque
{
  private $configuracion;

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroModificarEspacioAsisVice($configuracion);
 		$this->funcion=new funciones_registroModificarEspacioAsisVice($configuracion, $this->sql);
                $this->configuracion=$configuracion;
	}


	function html()
	{
            	if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "modificarEspacio":
                                                $this->funcion->modificarEspacio();
						//$this->funcion->formularioModificarAsisVice($configuracion);
						break;

					case "solicitarConfirmacion":
						$this->funcion->solicitarConfirmacion();
						break;

					case "modificarEspacioEncabezado":
						$this->funcion->formularioModificarAsisViceEncabezado();
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

                        case "confirmadoEspacio":
                                $this->funcion->guardarDatosEspacio();
				break;
                            
                        case "confirmadoEncabezado":
                                $this->funcion->guardarEAEncabezado();
				break;

                        case "actualizar":
                                $this->funcion->guardarEAComun();
				break;

                        case "confirmar":
                                $this->funcion->verificarEspacioPlan();
				break;

                        case "modificarNombre":
                                $this->funcion->verificarNombreEspacio();
				break;

                        case "confirmadoNombre":
                                $this->funcion->modificarNombreEspacio();
				break;

                        case "modificarCreditos":
                                $this->funcion->verificarCreditosEspacio();
				break;

                        case "confirmadoCreditos":
                                $this->funcion->modificarCreditosEspacio();
				break;

                        case "modificarClasificacion":
                                $this->funcion->verificarClasificacionEspacio();
				break;

                        case "confirmadoClasificacion":
                                $this->funcion->modificarClasificacionEspacioAcademico();
				break;

                        case "modificarNivel":
                                $this->funcion->verificarNivelEspacio();
				break;

                        case "confirmadoNivel":
                                $this->funcion->modificarNivelEspacio();
				break;

                        case 'cancelar':
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=adminAprobarEspacioPlan";
                            $variable.="&opcion=mostrar";
                            $variable.="&proyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            //echo $pagina.$variable;exit;
                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;
                                

                       
                }
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_registroModificarEspacioAsisVice($configuracion);
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