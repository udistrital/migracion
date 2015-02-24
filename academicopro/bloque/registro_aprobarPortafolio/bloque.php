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
class bloque_registro_aprobarPortafolio extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_registro_aprobarPortafolio($configuracion);
		$this->sql=new sql_registro_aprobarPortafolio();
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
                        case "ver":
				$this->funcion->verRegistro($configuracion);
			break;
                    
                        case "aprobarElectiva":
				$this->funcion->aprobarElectiva($configuracion);
			break;

                        case "noAprobar":
				$this->funcion->NoaprobarElectiva($configuracion);
			break;

                        case "modificarEspacio":
				$this->funcion->FormularioModificarEspacio($configuracion);
			break;

                        case "Solicitarconfirmar":

                                $this->funcion->solicitarConfirmacionModificacion($configuracion);

                        break;

                        case "verComentarios":

                                $this->funcion->formularioComentario($configuracion);

                        break;
                        
		}#Cierre de funcion html
	}
	
	
	function action($configuracion)
	{

            switch($_REQUEST['opcion'])
		{
                        case "validarEA":
                                $this->funcion->validarinformacionModificacion($configuracion);

                        break;
                        case "confirmado":
                                $this->funcion->guardarEAModificacion($configuracion);

                        break;
                        case "modificarEspacio":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variables="pagina=registro_aprobarPortafolio";
                                $variables.="&opcion=modificarEspacio";
                                $variables.="&codEspacio=".$_REQUEST['codEspacio'];
                                $variables.="&codProyecto=".$_REQUEST['codProyecto'];
                                $variables.="&planEstudio=".$_REQUEST['planEstudio'];
                                $variables.="&nombreProyecto=".$_REQUEST['nombreProyecto'];
                                $variables.="&clasificacion=".$_REQUEST['clasificacion'];
                                $variables.="&nombreEspacio=".$_REQUEST['nombreEspacio'];
                                $variables.="&creditos=".$_REQUEST['nroCreditos'];
                                $variables.="&nivel=".$_REQUEST['nivel'];
                                $variables.="&htd=".$_REQUEST['htd'];
                                $variables.="&htc=".$_REQUEST['htc'];
                                $variables.="&hta=".$_REQUEST['hta'];
                                $variables.="&semanas=".$_REQUEST['semanas'];
                                $variables.="&facultad=".$_REQUEST['facultad'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variables=$this->cripto->codificar_url($variables,$configuracion);
                                echo "<script>location.replace('".$pagina.$variables."')</script>";

                        break;
                        case "cancelar":
                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                                $variables="pagina=registro_aprobarPortafolio";
                                $variables.="&opcion=ver";
                                $variables.="&planEstudio=".$_REQUEST['planEstudio'];
                                $variables.="&facultad=".$_REQUEST['facultad'];

                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variables=$this->cripto->codificar_url($variables,$configuracion);
                                echo "<script>location.replace('".$pagina.$variables."')</script>";
                        break;
                }
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_registro_aprobarPortafolio($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>