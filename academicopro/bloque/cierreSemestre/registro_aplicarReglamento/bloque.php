<?
/**
 * Bloque nombreBloque
 *
 * Esta clase se encarga de redirigir las diferentes funciones dependiendo de la opcion seleccionada
 *
 * @package nombrePaquete
 * @subpackage nombreSubpaquete
 * @author Karen Palacios
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


/**
 * Descripcion de la clase
 * 
 * @package paquete de la Clase
 * @subpackage Subpaquete de la Clase
 */
class bloque_AplicarReglamento extends bloque
{

  public $configuracion;
        
    /**
     * 
     * @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
     */    
    function __construct($configuracion){
        
        $this->configuracion=$configuracion;                
        $this->funcion=new funcion_registroAplicarReglamento($configuracion);
        $this->sql=new sql_registro($configuracion);
        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
    }

    /**
     * 
     */
    function html(){
		$opcion=isset($_REQUEST['opcion'])?$_REQUEST['opcion']:"";
        switch($opcion){
            case 'actualizarProyectos':
                $this->funcion->proyectos($_REQUEST['proyecto'],$_REQUEST['anio'],$_REQUEST['periodo']);
                break;
            /*adicionado para recalcular estados
             *  Milton Parra 27/01/2014
             */
            case "seleccionarOpcion":
                    $this->funcion->seleccionarOpcion();
                break;
            /*adicionado para recalcular estados
             *  Milton Parra 27/01/2014
             */
            case "recalcularEstudiante":
                    $this->funcion->recalcularUnEstudiante();
                break;
            /*adicionado para recalcular estados
             *  Milton Parra 27/01/2014
             */
            case "recalcularListado":
                    $this->funcion->recalcularListado();
                break;
            /*adicionado para recalcular estados
             *  Milton Parra 27/01/2014
             */            
            case "opcionEstudiante":
                $this->funcion->mostrarFormularioCargaEstudiante();
                break;
                
                
            default:
                    $this->funcion->aplicarReglamento($_REQUEST['proyecto'],$_REQUEST['anio'],$_REQUEST['periodo']);
                break;
        }
            
    }

    /**
     * 
     */
    function action(){
        
        switch($_REQUEST['opcion']){

            case "opcionEstudiante":

                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                $variable="pagina=registro_aplicarReglamento";
                $variable.="&opcion=opcionEstudiante";   

                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                echo "<script>location.replace('".$pagina.$variable."')</script>";

            break;
            case "recalcularEstudiante":
                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                $variable="pagina=registro_aplicarReglamento";
                $variable.="&opcion=recalcularEstudiante";
                $variable.="&codEstudiante=".$_REQUEST['codEstudiante'];

                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                echo "<script>location.replace('".$pagina.$variable."')</script>";

            break;

            case "recalcularListado":
                unset ($_REQUEST['opcion'],$_REQUEST['action']);
                $pagina=  $this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                $variable="pagina=registro_aplicarReglamento";
                $variable.="&opcion=recalcularListado";
                foreach ($_REQUEST['codEstudiante'] as $key => $value) {
                    $variable.="&codEstudiante[$key]=".$value;
                }
                
                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                $this->cripto=new encriptar();
                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
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
$esteBloque=new bloque_AplicarReglamento($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>
