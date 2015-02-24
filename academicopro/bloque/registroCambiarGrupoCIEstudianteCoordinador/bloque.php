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
class bloque_registroCambiarGrupoCIEstudianteCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCambiarGrupoCIEstudianteCoordinador();
 		$this->funcion=new funciones_registroCambiarGrupoCIEstudianteCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "buscar":
						$this->funcion->buscarGrupos($configuracion);
						break;

                                        case "otrosGrupos":
						$this->funcion->buscarOtrosGrupos($configuracion);
						break;

                                        case "cambiar":
						$this->funcion->cambiarGrupo($configuracion);
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

                        case "cambiar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoCIEstudianteCoordinador";
				$variable.="&opcion=cambiar";
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variable.="&planEstudioGeneral=".$_REQUEST['planEstudioGeneral'];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&proyecto=".$_REQUEST["proyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&grupoAnterior=".$_REQUEST["grupoAnterior"];
                                $variable.="&nombre=".$_REQUEST["nombre"];
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

$esteBloque=new bloque_registroCambiarGrupoCIEstudianteCoordinador($configuracion);
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