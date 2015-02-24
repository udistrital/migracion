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
class bloque_registroBloqueEstudiantes extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
                $this->configuracion=$configuracion;
                $this->sql=new sql_registroBloqueEstudiantes();
 		$this->funcion=new funciones_registroBloqueEstudiantes($configuracion, $this->sql);
	}


	function html()
	{
		if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];

				switch($accion)
				{
                                        case "verProyectos":
						$this->funcion->verProyectos();
						break;

					case "crear":
						$this->funcion->moduloHabilitado();
						break;

                                        case "registrar":
						$this->funcion->registrarBloque();
						break;

                                        case "guardar":
						$this->funcion->guardarBloque();
						break;

                                        case "horario":
						$this->funcion->horarioBloqueInd();
						break;

                                        case "espacios":
						$this->funcion->adicionarEspacios();
						break;

                                        case "adicionar":
						$this->funcion->buscarGrupo();
						break;

                                        case "inscribir":
						$this->funcion->inscribirEspacios();
						break;

                                        case "inscripcion":
						$this->funcion->inscribirEstudiantes();
						break;

                                        case "editar":
						$this->funcion->editarBloque();
						break;

                                        case "borrar":
						$this->funcion->confirmarBorrar();
						break;

                                        case "borrarBloque":
						$this->funcion->borrarBloque();
						break;

                                        case "nuevosEstudiantes":
						$this->funcion->registrarBloqueEstudiantes();
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
            switch($_REQUEST['opcion'])
		{

                        case "registrar":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroBloqueEstudiantes";
				$variable.="&opcion=registrar";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];


                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "proyectos":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroBloqueEstudiantes";
				$variable.="&opcion=crear";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "guardar":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroBloqueEstudiantes";
				$variable.="&opcion=guardar";
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $j=0;
                                for($i=0;$i<500;$i++)

                                {
                                    if($_REQUEST['estudiante'.$i]==NULL)
                                        {

                                        }else
                                            {
                                                $variable.="&estudiante".$j."=".$_REQUEST['estudiante'.$i];
                                                $j++;
                                            }

                                }

                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "horario":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroBloqueEstudiantes";
                            $variable.="&opcion=horario";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&idBloque=".$_REQUEST["idBloque"];

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;

                        case "borrar":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroBloqueEstudiantes";
                            $variable.="&opcion=borrar";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&idBloque=".$_REQUEST["idBloque"];

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;

                        case "borrarBloque":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroBloqueEstudiantes";
                            $variable.="&opcion=borrarBloque";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&idBloque=".$_REQUEST["idBloque"];

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;

                        case "adicionar":

                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroBloqueEstudiantes";
				$variable.="&opcion=adicionar";
                                $variable.="&idEspacio=".$_REQUEST["idEspacio"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


                        case "inscribir":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroBloqueEstudiantes";
				$variable.="&opcion=inscribir";
                                $variable.="&idEspacio=".$_REQUEST["idEspacio"];
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&grupo=".$_REQUEST["grupo"];
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "estudiantes":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroBloqueEstudiantes";
				$variable.="&opcion=nuevosEstudiantes";
                                $variable.="&idBloque=".$_REQUEST["idBloque"];
                                $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;



                }
	}
}



// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroBloqueEstudiantes($configuracion);
//echo $_REQUEST['action'];
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