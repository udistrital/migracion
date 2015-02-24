<?

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {

    $miSesion = Sesion::singleton();
    //1. Verificar que el usuario esté registrado en el sistema


    $variable["usuario"] = $_REQUEST["usuario"];
    $variable["tipo"] = $_REQUEST["tipo"];
   //var_dump($_REQUEST);
    $conexion = "laverna";
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    if (!$esteRecursoDB) {

        echo "//Este se considera un error fatal";
        exit;
    }

    $cadena_sql = $this->sql->cadena_sql("buscarUsuario", $variable);
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
  
    if ($registro) {
        //1. Crear una sesión de trabajo
        $estaSesion = $miSesion->crearSesion($registro[0][0]);

        //2. Login crea una variable para hacer control por sesión y no por usuario

        $resultado = $miSesion->setValorSesion('tipo', $_REQUEST["tipo"]);
        $registro[0]["resultado"]=$resultado;
        $registro[0]["sesionID"] = $estaSesion;

        //Redirigir a la página principal del usuario
        $this->funcion->redireccionar($_REQUEST["opcionPagina"], $registro[0]);
        return true;
    }

    // Redirigir a la página de inicio con mensaje de error en usuario/clave
    $this->funcion->redireccionar("paginaPrincipal");
}
?>
