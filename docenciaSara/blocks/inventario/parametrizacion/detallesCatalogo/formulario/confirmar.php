<?

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

$miBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$indice = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque") . "/imagen/";

$tab = 1;

$valorCodificado = "pagina=detallesCatalogo";
$valorCodificado.= "&accion=" . $miBloque["nombre"];
$valorCodificado.="&opcion=confirmar";
$valorCodificado.="&nombreTabla=".$_REQUEST["nombre_tabla"];
$valorCodificado.="&tipoAccion=".$_REQUEST["tipoAccion"];
$valorCodificado.="&bloque=" . $miBloque["id_bloque"];
$valorCodificado.="&bloqueGrupo=" . $miBloque["grupo"];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

$this->cadena_sql = $this->sql->cadena_sql("rescatarTemp");

/**
 * La conexiòn que se debe utilizar es la principal de SARA
 */
$conexion = "configuracion";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$resultado = $esteRecursoDB->ejecutarAcceso($this->cadena_sql, "busqueda");

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    //Este se considera un error fatal
    exit;
}


$nombre_tabla = trim($_REQUEST["nombre_tabla"]);
$nombre_tabla = str_replace("\\", "", $nombre_tabla);
$cadena_sql = $this->sql->cadena_sql("consultarValoresTablaGeneral", $nombre_tabla);
$valores_tabla_general = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$label_combo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$label_combo = $label_combo[0];

if ($label_combo["tbl_tabla_relacion"] != null) {
    $label_relacion = explode(',', $label_combo["tbl_label_relacion"]);
    $tabla_relacion = explode(',', $label_combo["tbl_tabla_relacion"]);

    foreach ($tabla_relacion as $key => $string) {
        $cadena_sql = $this->sql->cadena_sql("consultarValoresCombo", trim($string));
        $consulta_combo = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $datos_combo[$key] = $esteRecursoDB->ejecutarAcceso(trim($consulta_combo[0]["cmb_script"]), "busqueda");
    }
}


$cadena_sql = $this->sql->cadena_sql("verLabelsTabla", $nombre_tabla);
$labels = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

$i = 0;
$j = 0;
foreach ($resultado as $key => $value) {
    if (strpos($value["campo"], "campo") === 0) {

        if (strpos(trim($value["valor"]), "*") === 0) {
            $value["valor"] = substr(trim($value["valor"]), 1);
            $datos = $datos_combo[$j];

            if (isset($datos)) {
                
                foreach ($datos as $k => $val) {
                    if ($datos[$k][0] == $value["valor"]) {
                        $value["valor"] = $datos[$k][1];
                    }
                }
                $j++;
            }
        }

        $valores[$i] = $value;
        $i++;
    }
}

$resultado = $valores;
$totalRegistros = count($valores);

//---------------Inicio conexión con la base de datos de inventarios-----------



$cadena_sql = $this->sql->cadena_sql("verLabelsTabla", $_REQUEST["nombre_tabla"]);
$labels = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
$labels = explode(',', $labels[0]["tbl_columnas"]);

//---------------Fin conexión con la base de datos de inventarios-----------

if ($totalRegistros > 0) {

    for ($i = 0; $i < $totalRegistros; $i++) {

        $variable[$resultado[$i]["campo"]] = $resultado[$i]["valor"];
    }
    
    //------------------Division General-------------------------
    $atributos["id"] = "";

    //Formulario para nuevos registros de usuario
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $miBloque["nombre"];
    echo $this->miFormulario->marcoFormulario("inicio", $atributos);


    //-------------Mostrar Datos a Confirmar-----------------------

    $cont = 0;
    foreach ($variable as $clave => $valor) {
        $esteCampo = $clave;

        //-------------Control cuadroTexto-----------------------

        $atributos["tamanno"] = "";
        $atributos["estilo"] = "";
        $atributos["etiqueta"] = $labels[$cont];
        $atributos["texto"] = $valor;
        echo $this->miFormulario->campoTexto($atributos);

        $cont++;
    }

    //------------------Division para los botones-------------------------
    $atributos["id"] = "botones";
    $atributos["estilo"] = "marcoBotones";
    echo $this->miFormulario->division("inicio", $atributos);

    //-------------Control Boton-----------------------
    $esteCampo = "botonAceptar";
    $atributos["id"] = $esteCampo;
    $atributos["verificar"] = "";
    $atributos["verificarFormulario"] = "1";
    $atributos["tipo"] = "boton";
    $atributos["estilo"] = "";
    $atributos["tabIndex"] = $tab++;
    $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->campoBoton($atributos);
    //-------------Fin Control Boton----------------------
   
    //-------------Control Boton-----------------------
    $esteCampo = "botonCancelar";
    $atributos["id"] = $esteCampo;
    $atributos["verificar"] = "";
    $atributos["tipo"] = "boton";
    $atributos["tabIndex"] = $tab++;
    $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
    echo $this->miFormulario->campoBoton($atributos);
    //-------------Fin Control Boton----------------------
    
//------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin", $atributos);

    //-------------Control cuadroTexto con campos ocultos-----------------------
    $atributos["id"] = "formSaraData";
    $atributos["tipo"] = "hidden";
    $atributos["etiqueta"] = "";
    $atributos["valor"] = $valorCodificado;
    echo $this->miFormulario->campoCuadroTexto($atributos);


    //-------------------Fin Division-------------------------------
    echo $this->miFormulario->marcoFormulario("fin", $atributos);exit;
}
?>
