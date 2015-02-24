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
class bloque_registroNoAprobarEspacioAsisVice extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_registroNoAprobarEspacioAsisVice($configuracion);
		$this->sql=new sql_registroNoAprobarEspacioAsisVice();
	}
	
	
	function html($configuracion)
	{
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
			case "no_aprobar":
				$this->funcion->formularioComentario($configuracion);
			break;
			
			case "confirmado":
				$this->funcion->guardarRegistros($configuracion);
			break;
					
			default:
				$this->funcion->mostrarRegistro($configuracion,$this->tema,$_REQUEST['id_malla'], $this->acceso_db,"");
			break;
                        
                        case "mostrar":
				$this->obj_aprobarEspacio->mostrarRegistro($configuracion,$_REQUEST['planEstudio']);
			break;
		}#Cierre de funcion html
	}
	
	
	function action($configuracion)
	{
//            echo $_REQUEST['opcion']."<br>";
//            echo $_REQUEST['action'];
//            exit;

            switch($_REQUEST['opcion'])
		{
                        case "confirmado":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroNoAprobarEspacioAsisVice";
				$variable.="&opcion=confirmado";
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&comentario=".$_REQUEST["comentario"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&nivel=".$_REQUEST["nivel"];
                                $variable.="&htd=".$_REQUEST["htd"];
                                $variable.="&htc=".$_REQUEST["htc"];
                                $variable.="&hta=".$_REQUEST["hta"];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "mostrar":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminAprobarEspacioPlan";
				$variable.="&opcion=mostrar";
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];                            

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_registroNoAprobarEspacioAsisVice($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>