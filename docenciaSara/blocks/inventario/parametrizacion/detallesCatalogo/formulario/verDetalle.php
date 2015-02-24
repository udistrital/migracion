<?php
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "verDetallesCatalogo";

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/inventario/imagenes";

if ($registro) {
    ?>
    <script>
        $(document).ready(function(){

            /* Build the DataTable with third column using our custom sort functions */
            $('#example').dataTable( {
                "aaSorting": [ [0,'asc'], [1,'asc'] ],
                "aoColumns": [
                    { "sType": 'string-case' },
    <? for ($i = 0; $i < count($titulos_filas); $i++) { ?>
                        { "sType": 'string-case' },
    <? } ?>
                ]
            } );
        });
    </script>
    <br> 
    <form id="formulario" name="formulario" method="post" enctype="multipart/form-data">     
        <h1>Catálogo <?= $nombre_tabla ?></h1>
        <div id='demo'>
            <div id='example_wrapper' class='dataTables_wrapper' role='grid'>
                <table aria-describedby='example_info' class='display dataTable' id='example' border='0' cellpadding='0' cellspacing='0'>
                    <thead>
                        <tr role='row'>                            
                            <?php foreach ($titulos_filas as $key => $value) { ?>
                                <th aria-label='Descripcion' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'><?= $value ?></th>
                            <? } ?>
                            <th aria-label='Descripcion' aria-sort='ascending' style='width: 128px;' colspan='1' rowspan='1' aria-controls='example' tabindex='0' role='columnheader' class='sorting_asc'></th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>                            
                            <?php foreach ($titulos_filas as $key => $value) { ?>
                                <th colspan='1' rowspan='1'><?= $value ?></th>
                            <? } ?>
                            <th colspan='1' rowspan='1'></th>
                    </tfoot>
                    <tbody aria-relevant='all' aria-live='polite' role='alert'>

                        <?php
                        foreach ($registro as $key => $row) {
                            ?>
                            <tr class='gradeA odd'>                                
                                <? for ($i = 0; $i < count($titulos_filas); $i++) { ?>
                                    <td class='sorting_1'><?php echo $row[$i]; ?></td>
                                <? } ?>
                                <td>
                                    <input type="checkbox" name="seleccionado<?= $key ?>" id="seleccionado<?= $key ?>" value="<?= $row[0] ?>" />
                                    <!--<input type="radio" name="seleccionado" value="<? //=$row[0]              ?>">-->
                                </td>    
                            </tr>

                            <?php
                        }
                        ?>
                    </tbody>
                </table>                
            </div>
        </div>

        <script>
            function crearInputHidden(valor){
                if(valor=="modificar"){
                    <?//$valor = "pagina=detallesCatalogo";
                        $valor="&opcion=modificar";
                        $valor.="&nombreTabla=" . $nombre_tabla_bd;
                        $valor.="&bloqueGrupo=" . $esteBloque["grupo"];
                        $valor = $this->miConfigurador->fabricaConexiones->crypto->codificar($valor);?>
                }else if(valor=="eliminar"){
                    <?//$valor = "pagina=detallesCatalogo";
                        $valor="&opcion=eliminar";
                        $valor.="&nombreTabla=" . $nombre_tabla_bd;
                        $valor.="&bloqueGrupo=" . $esteBloque["grupo"];
                        $valor = $this->miConfigurador->fabricaConexiones->crypto->codificar($valor);?>
                }else if(valor=="crear"){
                    <?//$valor = "pagina=detallesCatalogo";
                        $valor="&opcion=crear";
                        $valor.="&nombreTabla=" . $nombre_tabla_bd;
                        $valor.="&bloqueGrupo=" . $esteBloque["grupo"];
                        $valor = $this->miConfigurador->fabricaConexiones->crypto->codificar($valor);?>
                }
                                
                value = <?echo "'".$valor."'";?>;
                
                obj = document.createElement("input");
                obj.type = "hidden";    
                obj.name = valor;
                obj.id = valor;
                obj.value = value;       
                
                formulario =  document.getElementById("formulario");                
                formulario.appendChild(obj);   
                formulario.appendChild(ob);   
                
            }
                
            
        </script>

        <div>
            <table>
                <tr>
                    <td>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td width="30%">
                        <input type=image src="<?= $rutaBloque ?>/glyphicons_029_notes_2.png" title="Modificar" onclick="crearInputHidden('modificar')">                     
                    </td>                            
                    <td width="30%">                        
                        <input type="image" name="image" src="<?= $rutaBloque ?>/glyphicons_150_edit.png" onclick="crearInputHidden('crear')">
                    </td>

                    <td width="30%">    
                        <input type="image" name="image" src="<?= $rutaBloque ?>/glyphicons_016_bin.png" onclick="crearInputHidden('eliminar')">
                    </td>

                </tr>
            </table>
        </div>   

    </form>

    <?
}

////---------------Fin formulario (</form>)--------------------------------
//$atributos["verificarFormulario"] = $verificarFormulario;
//echo $this->miFormulario->formulario("fin", $atributos);
?>