<?
/*--------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
---------------------------------------------------------------------------------------------------------------------------*/

if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}
 
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
//Clase
class bloque_admin_consejeriasEstudiante extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_admin_consejeriasEstudiante($configuracion);
		$this->sql=new sql_admin_consejeriasEstudiante();
	}
	
	
	function html($configuracion)
	{
		$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                        case "verMensajes":
                                $this->funcion->datosEstudianteConsejerias($configuracion);
                        break;

                        case "verEstudiante":
                                $this->funcion->consultarEstudianteCohorte($configuracion);
                        break;

                        case "asociarNuevos":
                                $this->funcion->asociarEstudiantesCohorte($configuracion);
                        break;
                    
                        case "desasociarEst":
                                $this->funcion->desasociarEstudiantes($configuracion);
                        break;

                        case "guardarAsociacion":
                                $this->funcion->guardarAsociacionEstudiante($configuracion);
                        break;

                        case "borrarAsociacion":
                                $this->funcion->borrarAsociacionEstudiante($configuracion);
                        break;

                        default:
				$this->funcion->consultarEstudianteCohorte($configuracion);
			break;
		}#Cierre de funcion html
	}
	
	
	function action($configuracion)
	{

            switch($_REQUEST['opcion'])
		{
                        case "guardarAsociacion":
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=registroAsociarEstudianteConsejeria";
                            $variable.="&opcion=guardarAsociacion";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codDocente=".$_REQUEST["codDocente"];
                            $variable.="&nombreDocente=".$_REQUEST["nombreDocente"];
                            $j=0;
                            for($i=0;$i<100;$i++)
                            {
                                if(isset($_REQUEST['estudiante'.$i]))
                                    {
                                        $variable.="&estudiante".$j."=".$_REQUEST['estudiante'.$i];
                                        $j++;
                                    }
                            }
                            $variable.="&totalSeleccionados=".$j;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;
                        
                        case "borrarAsociacion":
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=registroAsociarEstudianteConsejeria";
                            $variable.="&opcion=borrarAsociacion";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codDocente=".$_REQUEST["codDocente"];
                            $variable.="&nombreDocente=".$_REQUEST["nombreDocente"];
                            $j=0;
                            for($i=0;$i<100;$i++)
                            {
                                if(isset($_REQUEST['estudiante'.$i]))
                                    {
                                        $variable.="&estudiante".$j."=".$_REQUEST['estudiante'.$i];
                                        $j++;
                                    }
                            }
                            $variable.="&totalSeleccionados=".$j;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;

                        
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_admin_consejeriasEstudiante($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>