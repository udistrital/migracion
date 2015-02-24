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
class bloque_adminHomologacionesPendientes extends bloque
{
    private $configuracion;
    
    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_adminHomologacionesPendientes();
            $this->funcion=new funciones_adminHomologacionesPendientes($configuracion, $this->sql);
	}


	function html()
	{           
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "realizarHomologacionPendientes":
                                            	$this->funcion->realizarHomologacionPendientes('estudiantes');
						break;
					
                                        case "realizarHomologacionCohortePendientes":
                                            	$this->funcion->realizarHomologacionPendientes('cohorte');
						break;
                                        case "consultarCohorte":
                                            	$this->funcion->mostrarCohorte();
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
$esteBloque=new bloque_adminHomologacionesPendientes($configuracion);
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