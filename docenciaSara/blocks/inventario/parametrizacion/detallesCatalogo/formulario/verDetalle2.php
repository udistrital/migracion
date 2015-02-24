<?
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "consultarCatalogo";

$directorio = $this->miConfigurador->getVariableConfiguracion("host");
$directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
$directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");

$valorCodificado = "pagina=crearParametro";
$valorCodificado.="&opcion=eliminar";
$valorCodificado.="&nombreTabla=" . $nombre_tabla;
$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

$cont_combo = 0;
?>


<form name="formulario" method="post" enctype="multipart/form-data">     

    <table class="bordered" width ="100%" align="center">
        <tr>
            <th colspan="2">CREACIÓN DE PARÁMETROS</th>
        </tr>
        <tr>
            <td>crear</td>
            <td><input type="checkbox" name="ch" id="ch" value="juemadre" /></td>
        </tr>

        <tr>
            <td align ="center" colspan="2">
                <div>  
                    <input type='hidden' name='formSaraData' value='<? echo $valorCodificado; ?>'>
                    <input class="btTxt submit" type="button" style='cursor:pointer;' onclick='submit()' name="guardar" value='Guardar'/>
                </div>         
            </td>
        </tr>
    </table>

</form>

