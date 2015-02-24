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
class bloque_registroCambiarGrupoEstudianteGrupoCoorCI extends bloque
{
  private $configuracion;

  public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_registroCambiarGrupoEstudianteGrupoCoorCI($this->configuracion);
            $this->funcion=new funcion_registroCambiarGrupoEstudianteGrupoCoorCI($this->configuracion, $this->sql);

	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                        case "inscribir":
                                            $this->funcion->realizarCambioSeleccionados();
                                            break;
                                        case "verGrupo":
                                            $this->funcion->reporteCambio();
                                            break;

                                        default:
                                            break;


				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->nuevoRegistro();
			}


	}

	function action()
	{
          unset ($_REQUEST['action']);
          switch($_REQUEST['opcion'])
		{
                        case "inscribir":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                                unset ($variable);
                                $variable="pagina=registro_cambiarGrupoEstudianteGrupoCoorCI";
				$variable.="&opcion=inscribir";
                                foreach ($_REQUEST as $key => $value) {
                                  $variable.="&".$key."=".$value;
                                }

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;



                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCambiarGrupoEstudianteGrupoCoorCI($configuracion);
//echo $_REQUEST['action'];
//var_dump($_REQUEST);exit;
if(!isset($_REQUEST['action']))
{
	$esteBloque->html();
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action();
	}
}


?>