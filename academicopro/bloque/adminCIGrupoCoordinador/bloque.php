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
class bloque_adminCIGrupoCoordinador extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminCIGrupoCoordinador($configuracion);
		$this->sql=new sql_adminCIGrupoCoordinador();
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
                        case "consultar":
                                $this->funcion->consultarGrupos($configuracion);
                        break;

                        case "validar":
                                $this->funcion->validarEstudiante($configuracion);
                        break;

                        case "seleccionado":
                                $this->funcion->consultarGruposSeleccionado($configuracion);
                        break;

                        default:
                            $this->funcion->consultarGrupos($configuracion);
                        break;
                       
		}#Cierre de funcion html
	}
	
	
	function action($configuracion)
	{
//            echo $_REQUEST['opcion']."<br>";
//            echo $_REQUEST['action'];
//            exit;

            switch($_REQUEST['opcion'])
		{
                        case "validar":

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminCIEstudianteCoordinador";
                            $variable.="&opcion=validar";
                            $variable.="&codProyecto=".$_REQUEST["codProyecto"];
                            $variable.="&planEstudio=".$_REQUEST["planEstudio"];
                            $variable.="&nombreProyecto=".$_REQUEST["nombreProyecto"];
                            $variable.="&codEstudiante=".$_REQUEST["codEstudiante"];

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";
                            break;


                        break;
                    
                        case "buscador":

                                $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminCIGrupoCoordinador";
				$variable.="&opcion=seleccionado";
				$variable.="&codEspacio=".$_REQUEST["codigoEA"];
				$variable.="&palabraEA=".$_REQUEST["palabraEA"];
				$variable.="&planEstudioCoor=".$_REQUEST["planEstudioCoor"];
				$variable.="&codProyecto=".$_REQUEST["codProyecto"];

//var_dump($_REQUEST);exit;
                                include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        

				
		}
		
	}
}

// @ Crear un objeto bloque especifico

$obj_aprobar=new bloque_adminCIGrupoCoordinador($configuracion);

if(!isset($_REQUEST['action']))
{
	$obj_aprobar->html($configuracion);
}
else
{
	$obj_aprobar->action($configuracion);
}


?>