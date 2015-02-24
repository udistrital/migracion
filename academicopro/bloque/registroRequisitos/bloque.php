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
class bloque_registroRequisitos extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroRequisitos();
 		$this->funcion=new funciones_registroRequisitos($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "nuevo":
						$this->funcion->seleccionarTipo($configuracion);
						break;

                                        case "registrar":
						$this->funcion->nuevoRegistro($configuracion);
						break;

					case "guardar":
						$this->funcion->guardarRegistro($configuracion);
						break;

					case "editar":
						$this->funcion->editarRegistro($configuracion);
						break;

					case "borrar":
						$this->funcion->borrarRegistro($configuracion);
						break;

                                        case "eliminar":
						$this->funcion->eliminarRegistro($configuracion);
						break;                                  

					case "actualizar":
						$this->funcion->actualizarRegistro($configuracion);
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
                        case "guardar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=requisitos_espacio";
				$variable.="&opcion=guardar";
                                $variable.="&id_espacio=".$_REQUEST["espacio"];
                                $variable.="&id_espacioRequisito=".$_REQUEST["espacioRequisito"];
                                $variable.="&id_planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&aprobado=".$_REQUEST["aprobado"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "actualizar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=requisitos_espacio";
				$variable.="&opcion=actualizar";
                                $variable.="&id_espacio=".$_REQUEST["espacio"];
                                $variable.="&id_espacioRequisito=".$_REQUEST["espacioRequisito"];
                                $variable.="&id_planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&aprobado=".$_REQUEST["aprobado"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "editar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=requisitos_espacio";
				$variable.="&opcion=editar";
                                $variable.="&requisito=".$_REQUEST["requisito"];
                                $variable.="&nombreRequisito=".$_REQUEST["nombreRequisito"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&aprobado=".$_REQUEST["aprobado"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                          case "borrar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=requisitos_espacio";
				$variable.="&opcion=borrar";
                                $variable.="&requisito=".$_REQUEST["requisito"];
                                $variable.="&nombreRequisito=".$_REQUEST["nombreRequisito"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&aprobado=".$_REQUEST["aprobado"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                         case "eliminar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=requisitos_espacio";
				$variable.="&opcion=eliminar";
                                $variable.="&id_espacio=".$_REQUEST["espacio"];
                                $variable.="&id_espacioRequisito=".$_REQUEST["espacioRequisito"];
                                $variable.="&id_planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&aprobado=".$_REQUEST["aprobado"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;                        


                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroRequisitos($configuracion);
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