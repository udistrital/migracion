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
class bloque_adminEspacioNoAprobado extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminEspacioNoAprobado();
 		$this->funcion=new funciones_adminEspacioNoAprobado($configuracion, $this->sql);
	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "verProyectos":
						$this->funcion->verProyectos($configuracion);
						break;                                      
                                        case "NOaprobados":
						$this->funcion->verNOaprobados($configuracion);
						break;

                                        case "aprobados_noCargados":
						$this->funcion->aprobadosNocargados($configuracion);
						break;
				}
                                
			}
	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{

                               case "NOaprobados":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminEspacioNoAprobado";
				$variable.="&opcion=NOaprobados";
				$variable.="&proyecto=".$_REQUEST["proyecto"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }

	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminEspacioNoAprobado($configuracion);
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