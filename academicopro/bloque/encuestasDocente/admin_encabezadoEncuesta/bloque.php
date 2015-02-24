<?
/**
* Bloque nombreBloque
*
* Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
*
* @package nombrePaquete
* @subpackage nombreSubpaquete
* @author Luis Fernando Torres
* @version 0.0.0.1
* Fecha: 26/02/2013
*/

if(!isset($GLOBALS["autorizado"]))
{
include("../index.php");
exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
include_once("css.php");

/**
* Descripcion de la clase
*
* @package paquete de la Clase
* @subpackage Subpaquete de la Clase
*/
class bloque_admin_navegacion extends bloque
{

  public $configuracion;
        
    /**
*
* @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
*/
    function __construct($configuracion){
        
        $this->configuracion=$configuracion;
       // $this->tema=$tema;
        $this->funcion=new funcion_admin_navegacion($configuracion);
        $this->sql=new sql_admin_navegacion($configuracion);
//        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
    }

    /**
*
*/
    function html(){
	echo $this->funcion->htmlBarraNavegacion();

    }

    /**
*
*/
    function action(){

	include_once($this->configuracion["raiz_documento"]. $this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();

                switch($_REQUEST['opcion']){
                    
                    case "nombreOpcionAction":

                        $pagina= $this->configuracion["host"]. $this->configuracion["site"]."/index.php?";
                        $variable="pagina=nombrePagina";
                        $variable.="&opcion=nombreOpcionMetodoHtml";

                        $variable=$this->cripto->codificar_url($variable, $this->configuracion);
                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                        
                        break;

                    default:
    }

}

}


/**
* Crea un nuevo objeto de la clase bloque_admin
*
*/
$esteBloque=new bloque_admin_navegacion($configuracion);

if(!isset($_REQUEST['action']))
{
$esteBloque->html($configuracion);
}
else
{
$esteBloque->action($configuracion);
}


?>
