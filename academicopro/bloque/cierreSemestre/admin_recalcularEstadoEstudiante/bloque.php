<?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("sql.class.php");
include_once("funcion.class.php");
//Clase
class bloque_adminRecalcularEstadoEstudiante extends bloque
{
    private $configuracion;
    
    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_adminRecalcularEstadoEstudiante();
            $this->funcion=new funciones_adminRecalcularEstadoEstudiante($configuracion, $this->sql);
	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "mostrarFormulario":
                                            	$this->funcion->mostrarFrmRecalcularEstado();
						break;
					
                                        default:
                                            break;
				}
			}
			

	}

	function action()
	{
            switch($_REQUEST['opcion'])
		{
                    case "mostrarFormulario":
                        unset ($_REQUEST['opcion'],$_REQUEST['action']);
                        $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                        $variable="pagina=admin_recalcularEstadoEstudiante";
                        $variable.="&opcion=mostrarFormulario";

                        include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                        $this->cripto=new encriptar();
                        $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";

                    break;
  
                }
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_adminRecalcularEstadoEstudiante($configuracion);
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