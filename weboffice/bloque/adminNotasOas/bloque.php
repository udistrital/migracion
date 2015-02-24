<?
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;		
}

include_once($configuracion["raiz_documento"].$configuracion["clases"]."/bloque.class.php");

include_once("funcion.class.php");
include_once("sql.class.php");
//echo "<br>sdasd________";


class bloque_adminNotasOas extends bloque
{
    
	 public function __construct($configuracion)
	{// echo "<br>constructor bloque ";
            $this->sql=new sql_adminNotasOas();
            $this->funcion=new funcion_adminNotasOas($configuracion, $this->sql);
          //echo "instancia funcion";

   	}
	
	function html($configuracion)
	{
                /**
                 * @global string $_REQUEST['opcion'] variable que contiene la opcion para ser dirijido a una funcion especifica
                 */
             
		if(!isset($_REQUEST['opcion']))
		{
			$_REQUEST['opcion']="mostrar";
		}
		//echo "variable opcion".$_REQUEST['opcion'];exit;
		switch($_REQUEST['opcion'])
		{
                        case "mostrar":
                            $this->funcion->consulta_asignaturas($configuracion);
                            break;

                        case "consulta_notas":
                            $this->funcion->consulta_notas($configuracion);
                            break;

                        case "importar":
                            $this->funcion->importar($configuracion);
                            break;

                        case "importarAsi":
                            $this->funcion->importarAsi($configuracion);
                            break;

                        case "consultaNotasAsignatura":
                            $this->funcion->consulta_asig($configuracion);
                            break;

                        case "consulta_notas_x_asi":
                            $this->funcion->consulta_notas_x_asi($configuracion);
                            break;

			default:
			    $this->funcion->consulta_asignaturas($configuracion);
			break;
		}
	}
	
	/**
         * Funcion action que se encarga de capturar las variables que vienen de un formulario html
         *
         * Esta funcion se encarga de capturar las variables enviadas por un formulario html, una vez la captura lo redirije a la funcion html del bloque que se especifica
         *
         * @param array $configuracion Arreglo que contiene todas las variables de configuracion que se encuentre en la tabla de configuracion del framework
         */
	function action($configuracion)
	{

            switch($_REQUEST['opcion'])
		{
                       case "importar":
                           $asi_cod=$_REQUEST['asi_cod'];
                           $grupo=$_REQUEST['grupo'];
                           $accion=$_REQUEST['accion'];
                           $tipo=$_REQUEST['tipo'];

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminNotasOas";
                            $variable.="&opcion=importar";
                            $variable.="&asi_cod=".$asi_cod;
                            $variable.="&grupo=".$grupo;
                            $variable.="&accion=".$accion;
                            $variable.="&tipo=".$tipo;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";

                           break;

                       case "importarAsi":
                           $asi_cod=$_REQUEST['asi_cod'];
                           $accion=$_REQUEST['accion'];
                           $tipo=$_REQUEST['tipo'];

                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminNotasOas";
                            $variable.="&opcion=importarAsi";
                            $variable.="&asi_cod=".$asi_cod;
                            $variable.="&accion=".$accion;
                            $variable.="&tipo=".$tipo;

                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";

                           break;

                       case "consultaNotasAsignatura":
                          
                            $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
                            $variable="pagina=adminNotasOas";
                            $variable.="&opcion=consultaNotasAsignatura";
                            
                            include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
                            $this->cripto=new encriptar();
                            $variable=$this->cripto->codificar_url($variable,$configuracion);

                            echo "<script>location.replace('".$pagina.$variable."')</script>";

                           break;

		}
		
	}
}
/**
 * Crea un nuevo objeto de la clase bloque_adminInscripcionCoordinador
 *
 * Se instancia la clase bloque_adminInscripcionCoordinador y se envia a la funcion que se necesita dependiendo si existe la variable $_REQUEST['action'] o no
 */


$obj_login=new bloque_adminNotasOas($configuracion);
//echo "ppp";
//echo "cancelar ".$_REQUEST['cancelar'];


if (isset ($_REQUEST['cancelar'])){
     unset ($opcion);
     $pagina=$configuracion["host"].$configuracion["site"]."/index.php?";
     $variable="pagina=adminNotasOas";
    
     include_once($configuracion["raiz_documento"].$configuracion["clases"]."/encriptar.class.php");
     $this->cripto=new encriptar();
     $variable=$this->cripto->codificar_url($variable, $configuracion);

     echo "<script>location.replace('".$pagina.$variable."')</script>";
}

if(!isset($_REQUEST['action']))
{
	$obj_login->html($configuracion);
}
else
{
	$obj_login->action($configuracion);
}


?>