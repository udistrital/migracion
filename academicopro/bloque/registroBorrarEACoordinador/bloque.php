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
class bloque_registroBorrarEACoordinador extends bloque
{
    public $configuracion;
    
    public function __construct($configuracion)
	{
 		$this->sql=new sql_registroBorrarEACoordinador();
 		$this->funcion=new funciones_registroBorrarEACoordinador($configuracion, $this->sql);
                $this->configuracion=$configuracion;
	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                       case "confirmarBorrarEA":
						$this->funcion->validarinformacion($this->configuracion);
						break;

                                        case "borrar":
						$this->funcion->borrarEspacio($this->configuracion);
                                                break;	
                                        
                                        case "solicitar":
						$this->funcion->formularioCreacion($this->configuracion);
						break;

                                        case "solicitarEncabezado":
						$this->funcion->formularioBorrarEncabezado($this->configuracion);
						break;
                                    
                                        case "cancelar":
						$this->funcion->cancelar($this->configuracion);
						break;

				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->nuevoRegistro($this->configuracion);
			}


	}

	function action()
	{
            switch($_REQUEST['opcion'])
		{
                        case "borrar":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroBorrarEACoordinador";
				$variable.="&opcion=borrar";
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
				$variable.="&aprobado=".$_REQUEST["aprobado"];

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;         
                          
                      
                        case "cancelar":
                            $this->funcion->cancelar($this->configuracion);
				break;                         

                       case "borrarEncabezado":
                                $this->funcion->borrarEncabezado($this->configuracion);
                           break;
                         
                }
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_registroBorrarEACoordinador($configuracion);
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