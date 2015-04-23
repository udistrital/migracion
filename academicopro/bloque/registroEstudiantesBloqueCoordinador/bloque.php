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
class bloque_registroEstudiantesBloqueCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroEstudiantesBloqueCoordinador();
 		$this->funcion=new funciones_registroEstudiantesBloqueCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                        case "registrar":
						$this->funcion->registrarBloque($configuracion);
						break;

                                        case "guardar":
						$this->funcion->guardarBloque($configuracion);
						break;

                                        case "editar":
						$this->funcion->editarBloque($configuracion);
						break;

                                        case "nuevosEstudiantes":
						$this->funcion->registrarBloqueEstudiantes($configuracion);
						break;

                                        case "borrar":
						$this->funcion->borrarBloqueEstudiantes($configuracion);
						break;

                                        case "borrarSeleccionados":
						$this->funcion->borrarSeleccionados($configuracion);
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
				$variable="pagina=registroEstudiantesBloqueCoordinador";
				$variable.="&opcion=guardar";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                
                                $j=0;
                                for($i=0;$i<500;$i++)
                                {
                                    $_REQUEST['estudiante'.$i]=(isset($_REQUEST['estudiante'.$i])?$_REQUEST['estudiante'.$i]:'');
                                    if($_REQUEST['estudiante'.$i]==NULL)
                                        {

                                        }else
                                            {
                                                $variable.="&estudiante".$j."=".$_REQUEST['estudiante'.$i];
                                                $j++;
                                            }
                                }
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "borrarSeleccionados":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroEstudiantesBloqueCoordinador";
				$variable.="&opcion=borrarSeleccionados";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $j=0;
                                for($i=0;$i<500;$i++)
                                {
                                    if(!isset($_REQUEST['estudiante'.$i]) || $_REQUEST['estudiante'.$i]==NULL)
                                        {

                                        }else
                                            {
                                                $variable.="&estudiante".$j."=".$_REQUEST['estudiante'.$i];
                                                $j++;
                                            }
                                }

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                       

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroEstudiantesBloqueCoordinador($configuracion);
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