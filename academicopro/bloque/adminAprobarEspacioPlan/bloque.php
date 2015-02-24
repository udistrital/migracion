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
class bloque_adminAprobacionEspacioPlan extends bloque
{
    public $obj_aprobarEspacio;
    public $configuracion;

    public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->obj_aprobarEspacio=new funcion_aprobarEspacioPlan($configuracion);
		$this->obj_sql=new sql_aprobarEspacio();
                $this->configuracion=$configuracion;
	}
	
	
	function html()
	{
		$this->acceso_db=$this->obj_aprobarEspacio->conectarDB($this->configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "guardar":
                                $this->obj_aprobarEspacio->guardarAprobacion($this->configuracion);
                        break;
                    
			case "mostrar":
				$this->obj_aprobarEspacio->mostrarRegistro($this->configuracion,$_REQUEST['planEstudio']);
			break;
			
			case "buscarPlan":
				$this->obj_aprobarEspacio->buscarEnfasis($this->configuracion,$_REQUEST['proyecto'],$_REQUEST['nombreProyecto']);
			break;

			case "ver":
				$this->obj_aprobarEspacio->verRegistro($this->configuracion,$this->tema,$this->acceso_db, "");
			break;

			case "verParametros":
				$this->obj_aprobarEspacio->vistaPrincipalParametros($this->configuracion);
			break;

                        case "editarParametros":
				$this->obj_aprobarEspacio->editarParametros($this->configuracion);
			break;

			case "aprobarParametros":
				$this->obj_aprobarEspacio->aprobarParametros($this->configuracion);
			break;
					
			default:
				$this->obj_aprobarEspacio->mostrarRegistro($this->configuracion,$_REQUEST['planEstudio']);
			break;
		}#Cierre de funcion html
	}
	
	
	function action()
	{

            switch($_REQUEST['opcion'])
		{
                        case "guardar":

                                $this->obj_aprobarEspacio->guardarAprobacion($this->configuracion);

                        break;

                        case "aprobarParametros":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=adminAprobarEspacioPlan";
                            $variable.="&opcion=aprobarParametros";
                            $variable.="&totalCreditos=".$_REQUEST["totalCreditos"];
                            $variable.="&OB=".$_REQUEST["OB"];
                            $variable.="&OC=".$_REQUEST["OC"];
                            $variable.="&EI=".$_REQUEST["EI"];
                            $variable.="&EE=".$_REQUEST["EE"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
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

				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminAprobacionEspacioPlan($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>