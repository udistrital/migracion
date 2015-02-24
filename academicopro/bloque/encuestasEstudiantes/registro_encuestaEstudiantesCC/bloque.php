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
include_once("css.php");

/**
* Descripcion de la clase
*
* @package paquete de la Clase
* @subpackage Subpaquete de la Clase
*/
class bloque_registro extends bloque
{

  public $configuracion;
        
    /**
*
* @param array $configuracion contiene todas la variables del sistema almacenadas en la base de datos del framework
*/
    function __construct($configuracion){
        
        $this->configuracion=$configuracion;
       // $this->tema=$tema;
        $this->funcion=new funcion_reading($configuracion);
        $this->sql=new sql_reading($configuracion);
//        include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
    }

    /**
*
*/
    function html(){
	
	//Por defecto imprime el formulario asociado con el ID 1
	$opcion=isset($_REQUEST['opcion'])?$_REQUEST['opcion']:"2";
	$this->funcion->htmlFormulario($opcion);
                
    }

    /**
*
*/
    function action(){

	include_once($this->configuracion["raiz_documento"]. $this->configuracion["clases"]."/encriptar.class.php");
        $this->cripto=new encriptar();
        $pagina= $this->configuracion["host"]. "/appserv/estudiantes/est_pag_principal.php";
        $opcion=isset($_REQUEST['opcion'])?$_REQUEST['opcion']:"";

                switch($_REQUEST['opcion']){
                    
                    case "guardar":
			foreach($_REQUEST as $clave=>$valor){
			
			    //si son unica respuesta
			    if(substr($clave,0,3)=="UQ_"){
				$respuestas['UQ'][substr($clave,3)]=$valor;
			    }
			    //si son de respuesta abierta
			    if(substr($clave,0,3)=="OQ_"){
				$respuestas['OQ'][substr($clave,3)]=$valor;
			    }
			    
			}
                        
			$formulario['proceso']=$_REQUEST['proceso'];
			$formulario['prueba']=$_REQUEST['prueba'];
			$formulario['seccion']=$_REQUEST['seccion'];
                        $validacionRA = $this->funcion->validarRespuestasAbiertas($respuestas); 
                        $validacionRU = $this->funcion->validarRespuestasUnicas($respuestas); 
                        if($validacionRA =='ok' && $validacionRU=='ok'){
                            $resultado=$this->funcion->guardarFormulario($formulario,$respuestas);
                            if($resultado){
                                echo "<script>alert('Se realizó el registro exitosamente. Gracias por participar en la encuesta.')</script>";
                                echo "<script>location.replace('".$pagina."')</script>";
                            }else{
                                echo "<script>alert('Error al registrar la encuesta.')</script>";
                                $pagina2=$this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                                $variable='pagina=registro_encuestaEstudiantesCC';
                                $variable.="&usuario=".$this->funcion->usuario;
                                $variable.="&opcion=2";
                                $variable.="&tipoUser=52";

                                include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                                echo "<script>location.replace('".$pagina2.$variable."')</script>";
                            }
                        }else{
                            if($validacionRU!='ok'){
                                echo "<script>alert('Error al registrar la encuesta. ".$validacionRU."')</script>";
                            }elseif($validacionRA!='ok'){
                                echo "<script>alert('Error al registrar la encuesta. ".$validacionRA."')</script>";
                            }
                            $pagina2=$this->configuracion["host"].  $this->configuracion["site"]."/index.php?";
                            $variable='pagina=registro_encuestaEstudiantesCC';
                            $variable.="&usuario=".$this->funcion->usuario;
                            $variable.="&opcion=2";
                            $variable.="&tipoUser=52";
                            include_once($this->configuracion["raiz_documento"].  $this->configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,  $this->configuracion);
                            echo "<script>location.replace('".$pagina2.$variable."')</script>";
                        }
                        
                        break;

                    default:
    }

}

}


/**
* Crea un nuevo objeto de la clase bloque_admin
*
*/
$esteBloque=new bloque_registro($configuracion);

if(!isset($_REQUEST['action']))
{
    $esteBloque->html($configuracion);
}
else
{
    $esteBloque->action($configuracion);
}


?>
