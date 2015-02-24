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
class bloque_registroEventoEditarAdmon extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroEventoEditarAdmon();
 		$this->funcion=new funciones_registroEventoEditarAdmon($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                       case "formulario":
						$this->funcion->formularioEditar($configuracion);
						break;

                                       case "guardar":
						$this->funcion->guardarModificacion($configuracion);
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
				$variable="pagina=registroEventoEditarAdmon";
				$variable.="&opcion=guardar";
                                $variable.="&descEvento=".$_REQUEST["descEvento"];
                                $variable.="&idEvento=".$_REQUEST["idEvento"];
                                $variable.="&nombreEvento=".$_REQUEST["nombreEvento"];
                                $variable.="&descEventoAnt=".$_REQUEST["descEventoAnt"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "cancelar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminEventoConsultarAdmon";
				$variable.="&opcion=consultar";

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroEventoEditarAdmon($configuracion);
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