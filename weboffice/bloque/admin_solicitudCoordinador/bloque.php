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
* @author		Oficina Asesora de Sistemas
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
class bloqueAdminSolicitudCoordinador extends bloque
{

    public $configuracion;
    
    public function __construct($configuracion)
	{
 		$this->sql=new sql_adminSolicitudCoordinador();
 		$this->funcion=new funciones_adminSolicitudCoordinador($configuracion, $this->sql);
                 $this->configuracion=$configuracion;
                $this->validacion=new validarUsu();
 		
	}
	
	
	function html()
	{       
                $variable='';
                $total='';
                $registro='';
		if(isset($_REQUEST["confirmar"]))
		{
			//Mostrar pantalla de confirmacion
			$this->funcion->confirmarGeneracion($this->configuracion);
		}
		elseif(!isset($_REQUEST["action"]))
		{
		
			if(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=="solicitadoEstudiante")
			{

				$this->funcion->reciboSolicitadoest($this->configuracion,$_REQUEST["estudiante"]);

			}
			elseif(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=="historico")
			{
                            
				$this->funcion->historicoEstudiante($this->configuracion,$registro, $total);

			}
			elseif(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=="mostrarRegistros")
			{

				$this->funcion->registrosHistorico($this->configuracion,$registro, $total);

			}
			else
			{
                                $conexion=  $this->funcion->accesoOracle;
				$tipo="busqueda";
				
				//Rescatar datos de sesion
				$usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "usuario");
				$id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
				$nivel=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "nivelUsuario");
				
				$variable='';
				//$variable[1]=$this->annoActual;
				//$variable[2]=$this->periodoActual;
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
					if($nivel==4){
					$cadena_sql=$this->sql->cadena_sql($this->configuracion,$this->funcion->accesoOracle,"carreraCoordinador",$id_usuario);
					$registro=$this->funcion->ejecutarSQL($this->configuracion, $this->funcion->accesoOracle, $cadena_sql, $tipo);
					
                                        }elseif($nivel==110 || $nivel==114){
                                            $proyectos =$this->validacion->consultarProyectosAsistente($usuario,$nivel,$conexion,$this->configuracion,  $this->funcion->accesoOracle);
                                             foreach ($proyectos as $key => $proyecto) {
                                                $registro[$key][0]= $proyecto[0];
                                                $registro[$key][1]= $proyecto[4];
                                            }
                                        }
					if(is_array($registro))
					{
						//Obtener el total de registros
						$totalRegistros=$this->funcion->totalRegistros($this->configuracion, $this->funcion->accesoOracle);
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
						
					}
				}
			}
		}
		
	}
	
	
	function action()
	{
		if(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=="solicitadoEstudiante")
		{
			$registro=$this->funcion->consultarSolicitudEstudiante($this->configuracion);
		}
		elseif(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=="historicoEstudiante")
		{
			$registro=$this->funcion->consultarHistoricoEstudiante($this->configuracion);
		}
		else
		{
			$this->funcion->generarLoteRecibos($this->configuracion);	
		
		}
		
		
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloqueAdminSolicitudCoordinador($configuracion);

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




















