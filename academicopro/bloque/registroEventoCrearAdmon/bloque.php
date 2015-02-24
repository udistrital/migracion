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
class bloque_registroEventoCrearAdmon extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroEventoCrearAdmon();
 		$this->funcion=new funciones_registroEventoCrearAdmon($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                       case "crear":
						$this->funcion->crearEvento($configuracion);
						break;

                                       case "generar":
						$this->funcion->generarEvento($configuracion);
						break;

                                       case "existe":
						$this->funcion->crearEventoExistente($configuracion);
						break;


                                
				}
			}
                        else
			{
				$accion="nuevo";
				$this->funcion->crearEvento($configuracion);
			}

	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{                    
                      case "generar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroEventoCrearAdmon";
				$variable.="&opcion=generar";
                                $variable.="&eventoNombre=".$_REQUEST["eventoNombre"];
                                $variable.="&eventoDescripcion=".$_REQUEST["eventoDescripcion"];                                

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                     /* case "existe":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroEventoCrearAdmon";
				$variable.="&opcion=existe";
                                $variable.="&eventoNombre=".$_REQUEST["eventoNombre"];
                                $variable.="&eventoDescripcion=".$_REQUEST["eventoDescripcion"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;*/

                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroEventoCrearAdmon($configuracion);
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