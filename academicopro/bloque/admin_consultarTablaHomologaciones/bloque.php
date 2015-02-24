<?

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
//echo "<br>action ".$_REQUEST['action'];
//echo "<br>opcion ".$_REQUEST['opcion'];
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("sql.class.php");
include_once("funcion.class.php");
//Clase
class bloque_adminConsultarTablaHomologaciones extends bloque
{
    private $configuracion;

    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_adminConsultarTablaHomologaciones($configuracion);
            $this->funcion=new funciones_adminConsultarTablaHomologaciones($configuracion, $this->sql);
	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "ConsultarHomologacion":
                                                $this->funcion->mostrarTablaHomologacion();
						break;
					default:
                                                $this->funcion->mostrarTablaHomologacion();
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

                         case "registrados":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroTablaHomologaciones";
				$variable.="&opcion=registrados";
				$variable.="&proyecto=".$_REQUEST["proyecto"];

//var_dump($_REQUEST);exit;
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

        
                }
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_adminConsultarTablaHomologaciones($configuracion);

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