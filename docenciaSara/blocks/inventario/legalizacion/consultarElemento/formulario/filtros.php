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
$cont_combo = 0;

/* * ****************************** */

?>
<form name="formulario" method="post" enctype="multipart/form-data">    
    <br>
    <h2>Filtros Consultar Entrada</h2>
    <div>
        <table align="center">
            <tbody aria-relevant='all' aria-live='polite' role='alert'>
                <tr>
                    <td align="center"> Placa </td>
                    <td align="center"> Serial </td>
                </tr>
                <tr>                
                    <td>
                        <input maxlength="30" size="12" tabindex="1" name="placa" id="placa" value="" >
                    </td>
                    <td>
                        <input maxlength="30" size="12" tabindex="1" name="serial" id="serial" value="" >
                    </td>
                </tr>
                <tr>
                    <td colspan="2"> 
                        <input type='hidden' name='formSaraData' value='<? echo $valorCodificado; ?>'>
                        <input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="consultar" value='Consultar'/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>