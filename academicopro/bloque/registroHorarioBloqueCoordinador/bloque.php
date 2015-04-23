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
class bloque_registroHorarioBloqueCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroHorarioBloqueCoordinador();
 		$this->funcion=new funciones_registroHorarioBloqueCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                        case "horario":
						$this->funcion->horarioBloque($configuracion);
						break;

                                        case "espacios":
						$this->funcion->adicionarEspacios($configuracion);
						break;

                                        case "adicionar":
						$this->funcion->buscarGrupo($configuracion);
						break;

                                        case "inscribir":
						$this->funcion->inscribirEspacios($configuracion);
						break;

                                        case "cambiarGrupo":
						$this->funcion->buscarGruposCambio($configuracion);
						break;

                                        case "confirmarCambio":
						$this->funcion->confirmarCambioGrupo($configuracion);
						break;
                                            
                                        case "cancelarEspacio":
						$this->funcion->solicitaConfirmacionCancelacion($configuracion);
						break;

                                        case "confirmarCancelar":
						$this->funcion->confirmarCancelarEspacio($configuracion);
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

                        case "adicionar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroHorarioBloqueCoordinador";
				$variable.="&opcion=adicionar";
                                $variable.="&idEspacio=".$_REQUEST["idEspacio"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];
                                //var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


                        case "inscribir":
                                //var_dump($_REQUEST);exit;
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroHorarioBloqueCoordinador";
				$variable.="&opcion=inscribir";
                                $variable.="&idEspacio=".$_REQUEST["idEspacio"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "confirmarCambio":
                                //var_dump($_REQUEST);exit;
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroHorarioBloqueCoordinador";
				$variable.="&opcion=confirmarCambio";
                                $variable.="&idEspacio=".$_REQUEST["idEspacio"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&id_grupo=".$_REQUEST["id_grupo"];
                                $variable.="&id_grupoAnt=".$_REQUEST["id_grupoAnt"];
                                $variable.="&totalEstudiantes=".$_REQUEST['totalEstudiantes'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroHorarioBloqueCoordinador($configuracion);
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