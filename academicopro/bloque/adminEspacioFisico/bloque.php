<?

/* --------------------------------------------------------------------------------------------------------------------------
  @ Derechos de Autor: Vea el archivo LICENCIA.txt que viene con la distribucion
  --------------------------------------------------------------------------------------------------------------------------- */
/* --------------------------------------------------------------------------------------------------------------------------
 * @name          bloque.php 
 * @author        Paulo Cesar Coronado
 * @revision      Última revisión 05 de febrero de 2009
  /*--------------------------------------------------------------------------------------------------------------------------
 * @subpackage		admin_equivalencias
 * @package		bloque
 * @copyright    	Universidad Distrital Francisco Jose de Caldas
 * @version      	0.0.0.1
 * @author		Marcela Morales
 * @author		Oficina Asesora de Sistemas
 * @link		N/D
 * @description  	
 *
  /*-------------------------------------------------------------------------------------------------------------------------- */
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

include_once($configuracion["raiz_documento"] . $configuracion["clases"] . "/bloque.class.php");
include_once("funcion.class.php");
include_once("sql.class.php");
include_once("espacioFisico.class.php");

//Clase
class BloqueAdminEspacioFisico extends bloque {

    public function __construct($configuracion) {

        $this->sql = new SqlAdminEspacioFisico();
        $this->funcion = new FuncionAdminEspacioFisico($configuracion, $this->sql);
        $this->espacioFisico = new EspacioFisico($configuracion, $this->sql);

        $this->accesoOracle = $this->funcion->conectarDB($configuracion, "coordinador");
    }

    function html($configuracion) {
        if (isset($_REQUEST['opcion'])) {
            $accion = $_REQUEST['opcion'];

            switch ($accion) {

                case "menu":
                    echo "<br> opcion ";
                    break;

                case "registrar":

                    $espacio = $_REQUEST['espacio'];
                    $valorIngresadoTemp = "";
                    $valorIngresado = explode(";", $_REQUEST['valorIngresado']);
                    $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
                    $atributos = $this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");
                    $cant_atributos = count($atributos);

                    $cantValores = count($valorIngresado);

                    for ($i = 0; $i < $cantValores; $i++) {

                        for ($j = 0; $j < $cant_atributos; $j++) {

                            $nombreCampo = $atributos[$j]['NOM_ID'];
                            $temporal = explode("=", $valorIngresado[$i]);

                            if ($temporal[0] == $nombreCampo) {
                                $valorIngresadoTemp[$j] = array($nombreCampo => $temporal[1]);
                                break;
                            }
                        }
                    }

                    $valorIngresado = $valorIngresadoTemp;

                    $this->espacioFisico->desplegarFormularioRegistro($configuracion, $espacio, $valorIngresado);
                    break;

                case "consultar":
                    $espacio = $_REQUEST['espacio'];
                    $this->espacioFisico->desplegarInformacionConsulta($configuracion, $espacio);
                    break;

                case "desplegarConsulta":

                    $codEspacio = $_REQUEST['codEspacio'];
                    $this->espacioFisico->desplegarInformacionConsultaEFA($configuracion, $codEspacio);

                    break;

                case "modificar":

                    $espacio = $_REQUEST['espacio'];
                    $datosEspacio = explode(";", $_REQUEST['seleccion']);
                    $this->espacioFisico->desplegarInformacionEspacio($configuracion, $datosEspacio, $espacio);

                    break;

                case "eliminar":

                    $espacio = $_REQUEST['espacio'];
                    $datosEspacio = explode(";", $_REQUEST['seleccion']);

                    $this->funcion->eliminarRegistro($configuracion, $datosEspacio, $espacio);
                    break;

                case "listadoRecuperar":

                    $espacio = $_REQUEST['espacio'];
                    $this->espacioFisico->listarEliminados($configuracion, $espacio);
                    break;

                case "recuperar":

                    $datosEspacio = explode(";", $_REQUEST['seleccion']);
                    $espacio = $_REQUEST['espacio'];

                    $this->funcion->recuperarRegistro($configuracion, $datosEspacio, $espacio);
                    break;

                default:
                    /* $espacio = $_REQUEST['espacio'];
                      if($espacio == NULL)
                      $espacio = $_REQUEST['opcion'];
                      $this->espacioFisico->desplegarInformacionConsulta($configuracion, $espacio); */
                    break;
            }
        } else {
            $accion = "nuevo";
            $this->funcion->nuevoRegistro($configuracion);
        }
    }

    function action($configuracion) {
        switch ($_REQUEST['opcion']) {

            case "almacenar":

                $espacioFisico = $_REQUEST;
                $this->funcion->nuevoRegistro($configuracion, $espacioFisico);
                break;

            case "modificar":

                $espacio = $_REQUEST['espacio'];                

                $cadena_espacio = $this->sql->cadena_sql($configuracion, "atributosEspacio", $espacio, "", "");
                $atributos = $this->funcion->ejecutarSQL($configuracion, $this->accesoOracle, $cadena_espacio, "busqueda");
                $cant_atributos = count($atributos);

                for ($j = 0; $j < $cant_atributos; $j++) {

                    $nombre_campo = $atributos[$j]['NOM_ID'];
                    $espacioFisicoNuevo[$j] = array($nombre_campo => $_REQUEST[$nombre_campo]);
                }

                $espacioFisicoAntiguo = explode(";", $_REQUEST['infoEspacio']);
                $cant_espacios = count($espacioFisicoAntiguo);

                for ($i = 0; $i < $cant_espacios; $i++) {

                    for ($j = 0; $j < $cant_atributos; $j++) {

                        $nombre_campo = $atributos[$j]['NOM_ID'];
                        $nombre_campoAntiguo = $atributos[$j]['NOM_BD'];
                        $espacioFisicoNuevo[$j] = array($nombre_campo => $_REQUEST[$nombre_campo]);

                        $temporal = explode("=", $espacioFisicoAntiguo[$i]);
                        if ($temporal[0] == $nombre_campoAntiguo) {
                            $espacioFisicoAntiguoTemp[$j] = array($nombre_campo => $temporal[1]);
                            break;
                        }
                    }
                }
                                
                $espacioFisicoAntiguo = $espacioFisicoAntiguoTemp;
                
                $this->funcion->modificarRegistro($configuracion, $espacio, $espacioFisicoAntiguo, $espacioFisicoNuevo);
                break;
        }
    }

}

// @ Crear un objeto bloque especifico

$esteBloque = new BloqueAdminEspacioFisico($configuracion);
if (!isset($_REQUEST['action'])) {
    $esteBloque->html($configuracion);
} else {
    if (!isset($_REQUEST['confirmar'])) {
        $esteBloque->action($configuracion);
    }
}
?>
