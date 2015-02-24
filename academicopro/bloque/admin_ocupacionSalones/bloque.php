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
class bloqueAdminOcupacion extends bloque
{   
    private $configuracion;
  public function __construct($configuracion)
	{       include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
                $this->cripto=new encriptar();
 		$this->sql=new sql_AdminOcupacion();
 		$this->funcion=new funciones_AdminOcupacion($configuracion, $this->sql);
                $this->configuracion=$configuracion;
	}
	
 function html()
	{	//Rescatar datos de sesion
		//$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
                $id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
	    
		switch ($_REQUEST['opcion'])
			{ 
			  case 'consultarOcupacion':
                              
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
                      
                          case 'reporteSalones':
                                $this->funcion->reporteSalones($this->configuracion);
                              break;
				
			  default:
                                //Rescatar Carreras
                                include_once($this->configuracion["raiz_documento"].$this->configuracion["clases"]."/alerta.class.php");
                                $cadena=".:: Diligencie el (los) campo (s) para realizar la b&uacute;squeda ::.";
                                alerta::sin_registro($this->configuracion,$cadena);
                                
			  	break;	
			}
	}
	
 function action()
	{ 
            switch ($_REQUEST['opcion'])
			{ 
			  case 'buscar':
			  	     $pagina=$this->configuracion['host'].$this->configuracion['site'].'/index.php?';
                                     $variable='pagina=adminocupacionSalones';
                                     $variable.='&opcion=consultarOcupacion';
                                     $variable.='&periodo='.$_REQUEST['periodo'];
                                     $variable.='&sede='.$_REQUEST['sede'];
                                     $variable.='&edificio='.$_REQUEST['edificio'];
                                     $variable.='&salon='.$_REQUEST['salon'];
                                     $variable.='&proyecto='.$_REQUEST['proyecto'];
                                     $variable.='&espacio='.$_REQUEST['espacio'];
                                     $variable.='&dia='.$_REQUEST['dia'];
                                     $variable.='&hora='.$_REQUEST['hora'];
                                     $variable.='&tipoBusca='.(isset($_REQUEST['tipoBusca'])?$_REQUEST['tipoBusca']:'');
                                     //echo $variable;exit;
                                     $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                     echo "<script>location.replace('".$pagina.$variable."')</script>";
			  break;

                          case 'reporteSalones':
			  	     $pagina=$this->configuracion['host'].$this->configuracion['site'].'/index.php?';
                                     $variable='pagina=adminocupacionSalones';
                                     $variable.='&opcion=reporteSalones';
                                     $variable.='&facultad='.$_REQUEST['sede'];
                                     $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                     echo "<script>location.replace('".$pagina.$variable."')</script>";
                              break;
                      
			  default:
                               $pagina=$this->configuracion['host'].$this->configuracion['site'].'/index.php?';
                                $variable='pagina=adminocupacionSalones';
                                $variable.='&opcion=inicio';
                                include_once($this->configuracion['raiz_documento'].$this->configuracion['clases'].'/encriptar.class.php');
                                $this->cripto=new encriptar();
                                $variable=$this->cripto->codificar_url($variable,$this->configuracion);
                                echo "<script>location.replace('".$pagina.$variable."')</script>";
                          break;    
                        }
	}
	
	
}
// @ Crear un objeto bloque especifico
$esteBloque=new bloqueAdminOcupacion($configuracion);
//echo $_REQUEST['action'];
if(isset($_REQUEST['cancelar']))
    {   unset($_REQUEST['action']);		
            $pagina=$configuracion['host'].$configuracion['site'].'/index.php?';
            $variable='pagina=adminocupacionSalones';
            $variable.='&tipoConsulta=todos';
            include_once($configuracion['raiz_documento'].$configuracion['clases'].'/encriptar.class.php');
            $this->cripto=new encriptar();
            $variable=$this->cripto->codificar_url($variable,$configuracion);
            echo "<script>location.replace('".$pagina.$variable."')</script>";
    }
    
if(!isset($_REQUEST['action']))
    {$esteBloque->html();}
else
    {$esteBloque->action();}
?>