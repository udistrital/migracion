<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @author        Paulo Cesar Coronado
* @revision      Última revisión 05 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_inscripcion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
* @author			Paulo Cesar Coronado
* @author			Oficina Asesora de Sistemas
* @link			N/D
* @description  	
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
class bloque_registro_PlanTrabajo extends bloque
{
    private $configuracion;
    public function __construct($configuracion)
	{
 		$this->sql=new sql_registro_PlanTrabajo();
 		$this->funcion=new funciones_registro_PlanTrabajo($configuracion, $this->sql);
                $this->configuracion=$configuracion;
 		
	}
	
	public function jxajax(){

		switch($_REQUEST['jxajax']){
			case "consultarOcupacion":
				$this->funcion->consultarOcupacion($_REQUEST['cod_salon'],$_REQUEST['anio'],$_REQUEST['periodo']);
			break;
			case "actualizarHorario":
				$this->funcion->actualizarHorario($_REQUEST['cod_salon'],$_REQUEST['cod_hora'],$_REQUEST['ano'],$_REQUEST['per'],$_REQUEST['cod_vinculacion'],$_REQUEST['cod_actividad'],$_REQUEST['cod_sede']);
			break;
			case "borrarHorario":
				$this->funcion->borrarHorario($_REQUEST['cod_hora'],$_REQUEST['ano'],$_REQUEST['per']);
			break;
			case "registrarHorario":
				$this->funcion->registrarHorario($_REQUEST['cod_salon'],$_REQUEST['cod_hora'],$_REQUEST['ano'],$_REQUEST['per'],$_REQUEST['cod_vinculacion'],$_REQUEST['cod_actividad'],$_REQUEST['cod_sede']);
			break;
			
		}
	}
	
		
	
	function html()
	{
//            echo "html";
//            var_dump($_REQUEST);exit;
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "dignotasPregrado":
                                            //echo "A";exit;
						$this->funcion->digitarNotasPregrado($this->configuracion, $accesoOracle,$acceso_db);
						break;
					case "actividades":
                                            //Esta opcion permite regsitrar una actividad.
						$this->funcion->registroActividades($this->configuracion);
						break;
					case "nuevoRegistro":
                                            //esta opcion se utiliza cuando se envia formulario para registrar una actividad
						$this->funcion->registrarPlanTrabajo($this->configuracion);
						break;
					case "mensajes":
                                            //Presenta mensajes de error
						$this->funcion->mensajesErrores($this->configuracion);
						break;
					case "reportes":
                                            //esta opcion se utiliza para consultar el plan e imprimirlo
						$this->funcion->reportes($this->configuracion);
						break;
					case "borrar":
                                            //Esta opcion se utiliza para enviar datos de borrado de una actividad
						$this->funcion->borrarActividades($this->configuracion);
						break;
					case "copiarPlan":
                                            //Esta opcion se utiliza para enviar datos de borrado de una actividad
                                                $this->funcion->copiarPlanTrabajo($this->configuracion);
						break;
                                            default :
                                                echo "default";exit;
				}
			}
			else
			{
                            //esta opcion se utiliza para registrar plan de trabajo. Es la opcion por defecto.
                            $accion="nuevo";
                            $this->funcion->registrarPlanTrabajo($this->configuracion);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($this->configuracion, "formgrado");	
		}
	}
	
	function action()
	{
//            echo "action";
//            var_dump($_REQUEST);exit;
                $this->funcion->revisarFormulario();
		
		$tipo="busqueda";
			
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($this->configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=='copiarPlan')
			{
                            //a traves de esta opcion se puede copiar el plan de trabajo de un periodo
                            $valor[10]=$_REQUEST['nivel'];
                            $this->funcion->redireccionarInscripcion($this->configuracion, "copiarPlanTrabajo");
			}
			if(isset($_REQUEST["opcion"]) && $_REQUEST["opcion"]=='copiarPlanDocente')
			{
                            //a traves de esta opcion se puede copiar el plan de trabajo de un periodo
                            $valor[10]=$_REQUEST['nivel'];
                            $this->funcion->copiarPlanDocente($this->configuracion);
			}
			if(isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
                            //a traves de esta opcion se guarda el registro nuevo
                            $valor[10]=$_REQUEST['nivel'];
                            $this->funcion->guardarPlanTrabajo($this->configuracion);
			}
			if(!isset($_REQUEST["opcion"]) && isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
                            //a traves de esta opcion se elimina el registro
                            $valor[10]=$_REQUEST['nivel'];
                            $this->funcion->eliminarActividad($this->configuracion);
			}
			if(!isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && isset($_REQUEST["grabobs"]) && !isset($_REQUEST["modbobs"]))
			{
                            //a traves de esta opcion se inserta el registro de observacion
                            $valor[10]=$_REQUEST['nivel'];
                            $this->funcion->grabarObservacion($this->configuracion);
			}
			if(!isset($_REQUEST["opcion"]) && !isset($_REQUEST["borrar"]) && !isset($_REQUEST["grabobs"]) && isset($_REQUEST["modbobs"]))
			{
                            //a traves de esta opcion se modifica el regsitro de observacion
                            $valor[10]=$_REQUEST['nivel'];
                            $this->funcion->ModificarObservacion($this->configuracion);
			}  else {
        			$this->funcion->redireccionarInscripcion($this->configuracion, "registroPlanTrabajo");	
                        }
		}
		else
		{
			$valor[10]=$_REQUEST['nivel'];
			$this->funcion->redireccionarInscripcion($this->configuracion, "registroPlanTrabajo",$valor);	
		}
		
	}
}


// @ Crear un objeto bloque especifico
$esteBloque=new bloque_registro_PlanTrabajo($configuracion);
if(!isset($_REQUEST['jxajax'])){

    include_once("funcion.js.php");
    include_once("css.php");

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
    }
    
}else{
    $esteBloque->jxajax();
}

?>