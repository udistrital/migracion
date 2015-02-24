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
class bloque_registroEventoEditarFechaAdmon extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroEventoEditarFechaAdmon();
 		$this->funcion=new funciones_registroEventoEditarFechaAdmon($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                       case "modifica":
						$this->funcion->formularioCrear($configuracion);
                                                //echo "en el bloque";
						break;

                                       case "editar":
						$this->funcion->editarFecha($configuracion);
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
                        case "editar":
                                //echo $_REQUEST["descUsuario"];exit;
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroEventoEditarFechaAdmon";
				$variable.="&opcion=editar";
                                $variable.="&idFechaEvento=".$_REQUEST["idFechaEvento"];
                                $variable.="&evento=".$_REQUEST["evento"];
                                $variable.="&nombreEvento=".$_REQUEST["nombreEvento"];
                                $variable.="&ano=".$_REQUEST['ano'];
                                $variable.="&periodo=".$_REQUEST['periodo'];
                                $variable.="&idCobertura=".$_REQUEST['idCobertura'];
                                $variable.="&idUsuarioAfectado=".$_REQUEST['usuario'];
                                $variable.="&fechaInicial=".$_REQUEST["fechaInicial"];
                                $variable.="&horaIni=".$_REQUEST["horaIni"];
                                $variable.="&minIni=".$_REQUEST["minIni"];
                                $variable.="&meridianoIni=".$_REQUEST["meridianoIni"];
                                $variable.="&fechaFinal=".$_REQUEST["fechaFinal"];
                                $variable.="&horaFin=".$_REQUEST["horaFin"];
                                $variable.="&minFin=".$_REQUEST["minFin"];
                                //$variable.="&meridianoFin=".$_REQUEST["meridianoFin"];
                                $variable.="&usuario=".$_REQUEST["usuario"];
                                $variable.="&descUsuario=".$_REQUEST["descUsuario"];
                                

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        
                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroEventoEditarFechaAdmon($configuracion);
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