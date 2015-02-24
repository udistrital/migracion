<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/
/*--------------------------------------------------------------------------------------------------------------------------
* @name          bloque.php 
* @revision      Última revisión 05 de febrero de 2009
/*--------------------------------------------------------------------------------------------------------------------------
* @subpackage		registro_inscripcion
* @package		bloques
* @copyright    	Universidad Distrital Francisco Jose de Caldas
* @version      		0.0.0.3
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
class bloque_registroCargaAcademica extends bloque
{

	 public function __construct($configuracion)
	{
 		$this->sql=new sql_registroCargaAcademica();
 		$this->funcion=new funciones_registroCargaAcademica($configuracion, $this->sql);
 		
	}
	
	
	function html($configuracion)
	{
		if(!isset($_REQUEST['cancelar']))
		{
			if(isset($_REQUEST['opcion']))
			{
				$accion=$_REQUEST['opcion'];
				
				switch($accion)
				{
					case "dignotasPregrado":
						$this->funcion->digitarNotasPregrado($configuracion);
						break;
					case "mensajes":
						$this->funcion->mensajesErrores($configuracion);
						break;
					case "duplicaCarga":
						$this->funcion->duplicarCarga($configuracion);
						break;
					case "mostrarGrilla":
						$this->funcion->verGrilla($configuracion);
						break;
					case "reportes":
						$this->funcion->verReportes($configuracion);
						break;
					case "borrar":
						$this->funcion->borrarCarga($configuracion);
						break;
					case "ListasCursos":
						$this->funcion->verListaCursos($configuracion);
						break;
					case "ListaDocentes":
						$this->funcion->verListaDocentes($configuracion);
						break;
					case "nuevo":
						$this->funcion->seleccionarPeriodo($configuracion);
						break;
					case "validaProyecto":
						$this->funcion->validaFormularioCurso($configuracion);
						break;
					case "registrarCarga":
						$this->funcion->registrarCarga($configuracion);
						break;
					case "borrarCarga":
						$this->funcion->eliminarCarga($configuracion);
						break;
					case "verListaDocentes":
						$this->funcion->verListaDocentes($configuracion);
						break;
					case "registrarCargaDocentes":
						$this->funcion->registrarCargaDocentes($configuracion);
						break;
					case "confirmarHoras":
						$this->funcion->confirmaNumHoras($configuracion);
						break;
					case "registroExitosoCursos":
						$this->funcion->registrarCargaDocentes($configuracion);
						break;
				}
			}
			else
			{
				$accion="nuevo";
				$this->funcion->seleccionarPeriodo($configuracion);      
				//$this->funcion->verListaCursos($configuracion,$conexion);
			}
		}
		else
		{
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado");	
		}
	}
	
	function action($configuracion)
	{
		$this->funcion->revisarFormulario();
		
		$tipo="busqueda";
			
		//Rescatar datos de sesion
		$usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "usuario");
		$id_usuario=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "id_usuario");
		$_REQUEST["registro"]=$this->funcion->rescatarValorSesion($configuracion, $this->funcion->acceso_db, "identificacion");
		if(!isset($_REQUEST['cancelar']))
		{
			$valor[10]=$_REQUEST['nivel'];
			$valor[5]= isset($_REQUEST['proyecto'])?$_REQUEST['proyecto']:'';
			$valor[6]= isset($_REQUEST['curso'])?$_REQUEST['curso']:'';
			$valor[7]=isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
			$valor[8]= isset($_REQUEST['identificacion'])?$_REQUEST['identificacion']:'';
			$valor[9]= isset($_REQUEST['nombres'])?$_REQUEST['nombres']:'';
			$valor[11]= isset($_REQUEST['tipVin'])?$_REQUEST['tipVin']:'';
			$valor[15]= isset($_REQUEST['apellidos'])?$_REQUEST['apellidos']:'';
			$valor[12]= isset($_REQUEST['consultaDocente'])?$_REQUEST['consultaDocente']:'';
			$valor[20]= isset($_REQUEST['formulario'])?$_REQUEST['formulario']:'';
			//echo "mmm".$_REQUEST['cursos']."<br>";
			//echo "mmm".$valor[15];
			//exit;
			$accion=$_REQUEST['opcion'];
			switch($accion)
			{
				case "seleccionarPeriodo":
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=registroCargaAcademica";
					$variable.="&opcion=ListasCursos";
					$variable.="&nivel=".$_REQUEST["nivel"];
					//$variable.="&pro=".$_REQUEST["plan"];

					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
				case "seleccionarProyecto":
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=registroCargaAcademica";
					$variable.="&opcion=validaProyecto";
					$variable.="&nivel=".$_REQUEST["nivel"];
					$variable.="&anio=".$_REQUEST["anio"];
					$variable.="&per=".$_REQUEST["per"];
					$variable.="&proyecto=".$_REQUEST["proyecto"];
					$variable.="&curso=".$_REQUEST["curso"];
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;  
				case "registrar":
					$this->funcion->registrarCarga($configuracion);
					//$this->funcion->redireccionarInscripcion($configuracion,"registrarCarga",$valor);
				break;
				case "borrarCarga":
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=registroCargaAcademica";
					$variable.="&opcion=borrarCarga";
					$variable.="&nombre=".$_REQUEST["nombre"];
					$variable.="&nombres=".$valor[9];
					$variable.="&apellidos=".$valor[15];
					$variable.="&nivel=".$_REQUEST["nivel"];
					$variable.="&anio=".$_REQUEST["anio"];
					$variable.="&per=".$_REQUEST["per"];
					$variable.="&carrera=".$_REQUEST["carrera"];
					$variable.="&curso=".$_REQUEST["curso"];
					$variable.="&grupo=".$_REQUEST["grupo"];
					$variable.="&docente=".$_REQUEST["docente"];
					$variable.="&nombreCurso=".$_REQUEST["nombreCurso"];
					$variable.="&tipoFormulario=".$_REQUEST["tipoFormulario"];
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
				case "duplicar":
					$this->funcion->EjecutarDuplicadoCarga($configuracion, $accesoOracle,$acceso_db);
				break;
				case "consultaDocente":
					if($_REQUEST["tipo"]=="docentes")
					{
						$this->funcion->redireccionarInscripcion($configuracion,"verListaDocentes",$valor);
					}
					else
					{
						$this->funcion->redireccionarInscripcion($configuracion,"mostrarGrilla",$valor);
					}
				break;
				case "enviarDocentes":
					$nombre= isset($_REQUEST['nombre'])?$_REQUEST['nombre']:'';
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=registroCargaAcademica";
					$variable.="&opcion=registrarCargaDocentes";
					$variable.="&nombre=".$nombre;
					$variable.="&nivel=".$_REQUEST["nivel"];
					$variable.="&anio=".$_REQUEST["anio"];
					$variable.="&per=".$_REQUEST["per"];
					$variable.="&proyecto=".$_REQUEST["proyecto"];
					$variable.="&tipVin=".$_REQUEST["tipVin"];
					$variable.="&docente=".$_REQUEST["docente"];
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
				case "registrarCursos":
					$valor[6]= isset($_REQUEST['curso'])?$_REQUEST['curso']:'';
					$valor[7]=isset($_REQUEST['grupo'])?$_REQUEST['grupo']:'';
					$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
					$variable="pagina=registroCargaAcademica";
					$variable.="&opcion=confirmarHoras";
					$variable.="&usuario=".$_REQUEST["usuario"];
					$variable.="&nivel=".$_REQUEST["nivel"];
					$variable.="&anio=".$_REQUEST["anio"];
					$variable.="&per=".$_REQUEST["per"];
					$variable.="&carrera=".$_REQUEST["carrera"];
					$variable.="&curso=".$valor[6];
					$variable.="&cursos=".$_REQUEST["cursos"];
					$variable.="&grupo=".$valor[7];
					$variable.="&docente=".$_REQUEST["docente"];
					$variable.="&tipVin=".$_REQUEST["tipVin"];
					$variable.="&numhoras=".$_REQUEST["numhoras"];
					include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
					$this->cripto=new encriptar();
					$variable=$this->cripto->codificar_url($variable,$configuracion);
					echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;
			}
		}
		else
		{
			$valor[10]=$_REQUEST['nivel'];
			$this->funcion->redireccionarInscripcion($configuracion, "formgrado",$valor);	
		}
		
	}
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_registroCargaAcademica($configuracion);
//echo $_REQUEST['action'];
if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	if(!isset($_REQUEST['confirmar']))
	{
		$esteBloque->action($configuracion);
	}
}


?>