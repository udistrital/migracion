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
class bloqueAdminConsultaHorario extends bloque
{   
  public function __construct($configuracion)
	{       include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
                $this->cripto=new encriptar();
 		$this->sql=new sql_adminConsultaHorario();
 		$this->funcion=new funciones_adminConsultaHorario($configuracion, $this->sql);
	}
	
 function html($configuracion)
	{	//Rescatar datos de sesion
		//$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
                $id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
	    
		switch ($_REQUEST['opcion'])
			{ 
			  case 'consultarGrupos':
                                //busca el periodo activo
                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->funcion->accesoOracle,'periodo','A');
                                $periodoActual=$this->funcion->ejecutarSQL($configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                $variable=array('proyecto'=>$_REQUEST['proyecto'],
                                                'plan'=>isset($_REQUEST['plan'])?$_REQUEST['plan']:'', 
                                                'asignatura'=>isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'', 
                                                'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'], 
                                                'order'=>isset($_REQUEST['order'])?$_REQUEST['order']:'2',
                                                'tipoConsulta'=>$_REQUEST['tipoConsulta']?$_REQUEST['tipoConsulta']:'',
                                                'tipoBusca'=>is_numeric($_REQUEST['espacio'])?'codigo':'nombre'                                    
                                                );
                                $this->funcion->consultarCarrera($configuracion,$variable);
			  break;
			  
			  case 'reporteGrupos':
                              //busca el periodo activo
                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->funcion->accesoOracle,'periodo','A');
                                $periodoActual=$this->funcion->ejecutarSQL($configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                $variable=array('proyecto'=>$_REQUEST['proyecto'],
                                                'plan'=>isset($_REQUEST['plan'])?$_REQUEST['plan']:'', 
                                                'asignatura'=>isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'', 
                                                'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'],
                                                );
                                $this->funcion->verReportes($configuracion,$variable);
			  		
			  break;
                      
				
			  default:
                                //Rescatar Carreras
                                $cadena_sql=$this->sql->cadena_sql($configuracion,$this->funcion->accesoOracle,"carreraCoordinador",$id_usuario);
                                $registro=$this->funcion->ejecutarSQL($configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                if(is_array($registro))
                                        {//Obtener el total de registros
                                        $totalRegistros=$this->funcion->totalRegistros($configuracion, $this->funcion->accesoOracle);
                                        if($totalRegistros>1)
                                                {
                                                        $this->funcion->mostrarRegistro($configuracion,$registro, $totalRegistros, "multiplesCarreras", $variable='');
                                                }
                                        else
                                                {       //busca el periodo activo
                                                        unset($_REQUEST['action']);		
                                                        $pagina=$configuracion['host'].$configuracion['site'].'/index.php?';
                                                        $variable='pagina=adminConsultaHorarios';
                                                        $variable.='&opcion=consultarGrupos';
                                                        $variable.='&tipoConsulta=todos';
                                                        $variable.='&proyecto='.$registro[0]['CODIGO'];
                                                        $variable=$this->cripto->codificar_url($variable,$configuracion);
                                                        echo "<script>location.replace('".$pagina.$variable."')</script>";
                                                }
                                        }
                                else
                                        {       include_once($configuracion["raiz_documento"].$configuracion["clases"]."/alerta.class.php");
                                                $cadena="No Existen Proyectos Curriculares Registrados.";
                                                alerta::sin_registro($configuracion,$cadena);
                                        }
			  	break;	
			}
	}
	
 function action($configuracion)
	{ 
            switch ($_REQUEST['opcion'])
			{ 
			  case 'buscar':
			  	     $pagina=$configuracion['host'].$configuracion['site'].'/index.php?';
                                     $variable='pagina=adminConsultaHorarios';
                                     $variable.='&opcion=consultarGrupos';
                                     $variable.='&tipoConsulta='.$_REQUEST['tipoConsulta'];
                                     $variable.='&proyecto='.$_REQUEST['proyecto'];
                                     $variable.='&espacio='.$_REQUEST['espacio'];
                                     $variable.='&periodo='.$_REQUEST['periodo'];
                                     $variable.='&tipoBusca='.$_REQUEST['tipoBusca'];
                                     //echo $variable;exit;
                                     $variable=$this->cripto->codificar_url($variable,$configuracion);
                                     echo "<script>location.replace('".$pagina.$variable."')</script>";
			  break;

			  default:
                               $pagina=$configuracion['host'].$configuracion['site'].'/index.php?';
                                $variable='pagina=adminConsultaHorarios';
                                $variable.='&tipoConsulta=todos';
                                include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$configuracion);
                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;    
                        }
	}
	
	
}
// @ Crear un objeto bloque especifico
$esteBloque=new bloqueAdminConsultaHorario($configuracion);
//echo $_REQUEST['action'];
if(isset($_REQUEST['cancelar']))
    {   unset($_REQUEST['action']);		
            $pagina=$configuracion['host'].$configuracion['site'].'/index.php?';
            $variable='pagina=adminConsultaHorarios';
            $variable.='&tipoConsulta=todos';
            include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);
            echo "<script>location.replace('".$pagina.$variable."')</script>";
    }
    
if(!isset($_REQUEST['action']))
    {$esteBloque->html($configuracion);}
else
    {$esteBloque->action($configuracion);}
?>