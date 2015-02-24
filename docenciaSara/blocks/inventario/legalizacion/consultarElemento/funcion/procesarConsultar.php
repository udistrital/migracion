<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} 

$busqueda = NULL;

if($_REQUEST['placa'])
    $busqueda['placa'] = $_REQUEST['placa'];
if($_REQUEST['serial'])
    $busqueda['serial'] = $_REQUEST['serial'];

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//Este se considera un error fatal
if (!$esteRecursoDB) {
    exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarElementos", $busqueda);
$elementos = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$this->funcion->desplegarElementos($elementos);

?>
