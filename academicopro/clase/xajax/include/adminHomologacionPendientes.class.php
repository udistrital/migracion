<?

//======= Revisar si no hay acceso ilegal ==============
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}
//======================================================
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/funcionGeneral.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/multiConexion.class.php");
include_once($configuracion["raiz_documento"].$configuracion["clases"]."/html.class.php");

    

 function nombreEstudiante($codEstudiante, $posicion, $anterior,$cod_proyecto) {     

  if (!isset($codEstudiante) || is_null($codEstudiante) || $codEstudiante == "") {
    echo 'Por favor ingrese el código del estudiante';
    exit;
  }

  
  require_once("clase/config.class.php");
  $esta_configuracion = new config();
  $configuracion = $esta_configuracion->variable();
  $funcion = new funcionGeneral();
  //Conectarse a la base de datos
  $acceso_db = new dbms($configuracion);
  $enlace = $acceso_db->conectar_db();
  //$valor = $acceso_db->verificar_variables($valor);

  $html = new html();
  $conexion = new multiConexion();
  $accesoOracle = $conexion->estableceConexion(75, $configuracion);
//echo $cod_proyecto;exit;

  if (isset($enlace)) {
    $busqueda = "SELECT DISTINCT est_nombre NOMBRE,"; 
    $busqueda.= " est_cra_cod COD_PROYECTO, ";
    $busqueda.= " cra_nombre PROYECTO, ";
    $busqueda.= " est_estado_est ESTADO, ";
    $busqueda.= " est_ind_cred MODALIDAD";
    $busqueda.=" FROM acest";
    $busqueda.=" INNER JOIN accra ON acest.est_cra_cod=accra.cra_cod";
    $busqueda.=" INNER JOIN acestado ON estado_cod=est_estado_est";
    $busqueda.=" WHERE est_cod=" . $codEstudiante; 
    if($cod_proyecto){
        $busqueda.=" AND est_cra_cod =" . $cod_proyecto;
        //$busqueda.=" AND estado_activo like '%S%'";
    }
       // echo $busqueda;exit;
    
    

    $resultado = $funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda, "busqueda");  
//echo $resultado[0]['NOMBRE'];exit;
    if (is_array($resultado)) {
      $html = $resultado[0]['NOMBRE'];      
      
    } else
    {
        $html = "Código de estudiante no valido.";
    }

    
    $respuesta = new xajaxResponse();
    $respuesta->addAssign("div_nombreEstudiante" . $anterior.$posicion, "innerHTML", $html);
    $html_proyecto=$resultado[0]['COD_PROYECTO']." - ".$resultado[0]['PROYECTO'];
    $respuesta->addAssign("div_proyecto" . $anterior.$posicion, "innerHTML", $html_proyecto);
    return $respuesta;
  }
}

?>
