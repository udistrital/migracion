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
class bloque_registroCambiarGrupoCIGrupoCoordinador extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCambiarGrupoCIGrupoCoordinador();
 		$this->funcion=new funciones_registroCambiarGrupoCIGrupoCoordinador($configuracion, $this->sql);

	}


	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
					case "estudiante":
						$this->funcion->buscarGrupo($configuracion);
						break;

					case "cambiarGrupoEstudiante":
						$this->funcion->cambiarGrupoEstudiante($configuracion);
						break;

					case "variosEstudiantes":
						$this->funcion->buscarGrupoVariosEstudiantes($configuracion);
						break;

                                        case "cambiarGrupoVarios":
						$this->funcion->cambiarGrupoVarios($configuracion);
						break;
                                }
			}
                 
			


	}

	function action($configuracion)
	{
            switch($_REQUEST['opcion'])
		{

                        case "cambiar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoCIGrupoCoordinador";
				$variable.="&opcion=cambiarGrupoEstudiante";
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nroGrupoAnt=".$_REQUEST["nroGrupoAnt"];
                                $variable.="&nroGrupoNue=".$_REQUEST["nroGrupoNue"];
                                $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "cambiarVarios":
                            
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroCambiarGrupoCIGrupoCoordinador";
				$variable.="&opcion=cambiarGrupoVarios";
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nroGrupoAnt=".$_REQUEST["nroGrupoAnt"];
                                $variable.="&nroGrupoNue=".$_REQUEST["nroGrupoNue"];
                                $variable.="&nroCreditos=".$_REQUEST["nroCreditos"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&totalEstudiantes=".$_REQUEST["totalEstudiantes"];

                                $total=$_REQUEST['totalEstudiantes']+1;
                                        for($i=0;$i<$total;$i++)
                                        {
                                            $variable.="&codEstudiante-".$i."=".$_REQUEST['codEstudiante-'.$i];

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

$esteBloque=new bloque_registroCambiarGrupoCIGrupoCoordinador($configuracion);
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