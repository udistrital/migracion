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
class bloque_registroAprobarOpcionAsisVice extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_registroAprobarOpcionAsisVice($configuracion);
		$this->sql=new sql_registroAprobarOpcionAsisVice();
	}
	
	
	function html($configuracion)
	{
		
		switch($_REQUEST['opcion'])
		{
			case "aprobar":
				$this->funcion->formularioComentarioAprobar($configuracion);
			break;
										
			case "no_aprobar":
				$this->funcion->formularioComentarioNoAprobar($configuracion);
			break;

			case "guardar":
				$this->funcion->guardarRegistros($configuracion);
			break;

			case "enviarformulario":
				$this->funcion->enviarComentario($configuracion);
			break;

                        case "aprobarEncabezado":
				$this->funcion->formularioComentarioAprobarEncabezado($configuracion);
			break;

			case "no_aprobarEncabezado":
				$this->funcion->formularioComentarioNoAprobarEncabezado($configuracion);
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
                            
                      case "enviarformulario":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=registroAprobarOpcionAsisVice";
				$variable.="&opcion=enviarformulario";
                                $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                                $variable.="&codEspacio=".$_REQUEST["codEspacio"];
                                $variable.="&comentario=".$_REQUEST["comentario"];
                                $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                                $variable.="&clasificacion=".$_REQUEST["clasificacion"];
                                $variable.="&nombreEspacio=".$_REQUEST["nombreEspacio"];
                                $variable.="&creditos=".$_REQUEST["creditos"];
                                $variable.="&idEncabezado=".$_REQUEST["idEncabezado"];
                                $variable.="&nombreGeneral=".$_REQUEST["nombreGeneral"];
                                //var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);
                                //echo $pagina.$variable;exit;
				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
				
		}
		
	}
}

// @ Crear un objeto bloque especifico
$obj_aprobar=new bloque_registroAprobarOpcionAsisVice($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>