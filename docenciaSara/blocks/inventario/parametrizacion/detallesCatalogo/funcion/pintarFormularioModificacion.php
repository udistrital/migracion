<?
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarCatalogo";

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

$valorCodificado = "pagina=detallesCatalogo";
$valorCodificado.="&accion=".$accion;
$valorCodificado.="&opcion=" . $_REQUEST["opcion"];
$valorCodificado.="&nombreTabla=" . $nombre_tabla;
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

$cont_combo = 0;
?>


<form name="formulario" method="post" enctype="multipart/form-data">     

    <table class="bordered" width ="100%" align="center">
        <tr>
            <th colspan="2">MODIFICACIÓN DE PARÁMETROS</th>
        </tr>
        <tr>
            <td>
                <table align="center">
                    <tr>
                        <td class="texto_elegante"><? echo $label[0]; ?></td>
                        <td class="texto_elegante"><input  class="cuadrosimple" maxlength="30" size="12" tabindex="1" readonly=”readonly” name="<? echo "campo0"; ?>" id="<? echo "campo0"; ?>" value="<? echo $valorModificar[0]; ?>" ></td>
                    </tr>
                    <?
                    for ($i = 1; $i < count($label); $i++) {
                        ?>
                        <tr>
                            <td class="texto_elegante"><? echo $label[$i]; ?></td>
                            <?
                            if (trim($label[$i]) == "TIPO DOC") {
                                echo "<td>";

                                $datos[0] = array(0 => "CC", 1 => "CC");
                                $datos[1] = array(0 => "TI", 1 => "TI");
                                $datos[2] = array(0 => "CE", 1 => "CE");
                                $datos[3] = array(0 => "PA", 1 => "PA");

                                $this->pintarCombo("campo" . $i, $datos);
                                echo "</td>";
                            } else if (strlen(strstr($parametro[$i], "combo")) > 0) {
                                echo "<td>";
                                $this->pintarCombo("campo" . $i, $datos_combo[$cont_combo], $valorModificar[$i]);
                                echo "</td>";
                                $cont_combo++;
                            } else {
                                $script_validacion = $this->validarTipoCampo(trim($tipo_campo[$i]));
                                ?>
                                <td class="texto_elegante"><input  onkeypress="<? echo $script_validacion; ?>" class="cuadrosimple" maxlength="<? echo $longitud_campo[$i]; ?>" size="<? echo $longitud_campo[$i]; ?>s" tabindex="1" name="<? echo "campo" . $i; ?>" id="<? echo "campo" . $i; ?>" value="<? echo $valorModificar[$i]; ?>" ></td>
                            <? } ?>
                        </tr>
                    <? } ?>
                    <tr>
                        <td align ="center" colspan="2">
                            <div>  
                                <input type='hidden' name='formSaraData' value='<? echo $valorCodificado; ?>'>
                                <input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="guardar" value='Guardar'/>
                            </div>         
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>  

</form>

