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
class bloque_adminEspaciosHorariosProyecto extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminEspaciosHorariosProyecto();
 		$this->funcion=new funciones_adminEspaciosHorariosProyecto($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "horario":
						$this->funcion->verHorarios($configuracion);
						break;

					case "verEstudiantes":
						$this->funcion->verEstudiantes($configuracion);
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

                        case "horario":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminEspaciosHorarios";
				$variable.="&opcion=horario";
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

$esteBloque=new bloque_adminEspaciosHorariosProyecto($configuracion);
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