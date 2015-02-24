<table class="tablaMarco">
	<tr>
		<td>
			<table cellpadding="7" cellspacing="0" class="bloquelateral">
				<tbody>
					<tr class="bloquelateralencabezado">
					<td>Instrucciones</td>
					</tr>
					<tr class="bloquecentralcuerpo">
					<td style="width: 50%; vertical-align: top; ">
					Para ingresar registros por lotes seguir los siguientes pasos:
					
					<ul>
						<li>Descargar el archivo de la plantilla.</li>
						<li>Ingresar los registros. Uno por cada fila. Siguiendo la instrucciones espec&iacute;ficas del formato.</li>
						</li>
						<li>Guardar el archivo (conservando la extensi&oacute;n .xls).</li>
						<li>Diligenciar el presente formulario colocando en el campo correspondiente la ruta completa al archivo creado.</li>
						<li>Seleccionar el bot&oacute;n de Aceptar. El sistema procesar&aacute; el archivo e informar&aacute; los
						resultados de la operaci&oacute;n.</li>
						</ul>
						A cada registro se le asigna un identificador &uacute;nico. No se puede realizar edici&oacute;n o correcci&oacute;n de
						la informaci&oacute;n de registros usando directamente este m&eacute;todo.
						<hr class="hr_subtitulo">
					</td>
					</tr>
					<tr>
						<td>
							<table align="center" width="100%">
								<tr>
									<td>
										<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/excel.jpg"  border="0" />	
									</td>
									<td class="textoCentral">
										<a href="<?echo $configuracion["host"].$configuracion["site"].'/documento/plantilla_general_pregrados.xls'; ?>"> Plantilla General para Pregrados</a><br>
										<a href="<?echo $configuracion["host"].$configuracion["site"].'/documento/plantilla_general_postgrados_creditos.xls'; ?>"> Plantilla Postgrados (Pago por Cr&eacute;ditos)</a><br>
										<a href="<?echo $configuracion["host"].$configuracion["site"].'/documento/plantilla_general_postgrados_smlv.xls'; ?>"> Plantilla Postgrados (Pago por S.M.L.V)</a><br>
									</td>
								</tr>
								<tr>
									<td>
									<img src="<? echo $configuracion["host"].$configuracion["site"].$configuracion["grafico"]?>/pdfGrande.png"  border="0" />
									</td>
									<td class="textoCentral">
									<a href="<?echo $configuracion["host"].$configuracion["site"].'/documento/lote_recibos.pdf'; ?>"> Instrucciones para ingresar registros en la plantilla.</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</td>
	</tr>
</table>
