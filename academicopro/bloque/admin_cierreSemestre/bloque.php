<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @revisiones 
* @author        Jairo Lavado
* @revision      Última revisión 23 de Noviembre de 2012
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitud
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.3
* @author		Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	Bloque para gestionar las solicitudes de recibos de pago
*				realizadas por las diferentes coordinaciones. Implementa el
*				caso de uso: CONSULTAR horarios
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
class bloque_adminCierreSemestre extends bloque
{   
    private $configuracion;
    public function __construct($configuracion)
	{       
            include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
            $this->configuracion=$configuracion;
            $this->cripto=new encriptar();
            $this->sql=new sql_adminCierreSemestre();
            $this->funcion=new funcion_adminCierreSemestre($configuracion, $this->sql);
	}
	
 function html()
	{	//Rescatar datos de sesion
		//$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
                $id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
	    
		switch ($_REQUEST['opcion'])
			{ 
			  case 'consultarProyecto':
                                //busca el periodo activo
                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->funcion->accesoOracle,'periodo','A');
                                $periodoActual=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                $variable=array('proyecto'=>$_REQUEST['proyecto'],
                                                'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'], 
                                                );
                                $this->funcion->consultarCarrera($this->configuracion,$variable);
			  break;
			  
			  case 'reporteGrupos':
                              //busca el periodo activo
                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->funcion->accesoOracle,'periodo','A');
                                $periodoActual=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                $variable=array('proyecto'=>$_REQUEST['proyecto'],
                                                'plan'=>isset($_REQUEST['plan'])?$_REQUEST['plan']:'', 
                                                'asignatura'=>isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'', 
                                                'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'],
                                                );
                                $this->funcion->verReportes($this->configuracion,$variable);
			  		
			  break;
                      case 'cambiar':
                          $this->funcion->realizarCambioEstado();
                      break;
                      				
                      case 'aplicar':
                          $this->funcion->realizarAplicacionReglamento();
                      break;
                      				
			  default:
                                //Rescatar Carreras
                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->funcion->accesoOracle,"carreraCoordinador",$id_usuario);
                                $registro=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                if(is_array($registro))
                                        {//Obtener el total de registros
                                        $totalRegistros=$this->funcion->totalRegistros($this->configuracion, $this->funcion->accesoOracle);
                                        if($totalRegistros>1)
                                                {
                                                        $this->funcion->mostrarRegistro($this->configuracion,$registro, $totalRegistros, "multiplesCarreras", $variable='');
                                                }
                                        else
                                                {       //busca el periodo activo
                                                        unset($_REQUEST['action']);		
                                                        $pagina=$this->configuracion['host'].$this->configuracion['site'].'/index.php?';
                                                        $variable='pagina=adminCierreSemestre';
                                                        $variable.='&opcion=consultarProyecto';
                                                        $variable.='&proyecto='.$registro[0]['CODIGO'];
                                                        $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                }
                                        }
                                else
                                        {       include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
                                                $cadena="No Existen Proyectos Curriculares Registrados.";
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
                case 'cerrar':
                    //busca el periodo activo
                  
                    $variable = "pagina=admin_realizarCierreSemestre";
                    $variable.="&opcion=cargarNotas";
                    $variable.="&codProyecto=".$_REQUEST['proyecto'];
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    break;

                case 'cambiar':
                        //busca el periodo activo
                    $variable = "pagina=adminCierreSemestre";
                    $variable.="&opcion=cambiar";
                    $variable.="&codProyecto=".$_REQUEST['proyecto'];
                    $variable = $this->cripto->codificar_url($variable, $this->configuracion);
                    echo "<script>location.replace('" . $pagina . $variable . "')</script>";
                    break;
                
                case 'aplicar':
                        //busca el periodo activo
                    $variable = "pagina=adminCierreSemestre";
                    $variable.="&opcion=aplicar";
                    $variable.="&codProyecto=".$_REQUEST['proyecto'];
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
$esteBloque=new bloque_adminCierreSemestre($configuracion);
//echo $_REQUEST['action'];
//var_dump($_REQUEST);exit;

if(!isset($_REQUEST['action']))
    {$esteBloque->html($configuracion);}
else
    {$esteBloque->action($configuracion);}
?>