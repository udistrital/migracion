<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Monica Monroy
* @revision      Última revisión 12 de enero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @revisiones 
* @author        Monica Monroy
* @revision      Última revisión 23 de Noviembre de 2012
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		admin_solicitud
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      	0.0.0.3
* @author		Monica Monroy
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
include_once("funcion.js.php");
include_once("sql.class.php");

//Clase
class bloque_adminCierreVacacionales extends bloque
{   
    private $configuracion;
    public function __construct($configuracion)
	{       
            include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
            $this->configuracion=$configuracion;
            $this->cripto=new encriptar();
            $this->sql=new sql_adminCierreVacacionales($configuracion);
            $this->funcion=new funcion_adminCierreVacacionales($configuracion, $this->sql);
	}
	
 function html()
	{	//Rescatar datos de sesion
		//$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
                $id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
	    
		switch ($_REQUEST['opcion'])
			{ 
			  case 'consultarProyecto':
                                //busca el periodo activo
                                $cadena_sql=$this->sql->cadena_sql('periodo','V');
                                $periodoActual=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                $variable=array('codProyecto'=>$_REQUEST['codProyecto'],
                                                'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'], 
                                                );
                                $this->funcion->consultarCarrera($variable);
			  break;
			  
			  case 'reporteGrupos':
                              //busca el periodo activo
                                $cadena_sql=$this->sql->cadena_sql('periodo','A');
                                $periodoActual=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                $variable=array('codProyecto'=>$_REQUEST['codProyecto'],
                                                'plan'=>isset($_REQUEST['plan'])?$_REQUEST['plan']:'', 
                                                'asignatura'=>isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'', 
                                                'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'],
                                                );
                                $this->funcion->verReportes($this->configuracion,$variable);
			  		
			  break;

                      default:
                                $cadena_sql=$this->sql->cadena_sql("carreraCoordinador",$id_usuario);
                                $registro=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                if(is_array($registro))
                                        {//Obtener el total de registros
                                        $totalRegistros=$this->funcion->totalRegistros($this->configuracion, $this->funcion->accesoOracle);
                                        if($totalRegistros>1)
                                                {
                                                        $this->funcion->mostrarRegistro($registro, $totalRegistros, "multiplesCarreras", $variable='');
                                                }
                                        else
                                                {       //busca el periodo activo
                                                        unset($_REQUEST['action']);		
                                                        $pagina=$this->configuracion['host'].$this->configuracion['site'].'/index.php?';
                                                        $variable='pagina=admin_cierreVacacionales';
                                                        $variable.='&opcion=consultarProyecto';
                                                        //$variable.='&codProyecto=999';
                                                        $variable.='&codProyecto='.$registro[0]['CODIGO'];
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
                    echo "Un momento por favor, cerrando notas de estudiantes...";
                    $variable = "pagina=registro_realizarCierreVacacionales";
                    $variable.="&opcion=cargarNotas";
                    $variable.="&codProyecto=".$_REQUEST['codProyecto'];
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
$esteBloque=new bloque_adminCierreVacacionales($configuracion);
//echo $_REQUEST['action'];
//var_dump($_REQUEST);exit;

if(!isset($_REQUEST['action']))
    {$esteBloque->html();}
else
    {$esteBloque->action();}
?>