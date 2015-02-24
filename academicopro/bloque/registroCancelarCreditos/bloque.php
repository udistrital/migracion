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
class bloque_registroCancelarCreditos extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCancelarCreditos();
 		$this->funcion=new funciones_registroCancelarCreditos($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "verificar":
						$this->funcion->verificarCancelacionEspacio($configuracion);
						break;

					case "verificarCreditos":
						$this->funcion->verificarCreditos($configuracion);
						break;

                                        case "cancelarCreditos":
						$this->funcion->cancelarCreditos($configuracion);
						break;

                                        case "nuevo":
						$this->funcion->formularioConsultaHorario($configuracion);
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
//            var_dump($_REQUEST);
//            exit;
            if (isset ($_REQUEST['cancelar_x']))
            {
                $this->funcion->cancelar($configuracion);
            }

            switch($_REQUEST['opcion'])
		{
               
                        case "cancelarCreditos":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=cancelarCreditos";
				$variable.="&opcion=cancelarCreditos";
                                $variable.="&espacio=".$_REQUEST["codEspacio"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&proyecto=".$_REQUEST["proyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&ano=".$_REQUEST["ano"];
                                $variable.="&periodo=".$_REQUEST["periodo"];
                                $variable.="&nombre=".$_REQUEST["nombre"];
                                $variable.="&creditos=".$_REQUEST["creditos"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "inscribir":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adicionarCreditos";
				$variable.="&opcion=inscribir";
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];
                                $variable.="&espacio=".$_REQUEST["espacio"];
                                $variable.="&carrera=".$_REQUEST["carrera"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "nuevo":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=cancelarCreditos";
				$variable.="&opcion=verificar";
                                $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCancelarCreditos($configuracion);
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
