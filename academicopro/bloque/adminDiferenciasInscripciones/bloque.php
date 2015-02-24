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
class bloque_adminDiferenciasInscripciones extends bloque
{

	 public function __construct($configuracion)
	{	include ($configuracion["raiz_documento"].$configuracion["estilo"]."/basico/tema.php");
		$this->tema=$tema;
		$this->funcion=new funcion_adminDiferenciasInscripciones($configuracion);
		$this->sql=new sql_adminDiferenciasInscripciones();
	}
	
	
	function html($configuracion)
	{
		//$this->acceso_db=$this->funcion->conectarDB($configuracion);
		// @ Crear un objeto de la clase funcion
		
//			var_dump($_REQUEST);
//                        echo "<br>usuario".$usuario;
//                        exit;
	
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="nuevo";
		}
		
		switch($_REQUEST['opcion'])
		{
                    
                        case "seleccionarPlan":
				$this->funcion->seleccionPlan($configuracion);
				break;

                        case "seleccionar":
				$this->funcion->seleccion($configuracion);
				break;

			case "diferencias":
				$this->funcion->diferencias($configuracion);
				break;

			case "noOracle":
				$this->funcion->noOracle($configuracion);
				break;
			
			case "noMysql":
				$this->funcion->noMysql($configuracion);
				break;

			case "reporte":
				$this->funcion->reporte($configuracion);
				break;

			default:
				$this->funcion->nuevoRegistro($configuracion);
				break;	
		}
	}
	
	
	function action($configuracion)
	{
                switch($_REQUEST['opcion'])
		{
			
                        case "diferencias":


				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminDiferenciasInscripciones";
				$variable.="&opcion=diferencias";
				$variable.="&planEstudio=".$_REQUEST['planEstudio'];

				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "noOracle":


				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminDiferenciasInscripciones";
				$variable.="&opcion=noOracle";
				$variable.="&planEstudio=".$_REQUEST['planEstudio'];


				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "asignaturas":


				$pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
				$variable="pagina=adminDiferenciasEspacios";
				$variable.="&opcion=asignaturas";
				$variable.="&planEstudio=".$_REQUEST['planEstudio'];


				include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
				$this->cripto=new encriptar();
				$variable=$this->cripto->codificar_url($variable,$configuracion);

				echo "<script>location.replace('".$pagina.$variable."')</script>";
				break;

                        case "seleccionarPlan":
                            
				$this->funcion->seleccionPlan($configuracion);
				break;


		}
		
	}
	
	
}


// @ Crear un objeto bloque especifico

$esteBloque=new bloque_adminDiferenciasInscripciones($configuracion);

if(!isset($_REQUEST['action']))
{
	$esteBloque->html($configuracion);
}
else
{
	$esteBloque->action($configuracion);
}


?>