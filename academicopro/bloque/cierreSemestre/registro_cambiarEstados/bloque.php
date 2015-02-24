<?
/**
 * Bloque cambiarEstados
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package cierreSemestre
 * @subpackage cambiarEstados
 * @author Milton Parra
 * @version 0.0.0.1
 * Fecha: 17/04/2013
 */

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

/**
 * Descripcion de la clase
 * 
 * @package paquete de la Clase
 * @subpackage Subpaquete de la Clase
 */
class bloque_registroCambiarEstados extends bloque
{

  public $configuracion;
        
    /**
     * 
     * @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
     */    
    function __construct($configuracion){
        
        //include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
        $this->configuracion=$configuracion;                
        //$this->tema=$tema;
        $this->funcion=new funcion_registroCambiarEstados($configuracion);
        $this->sql=new sql_registroCambiarEstados($configuracion);
    }

    /**
     * 
     */
    function html(){

        switch($_REQUEST['opcion']){

                case "cambiarEstados":
                        $this->funcion->cambiarEstados();
                        break;

                case "registrar":
                        $this->funcion->insertarRegistro();
                        break;

                case "formularioEditar":
                        $this->funcion->mostrarFormularioEditar();
                        break;

                case "actualizar":
                        $this->funcion->actualizarRegistro();
                        break;

                default:
                        $this->funcion->nombreMetodoDefecto();
                        break;
        }
            
    }

    /**
     * 
     */
    function action(){
      
        switch($_REQUEST['opcion']){

            case "nombreOpcionAction":

                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                $variable="pagina=nombrePagina";
                $variable.="&opcion=nombreOpcionMetodoHtml";   

                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                echo "<script>location.replace('".$pagina.$variable."')</script>";

                break;

            default:


        }

    }

}


/**
 * Crea un nuevo objeto de la clase bloque_registroCambiarEstados
 *
 */
$esteBloque=new bloque_registroCambiarEstados($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>