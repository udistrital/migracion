<?

include_once("core/manager/Configurador.class.php");
include_once("core/builder/FormularioHtml.class.php");

class FronteradetallesCatalogo {

    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $formulario;
    var $miConfigurador;
    var $esteRecursoDB;
    var $miFormulario;

    function __construct() {

        
        $this->miConfigurador = Configurador::singleton();
        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        $this->miFormulario = new formularioHtml();
    }

    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }

    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }

    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

    function frontera() {
        $this->html();
    }

    function setSql($a) {
        $this->sql = $a;
    }

    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }

    function eliminarParametro() {
        include_once($this->ruta . "/formulario/eliminar.php");
    }

    function modificarParametro() {
        include_once($this->ruta . "/formulario/modificar.php");
        $this->funcion->pintarFormularioModificacion($label, $parametro, $datos_combo, $nombre_tabla, $accion, $tipo_campo, $longitud_campo, $valorModificar);
    }

    function crearParametro() {
        include_once($this->ruta . "/formulario/modificar.php");
        $this->funcion->pintarFormularioModificacion($label, $parametro, $datos_combo, $nombre_tabla, $accion, $tipo_campo, $longitud_campo, $valorModificar);
    }

    function confirmar() {
        include_once($this->ruta . "/formulario/confirmar.php");
    }

    function extraerDatosEliminacionModificacion($texto) {
        $ruta = $this->miConfigurador->fabricaConexiones->crypto->decodificar($texto);
        $arreglo = explode('&', $ruta);

        foreach ($arreglo as $key => $value) {
            $arreglo[$key] = explode('=', $value);
        }

        return $arreglo;
    }

    function setConexion($conexion) {

        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        if (!$esteRecursoDB) {
            //Este se considera un error fatal
            exit;
        } else {
            $this->esteRecursoDB = $esteRecursoDB;
        }
    }

    function html() {

        if (isset($_REQUEST["modificar"])) {

            $arreglo = $this->extraerDatosEliminacionModificacion($_REQUEST["modificar"]);
            $_REQUEST["opcion"] = 'modificar';
        } else if (isset($_REQUEST["eliminar"])) {

            $arreglo = $this->extraerDatosEliminacionModificacion($_REQUEST["eliminar"]);
            $_REQUEST["opcion"] = 'eliminar';
        } else if (isset($_REQUEST["crear"])) {

            $arreglo = $this->extraerDatosEliminacionModificacion($_REQUEST["crear"]);
            $_REQUEST["opcion"] = 'crear';
        }

        if (isset($arreglo)) {

            foreach ($arreglo as $key => $value) {
                if ($value[0] == 'nombreTabla') {
                    $_REQUEST["nombreTabla"] = $value[1];
                }
            }
        }

        switch ($_REQUEST["opcion"]) {

            case "eliminar":
                $this->eliminarParametro();
                break;

            case "modificar":
                $this->modificarParametro();
                break;

            case "crear":
                $this->crearParametro();
                break;

            case "confirmar":
                $this->confirmar();
                break;

            default:
                include_once("core/builder/FormularioHtml.class.php");
                $this->setConexion("inventario");
                $this->verdetallesCatalogo();
                break;
        }
    }

    /**
     * Método para ver los detalles o parámetros de un catálogo seleccionado
     * @param type $nombre_tabla_bd Nombre de la tabla que se consulta en la base de datos
     * @param type $nombre_tabla Nombre de la tabla tranformado de manera entendible para el usuario
     */
    function verdetallesCatalogo() {

        $nombre_tabla_bd = trim($_REQUEST["opcion"]);
        $cadenaSql = $this->sql->cadena_sql("verDetallesCamposTabla", $nombre_tabla_bd);
        $titulos_filas = $this->esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

        $nombre_tabla = $titulos_filas[0]['tbl_nombre_tabla'];
        $modificable = $titulos_filas[0]["tbl_modificable"];
        $cadenaSql = $titulos_filas[0]["tbl_consulta_detalles"];
        $registro = $this->esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        $titulos_filas = $this->obtenerTitulos($titulos_filas[0]['tbl_columnas']);

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        include_once($this->ruta . "/formulario/verDetalle.php");
    }

    /**
     * 
     * @param $titulos cadena para trasformar en array
     * @return arreglo con los titulos 
     */
    function obtenerTitulos($titulos) {
        $cantidadTitulos = substr_count($titulos, ',');

        for ($i = 0; $i < $cantidadTitulos; $i++) {
            $var = trim(substr($titulos, 0, strpos($titulos, ',')));
            $titulos = trim(substr($titulos, strpos($titulos, ',') + 1));
            $titulosArray[$i] = $var;
        }

        $titulosArray[$i] = $titulos;
        return $titulosArray;
    }

    /**
     * Método para realizar la consulta de los labels o encabezados de las tablas
     * @param type $nombre_tabla
     * @return type
     */
    function getLabels($nombre_tabla) {
        $conexion = "inventario";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $cadena_sql = $this->sql->cadena_sql("verLabelsTabla", $nombre_tabla);
        $label = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $label = explode(',', $label[0]["tbl_columnas"]);
        return $label;
    }

}

?>
