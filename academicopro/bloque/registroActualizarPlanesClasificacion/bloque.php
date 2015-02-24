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
class bloque_registroActualizarPlanesClasificacion extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroActualizarPlanesClasificacion();
 		$this->funcion=new funciones_registroActualizarPlanesClasificacion($configuracion, $this->sql);
	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				switch($accion)
				{
                                    
					case "actualizar":
						$this->funcion->actualizarClasificacion($configuracion);
						break;

					case "actualizarInscripciones":
						$this->funcion->actualizarInscripciones($configuracion);
						break;

					case "actualizarNotas":
						$this->funcion->actualizarNotas($configuracion);
						break;
					
					case "actualizarNotasElectivas":
						$this->funcion->actualizarNotasElectivas($configuracion);
						break;

                                }
			}
			else
			{
				$accion="nuevo";
				$this->funcion->nuevoRegistro($configuracion);
			}


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{
                         case "":
                            break;
                }
	}
}

// @ Crear un objeto bloque especifico
$esteBloque=new bloque_registroActualizarPlanesClasificacion($configuracion);
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