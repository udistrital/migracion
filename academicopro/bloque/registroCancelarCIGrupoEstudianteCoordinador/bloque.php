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
class bloque_registroCancelarCIGrupoEstudianteCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCancelarCIGrupoEstudianteCoordinador();
 		$this->funcion=new funciones_registroCancelarCIGrupoEstudianteCoordinador($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
                                     case "verificaEstudiante":
						$this->funcion->verificaCancelacionEstudiante($configuracion);
						break;

                                    case "variosEstudiantes":
						$this->funcion->solicitarConfirmacion($configuracion);
						break;
                                            
                                    case "confirmacionExitosa":
						$this->funcion->verificaCancelacionEstudianteXGrupo($configuracion);
						break;

                                     case "cancelarEspacio":
						$this->funcion->cancelacionEspacioEstudiante($configuracion);
						break;

                                    case "cancelar":
						$this->funcion->cancelaSolicitud($configuracion);
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
                          case "cancelarEspacio":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarCIGrupoEstudianteCoordinador";
				$variable.="&opcion=cancelarEspacio";
                                $variable.="&cancelaEstudiante=".$_REQUEST["cancelaEstudiante"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                          case "cancelar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarCIGrupoEstudianteCoordinador";
				$variable.="&opcion=cancelar";
                                $variable.="&cancelaEstudiante=".$_REQUEST["cancelaEstudiante"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                               
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
               
                      /*  case "cancelarCreditos":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarCIGrupoEstudianteCoordinador";
				$variable.="&opcion=cancelarCreditos";
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&proyecto=".$_REQUEST["proyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&espacio=".$_REQUEST["codEspacio"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&periodo=".$_REQUEST["periodo"];
                                $variable.="&ano=".$_REQUEST["ano"];
                                $variable.="&nombre=".$_REQUEST["nombre"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&planEstudioGeneral=".$_REQUEST["planEstudioGeneral"];
                                $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                                //var_dump($variable);exit;

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;                       
                        

                        case "nuevo":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCancelarCIGrupoEstudianteCoordinador";
				$variable.="&opcion=verificar";
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;*/

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCancelarCIGrupoEstudianteCoordinador($configuracion);
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
