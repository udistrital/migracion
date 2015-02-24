<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}

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

if ($elementos) {
    ?>
    <form name="formulario" method="post" enctype="multipart/form-data">    
        <br>
        <h1>Elementos</h1>
        <div id='demo'>
            <div id='example_wrapper' class='dataTables_wrapper' role='grid'>
                <table aria-describedby='example_info' class='display dataTable' id='example' border='0' cellpadding='0' cellspacing='0'>
                    <thead>
                        <tr role='row'>
                            <th aria-label='Tipo Elemento' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Tipo Elemento</th>
                            <th aria-label='Salida' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Salida</th>
                            <th aria-label='Cantidad' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Cantidad</th>
                            <th aria-label='Precio' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Precio</th>
                            <th aria-label='Codigo Barras' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Código Barras</th>
                            <th aria-label='IVA' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>IVA</th>
                            <th aria-label='Descripcion' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Descripción</th>
                            <th aria-label='Marca' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Marca</th>
                            <th aria-label='Serie' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Serie</th>
                            <th aria-label='Descuento' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Descuento</th>
                            <th aria-label='Total' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>Total</th>
                            <th aria-label='Sel' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'>√</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th colspan='1' rowspan='1'>Tipo Elemento</th>
                            <th colspan='1' rowspan='1'>Salida</th>
                            <th colspan='1' rowspan='1'>Cantidad</th>
                            <th colspan='1' rowspan='1'>Precio</th>
                            <th colspan='1' rowspan='1'>Código Barras</th>
                            <th colspan='1' rowspan='1'>IVA</th>
                            <th colspan='1' rowspan='1'>Descripción</th>
                            <th colspan='1' rowspan='1'>Marca</th>
                            <th colspan='1' rowspan='1'>Serie</th>
                            <th colspan='1' rowspan='1'>Descuento</th>
                            <th colspan='1' rowspan='1'>Total</th>
                            <th colspan='1' rowspan='1'>√</th>
                        </tr>
                    </tfoot>
                    <tbody aria-relevant='all' aria-live='polite' role='alert'>

                        <?php
                        foreach ($elementos as $key => $row) {

                            $totalCant = $row["cantidad"] * $row["precio"];
                            $total = (($totalCant * $row["iva"]) / 100) + $totalCant;

                            if (($key % 2) == 0) {
                                ?>
                                <tr class='gradeA odd'>
                                    <td class="sorting_1"><?php echo $row['nombre_suministro']; ?></td>
                                    <td class="sorting_1"><?php echo $row['salida']; ?></td>
                                    <td class="sorting_1"><?php echo $row['cantidad']; ?></td>
                                    <td class="sorting_1"><?php echo $row['precio']; ?></td>
                                    <td class="sorting_1"><?php echo $row['cod_barras']; ?></td>
                                    <td class="sorting_1"><?php echo $row['iva']; ?></td>
                                    <td class="sorting_1"><?php echo $row['descr']; ?></td>
                                    <td class="sorting_1"><?php echo $row['marca']; ?></td>
                                    <td class="sorting_1"><?php echo $row['serie']; ?></td>
                                    <td class="sorting_1"><?php echo $row['descuento']; ?></td>
                                    <td class="sorting_1"><?php echo $total; ?></td>
                                    <td><input type="checkbox"/></td>
                                </tr>

                                <?php
                            } else {
                                ?>
                                <tr class='gradeA even'>
                                    <td class="sorting_1"><?php echo $row['nombre_suministro']; ?></td>
                                    <td class="sorting_1"><?php echo $row['salida']; ?></td>
                                    <td class="sorting_1"><?php echo $row['cantidad']; ?></td>
                                    <td class="sorting_1"><?php echo $row['precio']; ?></td>
                                    <td class="sorting_1"><?php echo $row['cod_barras']; ?></td>
                                    <td class="sorting_1"><?php echo $row['iva']; ?></td>
                                    <td class="sorting_1"><?php echo $row['descr']; ?></td>
                                    <td class="sorting_1"><?php echo $row['marca']; ?></td>
                                    <td class="sorting_1"><?php echo $row['serie']; ?></td>
                                    <td class="sorting_1"><?php echo $row['descuento']; ?></td>
                                    <td class="sorting_1"><?php echo $total; ?></td>
                                    <td><input type="checkbox"/></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                    </tbody>                    
                </table> 
                <table>
                    <tr>
                        <td>
                            <div id="verHistórico">
                                Historico Elemento
                                <button name="HistoricoElemento" type="submit"><img src="<?php echo $rutaBloque . "/css/images/glyphicons_071_book.png" ?>" href="<?php
                                    $variable = "pagina=historicoElemento"; //pendiente la pagina para modificar parametro                                                        
                                    $variable.="&opcion=historico";
                                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                    echo $variable;
                                    ?>" /></button>
                            </div>
                        </td>
                        <td>
                            <div id="Modificar">
                                Modificar
                                <button name="ModificarElemento" type="submit"><img src="<?php echo $rutaBloque . "/css/images/glyphicons_150_edit.png" ?>" href="<?php
                                    $variable = "pagina=modificarElemento"; //pendiente la pagina para modificar parametro                                                        
                                    $variable.="&opcion=modificar";
                                    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);
                                    echo $variable;
                                    ?>" /></button>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
    <?php
} else {
    echo "<br> No se encontraron datos de los elementos ";
}
?>