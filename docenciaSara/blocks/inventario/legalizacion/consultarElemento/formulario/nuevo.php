<?php
$unBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarEntrada";

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site");

if ($unBloque["grupo"] == "") {
    $rutaBloque.="/blocks/" . $unBloque["nombre"];
} else {
    $rutaBloque.="/blocks/" . $unBloque["grupo"] . "/" . $unBloque["nombre"];
}

$directorio = $rutaBloque . "/index.php?";
$directorio.= $this->miConfigurador->getVariableConfiguracion("enlace");

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

//Este se considera un error fatal
if (!$esteRecursoDB) {
    exit;
}

$cadena_sql = $this->sql->cadena_sql("consultarEntradas", "");
$entradas = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");

echo "<br> ruta bloque " . $rutaBloque;

if ($entradas) {
    ?>
    <br>
    <h1>Entradas</h1>
    <div id='demo'>
        <div id='example_wrapper' class='dataTables_wrapper' role='grid'>
            <table aria-describedby='example_info' class='display dataTable' id='example' border='0' cellpadding='0' cellspacing='0'>
                <thead>
                    <tr role='row'>
                        <th aria-label='Entrada' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Entrada</th>
                        <th aria-label='Proveedor' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Proveedor</th>
                        <th aria-label='Cuenta Contable' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Cuenta Contable</th>
                        <th aria-label='Concepto' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Concepto</th>
                        <th aria-label='Estado' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Estado</th>
                        <th aria-label='Subtotal' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Subtotal</th>
                        <th aria-label='IVA' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>IVA</th>
                        <th aria-label='Total' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Total</th>
                        <th aria-label='Sel' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>√</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th colspan='1' rowspan='1'>Entrada</th>
                        <th colspan='1' rowspan='1'>Proveedor</th>
                        <th colspan='1' rowspan='1'>Cuenta contable</th>
                        <th colspan='1' rowspan='1'>Concepto</th>
                        <th colspan='1' rowspan='1'>Estado</th>
                        <th colspan='1' rowspan='1'>Subtotal</th>
                        <th colspan='1' rowspan='1'>IVA</th>
                        <th colspan='1' rowspan='1'>Total</th>
                        <th colspan='1' rowspan='1'>√</th>
                    </tr>
                </tfoot>
                <tbody aria-relevant='all' aria-live='polite' role='alert'>

                    <?php
                    foreach ($entradas as $key => $row) {

                        if (($key % 2) == 0) {
                            ?>
                            <tr class='gradeA odd'>
                                <td class="sorting_1"><?php echo $row['entrada']; ?></td>
                                <td class="sorting_1"><?php echo $row['proveedor']; ?></td>
                                <td></td>
                                <td class="sorting_1"><?php echo $row['concepto']; ?></td>
                                <td class="sorting_1"><?php echo $row['estado']; ?></td>
                                <td class="sorting_1"><?php echo $row['neto']; ?></td>
                                <td class="sorting_1"><?php echo $row['iva']; ?></td>
                                <td class="sorting_1"><?php echo $row['total']; ?></td>
                                <td><input type="checkbox"/></td>
                            </tr>

                            <?php
                        } else {
                            ?>
                            <tr class='gradeA even'>
                                <td class="sorting_1"><?php echo $row['entrada']; ?></td>
                                <td class="sorting_1"><?php echo $row['proveedor']; ?></td>
                                <td></td>
                                <td class="sorting_1"><?php echo $row['concepto']; ?></td>
                                <td class="sorting_1"><?php echo $row['estado']; ?></td>
                                <td class="sorting_1"><?php echo $row['neto']; ?></td>
                                <td class="sorting_1"><?php echo $row['iva']; ?></td>
                                <td class="sorting_1"><?php echo $row['total']; ?></td>
                                <td><input type="checkbox"/></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>           
            <div id="verDetalle">
                Ver detalle entrada
                <button name="DetalleEntrada" type="submit"><img src="<?php echo $rutaBloque . "/css/images/glyphicons_027_search.png" ?>" href="<?php
                $variable = "pagina=verDetalleEntrada"; //pendiente la pagina para modificar parametro                                                        
                $variable.="&opcion=verDetalle";
                $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                echo $variable;
                ?>" /></button>
            </div>
        </div>
    </div>
    <?php
//---------------Fin formulario (</form>)--------------------------------
    $atributos["verificarFormulario"] = $verificarFormulario;
    echo $this->miFormulario->formulario("fin", $atributos);
}
?>
