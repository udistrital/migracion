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
class bloque_adminConsultarIncritosEspacioPorFacultadAsisVice extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminConsultarIncritosEspacioPorFacultadAsisVice($configuracion);
		$this->sql=new sql_adminConsultarIncritosEspacioPorFacultadAsisVice();
                $this->configuracion=$configuracion;
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
			case "consultar":
                                $this->funcion->mostrarRegistro($this->configuracion);
			break;
			
			case "select":
				$this->funcion->facultad($this->configuracion);
			break;

                        default:
				$this->funcion->verRegistro($this->configuracion);
			break;
					
			
		}#Cierre de funcion html
	}
	
	
	function action()
	{

            switch($_REQUEST['opcion'])
		{
                        case "consultar":

                            $pagina=$this->configuracion["host"].$this->configuracion["site"]."/index.php?";
                            $variable="pagina=adminConsultarIncritosEspacioPorFacultadAsisVice";
                            $variable.="&opcion=consultar";
                            $variable.="&id_facultad=".$_REQUEST["id_facultad"];
                            $variable.="&id_espacio=".$_REQUEST["id_espacio"];
                            //$variable.="&facultad=".$_REQUEST["facultad"];
                            //$variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];

                            include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$this->configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;
                       


				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminConsultarIncritosEspacioPorFacultadAsisVice($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html();
}
else
{
	$obj_aprobar->action();
}


?>