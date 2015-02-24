<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarCatalogo";

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

//---------------Inicio Formulario (<form>)--------------------------------
$atributos["id"] = $nombreFormulario;
$atributos["tipoFormulario"] = "multipart/form-data";
$atributos["metodo"] = "POST";
$atributos["nombreFormulario"] = $nombreFormulario;
$verificarFormulario = "1";
echo $this->miFormulario->formulario("inicio", $atributos);
unset($atributos);


/* * ****************************** */


$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

if (!$esteRecursoDB) {

    //Este se considera un error fatal
    exit;
}

$cadena_sql = $this->sql->cadena_sql("buscarTablas", "");

$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

//var_dump($resultado);

if ($resultado) {
    ?>
    <br>
    <h1>Catálogos</h1>
    <div id='demo'>
        <div id='example_wrapper' class='dataTables_wrapper' role='grid'>
            <table aria-describedby='example_info' class='display dataTable' id='example' border='0' cellpadding='0' cellspacing='0'>
                <thead>
                    <tr role='row'>
                        <th aria-label='Descripcion' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Descripcion</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr><th colspan='1' rowspan='1'>Descripcion</th></tr>
                </tfoot>
                <tbody aria-relevant='all' aria-live='polite' role='alert'>

                    <?php
                    foreach ($resultado as $key => $row) {

                        if (($key % 2) == 0) {
                            ?>
                            <tr class='gradeA odd'>
                                <td class='  sorting_1'><a href='
                                    <?php
                                    $variable = "pagina=detallesCatalogo";
                                    $variable .="&opcion=" . $row['tabla'];
                                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                    echo $variable;
                                    ?>

                                                           '>
                                        <?php echo $row['descripcion']; ?></a></td>
                            </tr>

                            <?php
                        } else {
                            ?>
                            <tr class='gradeA odd'>
                                <td class='  sorting_1'> <a href='
                                                            <?php
                                                            $variable = "pagina=detallesCatalogo";
                                                            $variable .="&opcion=" . $row['tabla'];
                                                            $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                                            echo $variable;
                                                            ?>

                                                            '>

                                    <?php echo $row['descripcion']; ?></a></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <!--div id='example_info' class='dataTables_info'>Showing 1 to 10 of 57 entries</div><div id='example_paginate' class='dataTables_paginate paging_two_button'><a aria-controls='example' id='example_previous' class='paginate_disabled_previous' tabindex='0' role='button'>Previous</a><a aria-controls='example' id='example_next' class='paginate_enabled_next' tabindex='0' role='button'>Next</a></div-->
        </div>
    </div>
    <?php
//---------------Fin formulario (</form>)--------------------------------
    $atributos["verificarFormulario"] = $verificarFormulario;
    echo $this->miFormulario->formulario("fin", $atributos);
}
?>
