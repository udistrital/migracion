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
class bloque_registroEditarAgruparEspaciosCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroEditarAgruparEspaciosCoordinador();
 		$this->funcion=new funciones_registroEditarAgruparEspaciosCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{                             

                                       case "modificar":
						$this->funcion->modificarEncabezado($configuracion);
						break;

                                        case "guardarCambios":
						$this->funcion->guardarCambiosEncabezado($configuracion);
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

                        case "modificar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCrearAgrupacionEspaciosCoordinador";
				$variable.="&opcion=modificar";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];                                
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];                               

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "guardarCambios":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroEditarAgruparEspaciosCoordinador";
				$variable.="&opcion=guardarCambios";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&encabezadoNombre=".$_REQUEST["encabezadoNombre"];
                                $variable.="&encabezadoDescripcion=".$_REQUEST["encabezadoDescripcion"];
                                $variable.="&encabezadoCreditos=".$_REQUEST["encabezadoCreditos"];
                                $variable.="&encabezadoNivel=".$_REQUEST["encabezadoNivel"];
                                $variable.="&id_encabezado=".$_REQUEST["id_encabezado"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroEditarAgruparEspaciosCoordinador($configuracion);
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