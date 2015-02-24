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
class bloque_registroMultipleClasificacionEspacioCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroMultipleClasificacionEspacioCoordinador();
 		$this->funcion=new funciones_registroMultipleClasificacionEspacioCoordinador($configuracion, $this->sql);
	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "ver_planEstudio":
						$this->funcion->verPlanEstudio($configuracion);
						break;

					case "actualizarEspacioPortafolio":
						$this->funcion->actualizarEspacioPortafolio();
						break;
                                            
                                         default:
                                             $this->funcion->verPlanEstudio($configuracion);
                                             break;

                                }
			}
			else
			{
				echo 'no existe opci&oacute;n';exit;
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
$esteBloque=new bloque_registroMultipleClasificacionEspacioCoordinador($configuracion);
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