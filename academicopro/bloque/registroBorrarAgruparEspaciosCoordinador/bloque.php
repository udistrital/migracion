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
class bloque_registroBorrarAgruparEspaciosCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroBorrarAgruparEspaciosCoordinador();
 		$this->funcion=new funciones_registroBorrarAgruparEspaciosCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{                                     
                                        case "borrarEncabezado":
						$this->funcion->cambiarEstadoEncabezado($configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->modificarEncabezado($configuracion);
			}


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{                                       
                     

                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroBorrarAgruparEspaciosCoordinador($configuracion);
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