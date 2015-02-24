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
class bloque_adminVerRequisitosDecano extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_adminVerRequisitosDecano();
 		$this->funcion=new funciones_adminVerRequisitosDecano($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "ver":
						$this->funcion->verRegistro($configuracion);
						break;

                                        case "visualizar":
						$this->funcion->verRequisitosDecano($configuracion);
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
                        case "visualizar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminVerRequisitosDecano";
				$variable.="&opcion=visualizar";
                                $variable.="&id_planEstudio=".$_REQUEST["planEstudio"];
                                //$variable.="&proyecto=".$_REQUEST["proyecto"];
                                
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;                

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminVerRequisitosDecano($configuracion);
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