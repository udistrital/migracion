<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
 
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloque_adminConfigurarPlanEstudioCoordinador extends bloque
{
    private $configuracion;
    
    public function __construct($configuracion)
	{	
            //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
            //$this->tema=$tema;
            $this->configuracion=$configuracion;
            $this->funcion=new funcion_adminConfigurarPlanEstudioCoordinador($configuracion);
            $this->sql=new sql_adminConfigurarPlanEstudioCoordinador();
	}
	
	
	function html()
	{
		$this->acceso_db=$this->funcion->conectarDB($this->configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "verProyectos":
                                $this->funcion->verProyectos();
                        break;

                        case "guardar":
                                $this->funcion->guardarAprobacion($this->configuracion);
                        break;
                    
			case "mostrar":
				$this->funcion->mostrarRegistro($_REQUEST['planEstudio']);
			break;
			
			case "ver":
				$this->funcion->verRegistro($this->configuracion,$this->tema,$this->acceso_db, "");
			break;

			case "registrados":
				$this->funcion->verRegistrados();
			break;
					
			default:
				$this->funcion->verProyectos();
			break;
		}#Cierre de funcion html
	}
	
	
	function action()
	{
//            echo $_REQUEST['opcion']."<br>";
//            echo $_REQUEST['action'];
//            exit;

            switch($_REQUEST['opcion'])
		{
                        case "guardar":

                                $this->funcion->guardarAprobacion($this->configuracion);

                        break;

                        case "comentarios":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroAgregarComentarioEspacio";
                            $variable.="&opcion=verComentarios";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nivel=".$_REQUEST["nivel"];
                            $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                            $variable.="&creditos=".$_REQUEST["creditos"];
                            $variable.="&htd=".$_REQUEST["htd"];
                            $variable.="&htc=".$_REQUEST["htc"];
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;

                        case "no_aprobar":
//var_dump($_REQUEST);exit;
                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=registroNoAprobarEspacioAsisVice";
                            $variable.="&opcion=no_aprobar";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nivel=".$_REQUEST["nivel"];
                            $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                            $variable.="&creditos=".$_REQUEST["creditos"];
                            $variable.="&htd=".$_REQUEST["htd"];
                            $variable.="&htc=".$_REQUEST["htc"];
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;
                        
                        case "seleccionClasificacion":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=registroCrearEACoordinador";
				$variable.="&opcion=seleccionClasificacion";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

//var_dump($_REQUEST);exit;
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

				
                        case "registrados":
                                $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
				$variable="pagina=adminConfigurarPlanEstudioCoordinador";
				$variable.="&opcion=registrados";
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];
				$variable.="&planEstudio=".$_REQUEST["planEstudio"];
				$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
				
//var_dump($_REQUEST);exit;
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$this->configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;


		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminConfigurarPlanEstudioCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>