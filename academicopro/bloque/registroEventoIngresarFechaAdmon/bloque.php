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
class bloque_registroEventoIngresarFechaAdmon extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroEventoIngresarFechaAdmon();
 		$this->funcion=new funciones_registroEventoIngresarFechaAdmon($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                       case "crear":
						$this->funcion->formularioCrear($configuracion);
						break;

                                       case "guardar":
						$this->funcion->guardarFecha($configuracion);
						break;
                                                                           
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->clasificacion($configuracion);
			}


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{                    
                        case "guardar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroEventoIngresarFechaAdmon";
				$variable.="&opcion=guardar";
                                $variable.="&evento=".$_REQUEST["evento"];
                                $variable.="&periodo=".$_REQUEST["periodo"];
                                $variable.="&fechaInicial=".$_REQUEST["fechaInicial"];
                                $variable.="&horaIni=".$_REQUEST["horaIni"];
                                $variable.="&minIni=".$_REQUEST["minIni"];
                                $variable.="&meridianoIni=".$_REQUEST["meridianoIni"];
                                $variable.="&fechaFinal=".$_REQUEST["fechaFinal"];
                                $variable.="&horaFin=".$_REQUEST["horaFin"];
                                $variable.="&minFin=".$_REQUEST["minFin"];
                                $variable.="&meridianoFin=".$_REQUEST["meridianoFin"];
                                $variable.="&usuario=".$_REQUEST["usuario"];
                                $variable.="&proyecto=".$_REQUEST["proyecto"];
                                $variable.="&facultad=".$_REQUEST["facultad"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        
                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroEventoIngresarFechaAdmon($configuracion);
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