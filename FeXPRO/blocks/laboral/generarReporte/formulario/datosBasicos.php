<?php
if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
$esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");
$nombreFormulario = "modificarSolicitud";

$rutaBloque=$this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site")."/blocks/";
$rutaBloque.= $esteBloque['grupo']."/".$esteBloque['nombre'];

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

/*****************************************************************************/
$conexion = "oracle";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
$cadena_sql = $this->sql->cadena_sql("buscarDatosBasicos", "");
$resultado = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
//var_dump($resultado);exit;
/*****************************************************************************/

if ($resultado) {
	?>
<br>
<div align="center">
	<h1>
		Certificado de <i>datos basicos</i>
	</h1>
</div>
<div id='demo'>
	<div id='example_wrapper' class='dataTables_wrapper' role='grid'>
		<table aria-describedby='example_info' class='display dataTable'
			id='example' border='0' cellpadding='0' cellspacing='0'>
			<!--Columnas-->
			<thead>
				<tr role='row'>
					<th aria-label='Documento' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Identificacion</th>
					<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Nombre</th>
					<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Cargo</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Dependencia</th>
				</tr>
			</thead>

			<!--Pie de Pagina-->
			<tfoot>
				<tr>
					<th aria-label='Documento' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Identificacion</th>
					<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Nombre</th>
					<th aria-label='nombres' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Cargo</th>
					<th aria-label='Descripciont' aria-sort='ascending'
						style='width: 128px;' colspan='1' rowspan='1'
						aria-controls='example' tabindex='0' role='columnheader'
						class='sorting_asc'>Dependencia</th>
				</tr>
			</tfoot>
			<tbody aria-relevant='all' aria-live='polite' role='alert'>

				<?php				
				foreach ($resultado as $key => $row) {
				?>
				<tr class='gradeA odd' align=center>
					<td class='  sorting_1'><a
						href="
						<?php
                           $valorCodificado = "pagina=generarReporte";
                           $valorCodificado .="&certificado=" . $row[4];
                           $valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($valorCodificado, $directorio);
                           echo $valorCodificado;
                        ?>
					"> <?php echo $row[4]; ?>
					
					</td>
					</a>
					<td><?php echo $row[0]." ".$row[1]." ".$row[2]." ".$row[3]; ?></td>
					<td><?php echo $row[5]; ?></td>
					<td><?php echo $row[6]; ?></td>
				</tr>
				<?php
                    }
                    ?>
			</tbody>
		</table>
		<!--div id='example_info' class='dataTables_info'>Showing 1 to 10 of 57 entries</div><div id='example_paginate' class='dataTables_paginate paging_two_button'><a aria-controls='example' id='example_previous' class='paginate_disabled_previous' tabindex='0' role='button'>Previous</a><a aria-controls='example' id='example_next' class='paginate_enabled_next' tabindex='0' role='button'>Next</a></div-->
	</div>
</div>
<?php
//---------------Fin formulario (</form>)--------------------------------
}else{
	//-------------------------------Mensaje-------------------------------------
$esteCampo="mensajeNoSolicitudes";
$atributos["id"]="mensaje"; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
$atributos["etiqueta"]="";
$atributos["estilo"]="";
$atributos["tipo"]="validation";
$atributos["mensaje"]=$this->lenguaje->getCadena($esteCampo);
echo $this->miFormulario->cuadroMensaje($atributos);
}
$atributos["verificarFormulario"] = $verificarFormulario;
echo $this->miFormulario->formulario("fin", $atributos);
?>