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

    

    function nombreEstudiante($codEstudiante, $posicion,$nivel,$usuario) {

 /* if (!isset($codEstudiante) || is_null($codEstudiante) || $codEstudiante == "") {
    echo 'Por favor ingrese el código del estudiante';
    exit;
  }
*/
  
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

  if (isset($enlace)) {
    if($nivel==4||$nivel==28){
        $listado_proyectos='';
        $cadena_sql="SELECT cra_cod, ";
        $cadena_sql.="cra_nombre  ";
        $cadena_sql.="FROM accra  ";
        $cadena_sql.="WHERE CRA_EMP_NRO_IDEN = ". $usuario;
        $cadena_sql.=" AND cra_estado = 'A'";
        $resultado1 = $funcion->ejecutarSQL($configuracion, $accesoOracle, $cadena_sql, "busqueda");
        if($resultado1){
            foreach ($resultado1 as $proyecto) {
                if(!$listado_proyectos){
                    $listado_proyectos=$proyecto['CRA_COD'];                            
                }else{
                    $listado_proyectos.=",".$proyecto['CRA_COD'];        
                }
            }
        }
    }                     
      
    $busqueda = "SELECT DISTINCT est_nombre NOMBRE,"; 
    $busqueda.= " est_cra_cod COD_PROYECTO, ";
    $busqueda.= " cra_nombre PROYECTO, ";
    $busqueda.= " est_estado_est COD_ESTADO, ";
    $busqueda.= " estado_nombre ESTADO, ";
    $busqueda.= " est_ind_cred MODALIDAD";
    $busqueda.=" FROM acest";
    $busqueda.=" INNER JOIN accra ON acest.est_cra_cod=accra.cra_cod";
    $busqueda.=" INNER JOIN acestado ON estado_cod=est_estado_est";
    $busqueda.=" WHERE est_cod=" . $codEstudiante;
    if($nivel==4||$nivel==28){
        $busqueda.=" AND est_cra_cod in (" . $listado_proyectos.")";
    }
    $resultado = $funcion->ejecutarSQL($configuracion, $accesoOracle, $busqueda, "busqueda");
    if (is_array($resultado)) {
      $html = $resultado[0]['NOMBRE'];
        //echo $html ;exit;
    } else
    {
        $html = "Código de estudiante no valido.";
    }

    $respuesta = new xajaxResponse();
    //echo "div_nombreEstudiante".$posicion;//exit;
    $respuesta->addAssign("div_nombreEstudiante" . $posicion, "innerHTML", $html);
    $html_estado=$resultado[0]['COD_ESTADO']." - ".$resultado[0]['ESTADO'];
    $respuesta->addAssign("div_estadoEstudiante" . $posicion, "innerHTML", $html_estado);
    $html_proyecto=$resultado[0]['COD_PROYECTO']." - ".$resultado[0]['PROYECTO'];
    $respuesta->addAssign("div_proyecto" . $posicion, "innerHTML", $html_proyecto);
    
    return $respuesta;
  }
}

?>