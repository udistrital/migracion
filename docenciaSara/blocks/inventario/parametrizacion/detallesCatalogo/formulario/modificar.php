<?php

$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarCatalogo";

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {
    //Este se considera un error fatal
    exit;
}

if (!isset($_REQUEST["modificar"]) && !isset($_REQUEST["crear"])) {
    echo "error fatal";
} else {

    $nombre_tabla = $_REQUEST["nombreTabla"];
    $banderaPermiso = false;

    if ($_REQUEST["opcion"] === "modificar") {
        // aqui se obtiene el id a modificar    
        foreach ($_REQUEST as $key => $value) {
            if (strpos($key, "seleccionado") === 0) {
                $valorModificar = $value;
                $banderaPermiso = true;
            }
        }
        $accion = "actualizarParametro";    
    } else {
        $accion = "crearParametro";
        $banderaPermiso = true;
    }

    
    if ($banderaPermiso == true) { //Valida si se seleccionó algún parámetro
        
        $label = $this->getLabels($nombre_tabla);
        $cadena_sql = $this->sql->cadena_sql("obtenerLabelsCombo", $nombre_tabla);
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

            $parametro;

            foreach ($label as $i => $texto) {
                
                $parametro[$i] = '';
                foreach ($label_relacion as $j => $valor) {
                    if (trim($texto) == trim($valor)) {
                        $parametro[$i] = "combo" . $j;
                    }
                }
                
            }
        }

        $tipo_campo = explode(',', $valores_tabla_general[0]["tbl_tipo_campo"]);
        $longitud_campo = explode(',', $valores_tabla_general[0]["tbl_longitud_campo"]);


        //Obtener los valores de modificación
        $cadena_sql = $valores_tabla_general[0]["tbl_consulta_detalles"] . " WHERE " . $valores_tabla_general[0]["tbl_id_tabla"] . "=" . $valorModificar;
        $valorModificar = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        $valorModificar = $valorModificar[0];
        
    } else {
        
        $mensaje = "Seleccione un parámetro para modificar";
        $error = "alerta";
        $this->funcion->mostrarMensaje($mensaje, $error);
        exit;
    }
}
?>
