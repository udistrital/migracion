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
class bloque_adminHomologacionTransferenciaInterna extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_adminHomologacionTransferenciaInterna();
            $this->funcion=new funciones_adminHomologacionTransferenciaInterna($configuracion, $this->sql);
	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "realizarHomologacionTransferenciaInterna":
                                            	$this->funcion->realizarHomologacionTransferenciaInterna('estudiantes');
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
$esteBloque=new bloque_adminHomologacionTransferenciaInterna($configuracion);
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