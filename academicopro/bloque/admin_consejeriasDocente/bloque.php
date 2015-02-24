<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
 
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloque_admin_consejeriasDocente extends bloque
{
        public $configuracion;

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->configuracion=$configuracion;
                $this->tema=$tema;
		$this->funcion=new funcion_admin_consejeriasDocente($configuracion);
		$this->sql=new sql_admin_consejeriasDocente($configuracion);
	}
	
	
	function html()
	{
		$this->acceso_db=$this->funcion->conectarDB($this->configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "verProyectos":
                                $this->funcion->verProyectos();
                        break;

                        case "verEstudiantes":
                                $this->funcion->consultarEstudiantes();
                        break;


                        default:
				$this->funcion->consultarEstudiantes();
			break;
		}#Cierre de funcion html
	}
	
	
	function action()
	{

            switch($_REQUEST['opcion'])
		{

                        default:
				$this->funcion->consultarEstudiantes();
			break;
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_admin_consejeriasDocente($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>