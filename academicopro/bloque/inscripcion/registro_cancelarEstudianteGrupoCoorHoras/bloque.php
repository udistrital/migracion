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
class bloque_registroCancelarEstudianteGrupoCoorHoras extends bloque
{
  private $configuracion;

  public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_registroCancelarEstudianteGrupoCoorHoras($this->configuracion);
            $this->funcion=new funcion_registroCancelarEstudianteGrupoCoorHoras($this->configuracion, $this->sql);
 		
	}
	
	
	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "procesar":
						$this->funcion->verificarCancelacion();
						break;

					case "verificarCancelacion":
						$this->funcion->verificarCancelacionEspacio();
						break;

                                        case "cancelarCreditos":
						$this->funcion->cancelarCreditos();
						break;                                      

                                        case "verGrupo":
						$this->funcion->reporteCancelado();
						break;

                                        default :
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
                        $variable="pagina=registro_cancelarEstudianteGrupoCoorHoras";
                        foreach ($_REQUEST as $key => $value) {
                          $variable.="&".$key."=".$value;
                        }
                        include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        break;

                    default :

                        $this->funcion->cancelar();                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCancelarEstudianteGrupoCoorHoras($configuracion);
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
