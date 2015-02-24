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
class bloque_registro_cancelarEspacioPreinscripcionDemandaEstud extends bloque
{
  private $configuracion;

  public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_registro_cancelarEspacioPreinscripcionDemandaEstud($this->configuracion);
            $this->funcion=new funcion_registro_cancelarEspacioPreinscripcionDemandaEstud($this->configuracion, $this->sql);
 		
	}
	
	
	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "confirmar":
						$this->funcion->confirmar();
						break;

                                        case "confirmarCancelacion":
						$this->funcion->confirmarCancelacion();
						break;                                      

                                        case "cancelar":
                                            //var_dump($_REQUEST);exit;
						$this->funcion->enlaceNoCancelar($_REQUEST);
						break;

                                        case "desbloquear":
						$this->funcion->desbloquearInscripcion();
						break;

                                        case "nuevo":
						$this->funcion->formularioConsultaHorario();
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
            unset ($_REQUEST['action']);
            if (isset ($_REQUEST['cancelar_x']))
            {
              $this->funcion->cancelar();
            }
            switch($_REQUEST['opcion'])
		{
                    case "cancelarCreditos":

                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registro_cancelarEspacioPreinscripcionDemandaEstud";
                        foreach ($_REQUEST as $key => $value) {
                          $variable.="&".$key."=".$value;
                        }

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        break;

                    case "nuevo":

                        $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                        $variable="pagina=registro_cancelarEspacioPreinscripcionDemandaEstud";
                        $variable.="&opcion=verificar";
                        $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        break;
                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registro_cancelarEspacioPreinscripcionDemandaEstud($configuracion);
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
