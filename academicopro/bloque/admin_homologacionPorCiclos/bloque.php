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
class bloque_adminHomologacionPorCiclos extends bloque
{
    private $configuracion;
    
    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_adminHomologacionPorCiclos();
            $this->funcion=new funciones_adminHomologacionPorCiclos($configuracion, $this->sql);
	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "realizarHomologacionPorCiclos":
                                            	$this->funcion->realizarHomologacionPorCiclos('estudiantes');
						break;
					case "realizarHomologacionCohortePorCiclos":
                                            	$this->funcion->realizarHomologacionPorCiclos('cohorte');
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
$esteBloque=new bloque_adminHomologacionPorCiclos($configuracion);
//"blouqe ".$_REQUEST['action'];exit;
//var_dump($_REQUEST);exit;
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