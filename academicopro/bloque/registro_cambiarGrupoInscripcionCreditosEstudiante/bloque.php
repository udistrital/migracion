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
class bloque_registroCambiarGrupoInscripcionCreditosEstudiante extends bloque
{
  private $configuracion;

  public function __construct($configuracion)
	{
            $this->configuracion=$configuracion;
            $this->sql=new sql_registroCambiarGrupoInscripcionCreditosEstudiante($this->configuracion);
            $this->funcion=new funcion_registroCambiarGrupoInscripcionCreditosEstudiante($this->configuracion, $this->sql);

	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                        case "inscribir":
						$this->funcion->cambiarGrupo();
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
                                $variable="pagina=registro_cambiarGrupoInscripcionCreditosEstudiante";
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

$esteBloque=new bloque_registroCambiarGrupoInscripcionCreditosEstudiante($configuracion);
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