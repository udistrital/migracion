<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Milton Parra
* @revision      Última revisión 08 de septiembre de 2014
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		cierreSemestre
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.1
* @author		Milton Parra
* @author		Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar cargar notas parciales que no fueron procesadas al momento del cierre
*
/*--------------------------------------------------------------------------------------------------------------------------*/
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloque_adminConsultarNotasParcialesNoCargadas extends bloque
{   
    private $configuracion;
    public function __construct($configuracion)
	{
            include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
            $this->configuracion=$configuracion;
            $this->cripto=new encriptar();
            $this->sql=new sql_adminConsultarNotasParcialesNoCargadas($configuracion);
            $this->funcion=new funcion_adminConsultarNotasParcialesNoCargadas($configuracion, $this->sql);
	}
	
 function html()
	{
                $id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
		switch ($_REQUEST['opcion'])
                    {
                        case 'consultarProyecto':
                            //busca el periodo activo
                            $periodo=  explode('-', $_REQUEST['periodoCargar']);
                            $variable=array('codProyecto'=>$_REQUEST['codProyecto'],
                                            'nombreProyecto'=>$_REQUEST['nombreProyecto'], 
                                            'anio'=>(isset($periodo[0])?$periodo[0]:''), 
                                            'periodo'=>(isset($periodo[1])?$periodo[1]:'')
                                            );
                            $this->funcion->consultarNotasNoCargadasProyecto($variable);
                            break;
			  
                    
                        case "confirmarNotas":
                              $this->funcion->confirmarNotas();
                            break;
                      		
                    
                        default:
                            //Rescatar Carreras
                            $cadena_sql=$this->sql->cadena_sql("carreraCoordinador",$id_usuario);
                            $registro=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                            if(is_array($registro))
                            {//Obtener el total de registros
                                $totalRegistros=$this->funcion->totalRegistros($this->configuracion, $this->funcion->accesoOracle);
                                if($totalRegistros>1)
                                {
                                    $this->funcion->mostrarProyectosCargar($registro, $totalRegistros, "multiplesCarreras", $variable='');
                                }
                                else
                                    {       //busca el periodo activo
                                        $this->funcion->presentarFormularioPeríodo($registro);                                            
                                    }
                            }
                            else
                                {
                                    include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
                                    $cadena="No existen Proyectos Curriculares de Postgrado asociados.";
                                    alerta::sin_registro($this->configuracion,$cadena);
                                }
                            break;	
                    }
	}
	
 function action()
	{
            $pagina = $this->configuracion["host"] . $this->configuracion["site"] . "/index.php?";
	
            unset($_REQUEST['action']);
            switch ($_REQUEST['opcion'])
            {
                case 'confirmarNotas':
                    $total=0;
                    unset ($_REQUEST['action']);
                    foreach ($_REQUEST as $key => $value) {
                        if (strpos($key,'insc')!==false)
                        {
                            $total++;
                        }
                    }
                    $_REQUEST['total']=$total;
                    $this->funcion->confirmarNotas();
                    break;

                    
                case 'cargarNotas':
                    if (isset ($_REQUEST['cancelar_x']))
                    {
                      $this->funcion->cancelar();
                    }
                    $this->funcion->cargarNotas();
                    exit;
                    break;
                
                    
                case 'consultarProyecto':
                    echo "Un momento por favor, buscando datos...";
                        //busca el periodo activo
                    $variable = "pagina=adminConsultarNotasParcialesNoCargadas";
                    $variable.="&opcion=consultarProyecto";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
                    $variable.="&nombreProyecto=".$_REQUEST['nombreProyecto'];                    
                    $variable.="&periodoCargar=".$_REQUEST['periodoCargar'];
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    break;
                
                case 'otro':
			  		
                    break;

                default:
                    break;    
            }
	}
	
	
}
// @ Crear un objeto bloque especifico
$esteBloque=new bloque_adminConsultarNotasParcialesNoCargadas($configuracion);

if(!isset($_REQUEST['action']))
    {$esteBloque->html();}
else
    {$esteBloque->action();}
?>