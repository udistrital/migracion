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

date_default_timezone_set('America/Bogota');
header('Content-Encoding: UTF-8');
header("Content-type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=Reporte_".$_REQUEST['opcion']."_".$_REQUEST['proyecto']."_".$_REQUEST['periodo']."_".date("Y-m-d H:i").".xls");
header("Pragma: no-cache");
header("Expires: 0");
                
                
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");

//Clase
class bloqueReportesExcelCoodinador extends bloque
{   
    private $configuracion;
    public function __construct($configuracion)
	{       include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
                $this->cripto=new encriptar();
               // $this->cripto->decodificar_url($_REQUEST['index'], $configuracion);
                /*if (isset($_REQUEST['index']))
                      {$this->cripto->decodificar_url($_REQUEST['index'], $configuracion);}*/
                $this->sql=new sql_ReportesExcelCoodinador();
 		$this->funcion=new funciones_ReportesExcelCoodinador($configuracion, $this->sql);
                $this->configuracion=$configuracion;
                
                
                
	}
	
 function html()
	{	
		switch ($_REQUEST['opcion'])
			{ 
			  case 'Horario':
                              //busca el periodo activo
                                $cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->funcion->accesoOracle,'periodo','A');
                                $periodoActual=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, "busqueda");
                                $variable=array('proyecto'=>$_REQUEST['proyecto'],
                                                'plan'=>isset($_REQUEST['plan'])?$_REQUEST['plan']:'', 
                                                'asignatura'=>isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'', 
                                                'anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'],
                                                'opcion'=>isset($_REQUEST['opcion'])?$_REQUEST['opcion']:'exportarHorario'
                                                );
                               // var_dump($variable);exit;
                      		/*header('Content-type: application/vnd.ms-excel;charset=UTF-8');
                                header('Content-Disposition: attachment; filename=repoteHorario_'.$variable['proyecto'].'_'.$_REQUEST['periodo'].'.xls');
                                header('Pragma: no-cache');
                                header('Expires: 0');*/
                         
                                $this->funcion->exportarHorario($this->configuracion,$variable);
			  		
			  break;
                      
                         case 'Ocupacion':
                              //busca el periodo activo
                               $variable=array('anio'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-6,4):$periodoActual[0]['ANIO'], 
                                                'periodo'=>isset($_REQUEST['periodo'])?substr($_REQUEST['periodo'],-1):$periodoActual[0]['PERIODO'], 
                                                'sede'=>isset($_REQUEST['sede'])?$_REQUEST['sede']:'', 
                                                'edificio'=>isset($_REQUEST['edificio'])?$_REQUEST['edificio']:'', 
                                                'salon'=>isset($_REQUEST['salon'])?$_REQUEST['salon']:'', 
                                                'proyecto'=>isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'', 
                                                'espacio'=>isset($_REQUEST['espacio'])?$_REQUEST['espacio']:'', 
                                                'dia'=>isset($_REQUEST['dia'])?$_REQUEST['dia']:'', 
                                                'hora'=>isset($_REQUEST['hora'])?$_REQUEST['hora']:''
                                                );
                                $this->funcion->consultarOcupacion($this->configuracion,$variable);
			  		
			  break;
                      
			  
			  case 'reporteGrupos':
                         			  		
			  break;
                      
				
			  default:
                               
			  	break;	
			}
	}
	
 function action()
	{   
            
     
            switch ($_REQUEST['opcion'])
			{ 
			  case 'buscar':
			  break;

			  default:
                               
                          break;    
                        }
	}
	
	
}
// @ Crear un objeto bloque especifico
$esteBloque=new bloqueReportesExcelCoodinador($configuracion);
//echo "llego ".$_REQUEST['action'];exit;
    
if(!isset($_REQUEST['action']))
    {$esteBloque->html();}
else
    {$esteBloque->action();}
?>