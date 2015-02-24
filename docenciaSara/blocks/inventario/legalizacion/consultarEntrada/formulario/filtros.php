<?php
$unBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarElemento";

$rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site");

if ($unBloque["grupo"] == "") {
    $rutaBloque.="/blocks/" . $unBloque["nombre"];
} else {
    $rutaBloque.="/blocks/" . $unBloque["grupo"] . "/" . $unBloque["nombre"];
}

$directorio = $rutaBloque . "/index.php?";
$directorio.= $this->miConfigurador->getVariableConfiguracion("enlace");

$valorCodificado = "pagina=consultarElemento";
//$valorCodificado.="&accion=consultarElemento";
$valorCodificado.="&opcion=consultar";
//$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];

$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

$conexion = "inventario";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

//Este se considera un error fatal
if (!$esteRecursoDB) {
    exit;
}

$cadenaClases = $this->sql->cadena_sql("clase", "");
$clases = $esteRecursoDB->ejecutarAcceso($cadenaClases, "busqueda");

$cadenaOrdenadores = $this->sql->cadena_sql("ordenador", "", "");
$ordenadores = $esteRecursoDB->ejecutarAcceso($cadenaOrdenadores, "busqueda");

/* * ****************************** */
?>
<form name="formulario" method="post" enctype="multipart/form-data">    
    <br>
    <h2>Filtros Consultar Entrada</h2>
    <div>
        <table align="center">
            <tbody aria-relevant='all' aria-live='polite' role='alert'>
                <tr>
                    <td align="center"> Entrada </td>
                    <td align="center"> Fecha Entrada </td>
                    <td align="center"> Proveedor </td>
                </tr>
                <tr>                
                    <td><input maxlength="30" size="10" tabindex="1" name="entrada" id="entrada" value="" ></td>
                    <td>
                        <div>
                            <input name="txtFechaEntrada" type="text" id="txtFechaEntrada" size="15" value="" readonly="readonly"/>
                            <input align="center" type=image src='<?php echo $rutaBloque . "/css/images/cal.png"; ?>' name="btnFechaEntrada" id="btnFechaEntrada">
                            <script type="text/javascript">
                                Calendar.setup({
                                    inputField: "txtFechaEntrada",
                                    button: "btnFechaEntrada",
                                    align: "Tr"
                                });
                            </script>
                        </div>
                    </td>
                    <td><input maxlength="30" size="10" tabindex="1" name="proveedor" id="proveedor" value="" ></td>                    
                </tr>
                <tr>
                    <td align="center"> Clase Entrada </td>
                    <td align="center"> Ordenador Gasto </td>                    
                </tr>    
                <tr>
                    <td>
                        <select id ="claseEntrada" name="claseEntrada" size="1"> 
                            <option value=''></option>
                            <?
                            for ($i = 0; $i < count($clases); $i++) {
                                ?>                                 
                                <option value='<? echo $clases[$i][0]; ?>'><? echo $clases[$i][1]; ?></option>               
                            <? } ?>
                        </select>
                    </td>
                    <td>
                        <select id ="ordenadorGasto" name="ordenadorGasto" size="1"> 
                            <option value=''></option>
                            <?
                            for ($i = 0; $i < count($ordenadores); $i++) {
                                ?>                                 
                                <option value='<? echo $ordenadores[$i][0]; ?>'><? echo $ordenadores[$i][1]; ?></option>               
                            <? } ?>
                        </select>                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2"> 
                        <input type='hidden' name='formSaraData' value='<? echo $valorCodificado; ?>'>
                    </td>
                    <td>
                        <input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="consultar" value='Consultar'/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>