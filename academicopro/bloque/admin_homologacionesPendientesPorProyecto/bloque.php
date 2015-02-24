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
class bloque_adminHomologacionesPendientesPorProyecto extends bloque
{
    private $configuracion;
    
    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_adminHomologacionesPendientesPorProyecto();
            $this->funcion=new funciones_adminHomologacionesPendientesPorProyecto($configuracion, $this->sql);
	}


	function html()
	{           
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					/*case "realizarHomologacionPendientes":
                                            	$this->funcion->realizarHomologacionPendientes();
						break;
					*/
                                        case "realizarHomologacionProyectoPendientes":
                                            	$this->funcion->realizarHomologacionPendientes();
						break;
                                        case "consultarProyecto":
                                            	$this->funcion->mostrarProyecto();
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
  
                }
	}
}



// @ Crear un objeto bloque especifico
$esteBloque=new bloque_adminHomologacionesPendientesPorProyecto($configuracion);
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