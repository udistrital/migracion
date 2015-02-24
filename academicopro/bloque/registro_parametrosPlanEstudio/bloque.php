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
class bloque_registro_parametrosPlanEstudio extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registro_parametrosPlanEstudio();
 		$this->funcion=new funciones_registro_parametrosPlanEstudio($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                       case "administrar":
						$this->funcion->vistaPrincipal($configuracion);
						break;

                                       case "verificar":
						$this->funcion->validarInformacion($configuracion);
						break;

                                       case "registrar":
						$this->funcion->registrarInformacion($configuracion);
						break;

                                       case "editar":
						$this->funcion->editarInformacion($configuracion);
						break;

                                       case "actualizar":
						$this->funcion->registrarActualizacion($configuracion);
						break;
                                            
                                       case "comentario":
						$this->funcion->comentarioCoordinador($configuracion);
						break;

                                       case "guardarComentario":
						$this->funcion->guardarComentario($configuracion);
						break;

				}
			}
			else
			{
				$this->funcion->vistaPrincipal($configuracion);
			}


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{                    
                        case "guardarParametros":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registro_parametrosPlanEstudio";
				$variable.="&opcion=verificar";
                                $variable.="&totalCreditos=".$_REQUEST["totalCreditos"];
                                $variable.="&OB=".$_REQUEST["OB"];
                                $variable.="&OC=".$_REQUEST["OC"];
                                $variable.="&EI=".$_REQUEST["EI"];
                                $variable.="&EE=".$_REQUEST["EE"];
                                $variable.="&CP=".$_REQUEST["CP"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "guardarComentario":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registro_parametrosPlanEstudio";
				$variable.="&opcion=guardarComentario";
                                $variable.="&comentario=".$_REQUEST["comentario"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}

// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registro_parametrosPlanEstudio($configuracion);
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