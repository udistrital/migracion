<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 12 de enero de 2009
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
*				caso de uso: CONSULTAR SOLICITUD DE RECIBO DE PAGO
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
include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/validacion_usu_wo.class.php");

//Clase
class bloqueAdminSolicitud extends bloque
{

    public $configuracion;
    
    public function __construct($configuracion)
	{

 		$this->sql=new sql_adminSolicitud();
 		$this->funcion=new funciones_adminSolicitud($configuracion, $this->sql);
                 $this->configuracion=$configuracion;
                 $this->validacion=new validarUsu();
 		
	}
	
	
	function html()
	{
		if(isset($_REQUEST["confirmar"]))
		{
			//Mostrar pantalla de confirmacion
			$this->funcion->confirmarDesbloqueo($this->configuracion);
		}
		elseif(!isset($_REQUEST["action"]))
		{
			if(isset($_REQUEST["opcion"])&&$_REQUEST["opcion"]=="generadoEstudiante"){

				$this->funcion->recibosGeneradosest($this->configuracion,$_REQUEST["estudiante"],$_REQUEST["anno_per"]);


			}
			else{		
				//Conexion ORACLE
				$accesoOracle=$this->funcion->conectarDB($this->configuracion,"coordinador");
				//Conexion General
				$acceso_db=$this->funcion->conectarDB($this->configuracion,"");
			
			
				$conexion=$accesoOracle;
				$tipo="busqueda";
			
				//Rescatar datos de sesion
				$usuario=$this->funcion->rescatarValorSesion($this->configuracion, $acceso_db, "usuario");
				$id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $acceso_db, "id_usuario");
				$nivel=$this->funcion->rescatarValorSesion($this->configuracion, $acceso_db, "nivelUsuario");
			
				//Rescatar Datos Generales
				$annoActual=$this->funcion->datosGenerales($this->configuracion,$conexion, "anno") ;
				$periodoActual=$this->funcion->datosGenerales($this->configuracion,$conexion, "per") ;
			
			
				$variable[1]=$annoActual;
				$variable[2]=$periodoActual;
			
				//TO REVIEW
				//Se rescatan las carreras de los cuales el usuario es coordinador, si el usuario tiene mas de una carrera
				//y no existe una solicitud explicita entonces se muestra un cuadro general con todas las carreras
			
				if(isset($_REQUEST["carrera"]))
				{
					//Consultar una carrera especifica
					$this->funcion->consultarCarrera($this->configuracion);
				}
				else
				{
					//Rescatar Carreras
					unset($registro);
					if($nivel==4){
                                            $cadena_sql=$this->sql->cadena_sql($this->configuracion,$conexion,"carreraCoordinador",$id_usuario);
                                            $registro=$this->funcion->ejecutarSQL($this->configuracion, $conexion, $cadena_sql, $tipo);
                                        
                                        }elseif($nivel==110 || $nivel==114){
                                            $accesoOracle=$this->funcion->conectarDB($this->configuracion,"asistente");
                                            $conexion=$accesoOracle;
                                            $proyectos =$this->validacion->consultarProyectosAsistente($usuario,$nivel,$conexion,$this->configuracion,$accesoOracle);
                                             foreach ($proyectos as $key => $proyecto) {
                                                $registro[$key][0]= $proyecto[0];
                                                $registro[$key][1]= $proyecto[4];
                                            }
                                        }
					if(is_array($registro))
					{
						//Obtener el total de registros
						$totalRegistros=$this->funcion->totalRegistros($this->configuracion, $conexion);
						if($totalRegistros>1)
						{
							$this->funcion->mostrarRegistro($this->configuracion,$registro, $totalRegistros, "multiplesCarreras", $variable);
						}
						else
						{
						
							$_REQUEST["carrera"]=$registro[0][0];
							//Consultar una carrera especifica
							$this->funcion->consultarCarrera($this->configuracion);
						}
					
					
					}
					else
					{
						$_REQUEST["carrera"]=$registro[0][0];
							//Consultar una carrera especifica
						$this->funcion->consultarCarrera($this->configuracion);
					}
				}
			}		
		}
	}
	
	
	function action()
	{
		//$accesoOracle=$this->funcion->conectarDB($configuracion,"coordinador");
		//$this->funcion->desbloquearRecibos($configuracion,$accesoOracle);

		if(isset($_REQUEST["opcion"])&&$_REQUEST["opcion"]=="generadoEstudiante")
		{
					
			//var_dump($_REQUEST);
			$registro=$this->funcion->consultarGeneradoEstudiante($this->configuracion);
		}
		else
		{
			$this->funcion->generarLoteRecibos($this->configuracion);	
		
		}
		

	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueAdminSolicitud($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html();
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
	$esteBloque->action();
	}
	else
	{
		$esteBloque->html();	
	}
}


?>




















