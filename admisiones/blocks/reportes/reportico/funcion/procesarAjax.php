<?php

/**
 * * Importante: Si se desean los datos del bloque estos se encuentran en el arreglo $esteBloque
 */
// Buscar proveedores
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = $esteBloque["nombre"];
$conexion = "aspirantes";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
if (!$esteRecursoDB) {
    echo "Este se considera un error fatal";
    exit;
}

if(isset($_REQUEST['codDepto']))
{    
    $variable['dep_cod']=$_REQUEST['codDepto'];

    $cadena_sql = $this->sql->cadena_sql("gemunicipio", $variable);
    
    $registro = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

    if (is_array($registro)) {

        for($i=0; $i<=count($registro)-1; $i++) {

            $html='<option value="'.$registro[$i][0].'">'.$registro[$i][1].'</option>';
            echo $html;
        }
    } else {
        echo '[{"label":"No encontrado","value":"-1"}]';
    }
}
if(isset($_REQUEST['tipIcfes']))
{
   $html=$_REQUEST['tipIcfes'];
   echo $html;
    
}    